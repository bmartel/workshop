<?php namespace Bmartel\Workshop;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;


trait Template {

    /**
     * Set the location of the generator templates.
     *
     * @param $path
     * @return $this
     */
    public function setTemplatePath($path = null)
    {

        if($path) {
            $this->templatePath = $path;
        }

        // Refresh the mustache reference to the templates directory
        $this->mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader($this->templatePath)
        ]);

        return $this;
    }
}