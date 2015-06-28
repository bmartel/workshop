<?php namespace Bmartel\Workshop\Builders;

use Illuminate\Support\Str;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use UnexpectedValueException;


abstract class Base
{

    /**
     * @var bool
     */
    protected $pluralizeNamespace = true;

    /**
     * @var array
     */
    protected $replacements = [];

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
     * @return bool
     */
    public function isNotPackageRoot()
    {
        return !$this->filesystem->exists('composer.json');
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

        list($path, $class) = $this->extractClassFromPath($filepath);

        $this
            ->addReplacement('DummyNamespace', $namespace)
            ->addReplacement('DummyClass', $class);

        $this->makeDirectory($path);

        $template = $this->loadTemplate($name, $data);

        $this->buildFromTemplate($template, $filepath, $data);

        return $filepath;
    }

    /**
     * @param string $namespace
     * @return array
     */
    public function getNamespaceAndPathForType($namespace)
    {
        list($rootNamespace, $rootPath) = $this->getRootNamespaceAndPath();

        $namespace = explode('\\', str_replace('/', '\\', $namespace));
        $class = array_pop($namespace) . '.php';

        // If there is an explicit namespace given, use it instead
        if ($namespace) {

            $namespace = implode('\\', $namespace);

            if (!stristr($namespace, $rootNamespace)) {
                throw new UnexpectedValueException('Must provide the full namespace path to the file.');
            }

            $path = $rootPath . '/' . trim(str_replace('\\', '/', str_replace($rootNamespace, '', $namespace)), '/');

        } // Otherwise use the given generator type to namespace the resource
        else {
            $resourceType = $this->pluralizeNamespace ? Str::plural($this->builderType) : $this->builderType;
            $namespace = $rootNamespace . '\\' . $resourceType;
            $path = $rootPath . '/' . $resourceType;
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
     * @param $filepath
     * @return array
     */
    public function extractClassFromPath($filepath)
    {
        $path = explode('/', $filepath);
        $class = str_replace('.php', '', array_pop($path));
        $path = implode('/', $path);

        return [$path, $class];
    }

    /**
     * @param $namespace
     * @return array
     */
    public function extractClassFromNamespace($namespace)
    {
        $namespace = explode('\\', str_replace('/' , '\\' ,$namespace));
        $class = array_pop($namespace);
        $namespace = implode('\\', $namespace);

        return [$namespace, $class];
    }

    /**
     * @param $placeholder
     * @param $replacement
     * @return $this
     */
    public function addReplacement($placeholder, $replacement)
    {

        $this->replacements[$placeholder] = $replacement;

        return $this;
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
     * @param $name
     * @param array $data
     * @return string
     * @throws FileNotFoundException
     */
    public function loadTemplate($name, $data = [])
    {
        $name = $this->getTemplate($name, $data);
        $template = realpath($this->getTemplatePath() . '/' . $name);

        return file_get_contents($template);
    }

    /**
     * @param $name
     * @param array $data
     * @return string
     */
    abstract protected function getTemplate($name, $data = []);

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
     * @param array $data
     * @return string
     */
    protected function populateTemplate($template, $data = [])
    {
        $templateReplacements = array_merge($this->getTemplateReplacements(), $this->replacements);

        foreach ($templateReplacements as $placeholder => $replacement) {
            $template = str_replace($placeholder, $replacement, $template);
        }

        return $template;
    }

    /**
     * Return an array of [placeholder => replacement]
     * that will be used to populate the template.
     *
     * @return array
     */
    protected function getTemplateReplacements()
    {
        return [];
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

}