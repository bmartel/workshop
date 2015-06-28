<?php namespace Bmartel\Workshop\Builders;


class Migration extends Base
{

    /**
     * Create a new migration at the given path.
     *
     * @param  string $name
     * @param  string $path
     * @param  string $table
     * @param  bool $create
     * @return string
     */
    public function create($name, $path = '', $table = null, $create = false)
    {
        $this->makeDirectory($path);

        $path = $this->getPath($name, $path);

        // First we will get the template file for the migration, which serves as a type
        // of template for the migration. Once we have those we will populate the
        // various place-holders, and save the file.
        $template = $this->getTemplate($table, compact('create'));

        $this->filesystem->dumpFile($path, $this->populateTemplate($template, compact('name') + compact('table')));

        return $path;
    }

    /**
     * Get the full path name to the file.
     *
     * @param  string $name
     * @param  string $path
     * @return string
     */
    protected function getPath($name, $path = '')
    {
        return ($path ? "$path/" : '') . "{$this->getDatePrefix()}_$name.php";
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
     * @param  string $table
     * @param array $data
     * @return string
     */
    protected function getTemplate($table, $data = [])
    {
        if (is_null($table)) {
            $template = 'blank.stub';
        } else {
            $template = empty($data['create']) ? 'update.stub' : 'create.stub';
        }

        return $template;
    }

    public function getTemplatePath()
    {
        return parent::getTemplatePath() . '/migration';
    }

    /**
     * @param $template
     * @param $data
     * @return mixed
     */
    protected function populateTemplate($template, $data = [])
    {

        $template = str_replace('DummyClass', $this->getClassName($data['name']), $template);

        if (!is_null($data['table'])) {
            $template = str_replace('DummyTable', $data['table'], $template);
        }

        return $template;
    }

}
