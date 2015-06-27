<?php

namespace Bmartel\Workshop\Builders\File;

class Migration extends Base
{

    /**
     * Create a new migration at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    public function create($name, $path, $table = null, $create = false)
    {
        $this->makeDirectory($path);

        $path = $this->getPath($name, $path);

        // First we will get the template file for the migration, which serves as a type
        // of template for the migration. Once we have those we will populate the
        // various place-holders, and save the file.
        $template = $this->getTemplate($table, $create);

        $this->filesystem->dumpFile($path, $this->populateTemplate($name, $template, $table));

        return $path;
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    /**
     * Get the migration template file.
     *
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    protected function getTemplate($table, $create)
    {
        if (is_null($table)) {
            return file_get_contents($this->getTemplatePath().'/blank.stub');
        }

        // We also have stubs for creating new tables and modifying existing tables
        // to save the developer some typing when they are creating a new tables
        // or modifying existing tables. We'll grab the appropriate stub here.
        else {
            $template = $create ? 'create.stub' : 'update.stub';

            return file_get_contents($this->getTemplatePath()."/{$template}");
        }
    }

    /**
     * Populate the place-holders in the migration stub.
     *
     * @param  string  $name
     * @param  string  $template
     * @param  string  $table
     * @return string
     */
    protected function populateTemplate($name, $template, $table)
    {

        $template = str_replace('DummyClass', $this->getClassName($name), $template);

        // Here we will replace the table place-holders with the table specified by
        // the developer, which is useful for quickly creating a tables creation
        // or update migration from the console instead of typing it manually.
        if (!is_null($table)) {
            $template = str_replace('DummyTable', $table, $template);
        }

        return $template;
    }

    /**
     * Get the full path name to the file.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     */
    protected function getPath($name, $path)
    {
        return $path.'/'.$this->getDatePrefix().'_'.$name.'.php';
    }

    public function getTemplatePath()
    {
        return parent::getTemplatePath() . '/migration';
    }

}
