<?php namespace Bmartel\Workshop\Builders;


use Illuminate\Support\Str;
use Symfony\Component\Filesystem\Filesystem;

abstract class Base
{

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