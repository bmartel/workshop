<?php

namespace Bmartel\Workshop;

use Bmartel\Workshop\Builders\Package;
use GitWrapper\GitWrapper;
use Mustache_Engine;
use Symfony\Component\Filesystem\Filesystem;

class Generator
{

    use Template;

    /**
     * @var string
     */
    protected $outputPath;

    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Mustache_Engine
     */
    protected $mustache;

    /**
     * @var Package
     */
    protected $packageBuilder;

    public function __construct($templatePath)
    {
        $this->packageBuilder = new Package();
        $this->filesystem = new Filesystem();
        $this->git = new GitWrapper();
        $this->setTemplatePath($templatePath);
    }

    /**
     * Create directory tree.
     *
     * @param string $dir
     * @return bool
     */
    public function createDirectory($dir = '')
    {

        $dirPath = rtrim($this->outputPath . '/' . ltrim($dir, '/'), '/');

        // Create directory if it is not found
        if (!$this->filesystem->exists($dirPath)) {

            $this->filesystem->mkdir($dirPath);
        }

        // Directory was created
        if ($this->filesystem->exists($dirPath)) {

            return true;
        }

        return false;
    }

    /**
     * Set the output directory path
     *
     * @param $path
     * @return $this
     */
    public function setOutputPath($path)
    {

        $this->outputPath = $path;

        // Create the base dir if it doesn't exist.
        $this->createDirectory();

        return $this;
    }

    /**
     * Generate and output a file based on a template and data set.
     *
     * @param $file
     * @param $template
     * @param array $data
     * @return bool
     */
    public function generateFileFrom($file, $template, array $data)
    {

        if ($template = $this->mustache->loadTemplate($template)) {

            $output = $template->render($data);

            return $this->outputToFile($output, $file);
        }

        return false;
    }

    public function newGitRepository()
    {

        $this->git
            ->init($this->outputPath)
            ->add('--all')
            ->commit('Initial Commit');
    }

    /**
     * Output the rendered template to a file.
     *
     * @param $output
     * @param $filePath
     * @return bool
     */
    private function outputToFile($output, $filePath)
    {

        // Separate the file and path pieces from the string
        list($file, $path) = $this->parseFileFromPath($filePath);

        // Get the absolute path to the file, relative to where the user is in the filesystem
        $pathToFile = trim($path, '/');

        $this->createDirectory($pathToFile);

        // Output the rendered content to a file
        return $this->createFile($filePath, $output);
    }

    /**
     * Parse and separate the filename from the path to the file.
     *
     * @param $file
     * @return array
     */
    private function parseFileFromPath($file)
    {

        $filePath = explode('/', $file);

        $fileName = array_pop($filePath);

        $pathToFile = $filePath ? implode('/', $filePath) : '';

        return [$fileName, $pathToFile];
    }

    /**
     * Create the file if it doesn't exist
     *
     * @param $filePath
     * @param $content
     * @return bool
     */
    private function createFile($filePath, $content)
    {

        $normalizedFilePath = $this->outputPath . '/' . trim($filePath, '/');

        if ($this->filesystem->exists($normalizedFilePath)) {
            return false;
        }

        $this->filesystem->dumpFile($normalizedFilePath, $content);

        return $this->filesystem->exists($normalizedFilePath);
    }

    /**
     * Generates a package skeleton from a blueprint
     *
     * @param $vendor
     * @param $package
     * @param array $data
     */
    public function createPackage($vendor, $package, $data = [])
    {

        // Load the blueprint
        $this->loadBlueprint($data);

        // Create the package directory
        $this->createPackageDirectory($package, $data);

        // Ensure essential placeholder replacement data exists
        $data = $this->addFormattedPackageData($vendor, $package, $data);

        // Generate the package structure
        $this->packageBuilder->parseBlueprint(function ($path, $files) use ($data) {

            $this->generateFiles($path, $files, $data);
        });

        // Initialize and add package to a new git repository
        $this->newGitRepository();
    }

    /**
     * @param $data
     * @throws Exceptions\InvalidBlueprintException
     */
    private function loadBlueprint($data)
    {

        if (!array_key_exists('blueprint', $data)) {
            $data['blueprint'] = '';
        }

        $this->packageBuilder->loadBlueprint($data['blueprint']);
    }

    /**
     * Generates files for the given paths and data
     *
     * @param $path
     * @param $files
     * @param array $data
     */
    private function generateFiles($path, $files, array $data = [])
    {

        foreach ($files as $file) {

            // Determine whether or not the filename has a template placeholder and replace it
            // if necessary with the correct data.
            $file = $this->replacePlaceholderInline($file, $data);

            // Map incoming filename to a template
            list($file, $template) = $this->getTemplateForFileName($file);

            // Relative path to file, from current working directory.
            $filePath = ltrim($path . '/' . $file, '/');

            // Create the file with the correctly parsed filename
            $this->generateFileFrom($filePath, $template, $data);
        }
    }

    /**
     * Attempts to map the incoming filename to a matching template.
     *
     * @param $file
     * @return string
     */
    public function getTemplateForFileName($file)
    {

        // Determine if there are any provided hints as to which template to use. template:file.ext
        list($template, $file) = $this->determineFileFromTemplate(explode(':', $file));

        list($fileName, $fileExtension) = explode('.', $template);

        // If the file is a hidden file .gitignore, .gitkeep, use the extension as the name.
        if (empty($fileName)) {

            $fileName = $fileExtension;
        }

        $requestedTemplate = $fileName . '.mustache';

        // Found a match so lets load it up.
        if ($this->filesystem->exists($this->templatePath . '/' . $requestedTemplate)) {

            return [$file, $requestedTemplate];
        }

        // Otherwise return a generic default (this must exist in your templates directory at minimum)
        return [$file, 'default.mustache']; // just a blank file
    }

    /**
     * Adds Package Vendor data to the template replacement data
     *
     * @param $vendor
     * @param $package
     * @param $data
     * @return array
     */
    private function addFormattedPackageData($vendor, $package, $data)
    {

        // Add the vendor and package name to the data array
        $data += compact('vendor');
        $data += compact('package');

        // Also add the titleized versions of both
        $data['Vendor'] = studly_case($vendor);
        $data['Package'] = studly_case($package);

        return $data;
    }

    /**
     * Replace and render filename from data
     *
     * @param $file
     * @param $data
     * @return string
     */
    public function replacePlaceholderInline($file, $data)
    {

        return (new Mustache_Engine)->render($file, $data);
    }

    /**
     * Create the directory to house the package files
     *
     * @param $package
     * @param $data
     */
    private function createPackageDirectory($package, $data)
    {

        $packageDirectory = $package;

        if (array_key_exists('path', $data)) {

            $path = rtrim($data['path'], '/');

            // Ensure that if the path contains the package directory,
            // only include it once in the project path
            $packageDirectory = stristr($path, $package) ? $path : $path . '/' . $packageDirectory;

        }

        // Set the directory for the package to be created in
        $this->setOutputPath($packageDirectory);
    }

    /**
     * @param $fileTemplate
     * @return array
     */
    private function determineFileFromTemplate($fileTemplate)
    {

        if (count($fileTemplate) > 1) {

            // add a tmp extension to appease the template logic to follow.
            $template = $fileTemplate[0] . '.tmp';
            $file = $fileTemplate[1];

        } else {

            $file = $template = $fileTemplate[0];
        }

        return [$template, $file];
    }
}
