<?php namespace Bmartel\Workshop\Builders;

use Illuminate\Support\Str;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use UnexpectedValueException;


abstract class Base
{

    /**
     * @var string
     */
    protected $builderType = '';

    /**
     * @var Filesystem
     */
    protected $filesystem;


    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * Get the path to the template.
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return __DIR__ . '/../../templates';
    }

    /**
     * @return bool
     */
    public function isNotPackageRoot()
    {
        return !$this->filesystem->exists('composer.json');
    }

    /**
     * @param string $namespace
     * @return array
     */
    public function getNamespaceAndPathForType($namespace)
    {
        list($rootNamespace, $rootPath) = $this->getRootNamespaceAndPath();

        $namespace = explode('\\', $namespace);
        $class = array_pop($namespace) . '.php';

        // If there is an explicit namespace given, use it instead
        if ($namespace) {

            $namespace = implode('\\', $namespace);

            if (!stristr($namespace, $rootNamespace)) {
                throw new UnexpectedValueException('Must provide the full namespace path to the file.');
            }

            $path = $rootPath . '/' . trim(str_replace('\\', '/', str_replace($rootNamespace, '', $namespace)), '/');

        } // Otherwise use the given generator type
        else {
            $namespace = $rootNamespace . '\\' . $this->builderType;
            $path = $rootPath . '/' . $this->builderType;
        }

        $path = "$path/$class";

        return [$namespace, $path];
    }

    /**
     * @param string $currentPath
     * @return array|string
     */
    public function getRootNamespaceAndPath($currentPath = '')
    {

        $composerPath = ($currentPath ? $currentPath . '/' : '') . 'composer.json';
        $composerJson = json_decode(file_get_contents($composerPath), true);

        if (!$path = $this->getPsr4Path($composerJson)) {
            $path = $this->getPsr0Path($composerJson);
        }

        return $path;
    }

    /**
     * @param $composerJson
     * @return array|string
     */
    private function getPsr4Path($composerJson)
    {
        if (!empty($composerJson['autoload']['psr-4'])) {

            $path = $composerJson['autoload']['psr-4'];
            $namespace = rtrim(array_keys($path)[0], '\\');

            $path = trim(array_values($path)[0], '/');

            return [$namespace, $path];
        }

        return '';
    }

    /**
     * @param $composerJson
     * @return array
     */
    private function getPsr0Path($composerJson)
    {
        if (!empty($composerJson['autoload']['psr-0'])) {

            $path = $composerJson['autoload']['psr-0'];
            $namespace = rtrim(array_keys($path)[0], '\\');

            $path = trim(array_values($path)[0], '/') . '/' . str_replace('\\', '/', $namespace);

            return [$namespace, $path];
        }

        return ['', ''];
    }

    /**
     * @param $name
     * @param $path
     * @param array $data
     *
     * @return mixed
     */
    public function create($name, $path = '', $data = [])
    {
        list($namespace, $filepath) = $this->getNamespaceAndPathForType($name);

        $path = explode('/', $filepath);
        $class = array_pop($path);
        $path = implode('/', $path);

        $data['replacements'] = [
            'DummyNamespace' => $namespace,
            'DummyClass' => $class
        ];

        $this->makeDirectory($path);

        $template = $this->loadTemplate($name, $data);

        $this->buildFromTemplate($template, $filepath, $data);
    }

    /**
     * Get the class name of a file name.
     *
     * @param  string $name
     * @return string
     */
    protected function getClassName($name)
    {
        return Str::studly($name);
    }

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    abstract protected function getTemplate($name, $data = []);

    /**
     * @param $name
     * @param array $data
     * @return string
     * @throws FileNotFoundException
     */
    public function loadTemplate($name, $data = []) {

        $template = $this->getTemplatePath() . '/' . $this->getTemplate($name, $data);

        if(file_exists($template)) {
            throw new FileNotFoundException("Template $name was not found. Check to ensure the path is correct and the file exists.");
        }

        return file_get_contents($template);
    }

    /**
     * @param $path
     */
    protected function makeDirectory($path)
    {
        // Create directory if it is not found
        if (!$this->filesystem->exists($path)) {

            $this->filesystem->mkdir($path);
        }
    }

    /**
     * @param $template
     * @param $path
     * @param array $data
     */
    protected function buildFromTemplate($template, $path, $data = [])
    {
        $template = $this->populateTemplate($template, $data);

        $this->filesystem->dumpFile($path, $template);
    }

    /**
     * @param $template
     * @param $data
     * @return string
     */
    protected function populateTemplate($template, $data)
    {
        if(!empty($data['replacements'])) {
            foreach ($data['replacements'] as $placeholder => $replacement) {
                $template = str_replace($placeholder, $replacement, $template);
            }
        }

        return $template;
    }

}