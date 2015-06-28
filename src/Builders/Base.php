<?php namespace Bmartel\Workshop\Builders;


use Illuminate\Support\Str;
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

    public function getNamespaceAndPathForType($namespace = '')
    {
        list($rootNamespace, $rootPath) = $this->getRootNamespaceAndPath();

        // If there is an explicit namespace given, use it instead
        if($namespace) {
            if(!stristr($namespace, $rootNamespace)) {
                throw new UnexpectedValueException('Must provide the full namespace path to the file.');
            }

            $path = $rootPath . '/' . trim(str_replace('\\', '/', str_replace($rootNamespace, '', $namespace)), '/');
        }

        // Otherwise use the given generator type
        else {
            $namespace = $rootNamespace . '\\' . $this->builderType;
            $path = $rootPath . '/' . $this->builderType;
        }

        return [$namespace, $path];
    }

    public function getRootNamespaceAndPath($currentPath = '')
    {

        $composerPath = ($currentPath ? $currentPath . '/' : '') . 'composer.json';
        $composerJson = json_decode(file_get_contents($composerPath), true);

        if (!$path = $this->getPsr4Path($composerJson)) {
            $path = $this->getPsr0Path($composerJson);
        }

        return $path;
    }

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
     * Get the full path name to the file.
     *
     * @param  string $name
     * @param  string $path
     * @return string
     */
    abstract protected function getPath($name, $path);

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

    protected function makeDirectory($path)
    {
        // Create directory if it is not found
        if (!$this->filesystem->exists($path)) {

            $this->filesystem->mkdir($path);
        }
    }

}