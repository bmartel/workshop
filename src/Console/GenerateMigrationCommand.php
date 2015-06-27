<?php

namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\File\Migration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMigrationCommand extends Command
{

    private $generator;

    public function __construct($name = null)
    {

        parent::__construct($name);

        $this->generator = new Migration();
    }

    protected function configure()
    {

        $this
            ->setName('generate:migration')
            ->setAliases(['g:migration'])
            ->setDescription('Generate a Laravel Migration')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the migration.'
            )
            ->addOption(
                'create',
                null,
                InputOption::VALUE_OPTIONAL,
                'The table to be created.'
            )
            ->addOption(
                'table',
                null,
                InputOption::VALUE_OPTIONAL,
                'The table to migrate.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // It's possible for the developer to specify the tables to modify in this
        // schema operation. The developer may also specify if this table needs
        // to be freshly created so we can create the appropriate migrations.
        $name = $input->getArgument('name');

        $table = $input->getOption('table');

        $create = $input->getOption('create');

        if (!$table && is_string($create)) {
            $table = $create;
        }

        if($this->generator->isNotPackageRoot()) {
            $output->writeln("<error>Cannot generate file. Generator commands must be run from the root of a composer package.</error>");
            return;
        }

        // Now we are ready to write the migration out to disk. Once we've written
        // the migration out, we will dump-autoload for the entire framework to
        // make sure that the migrations are registered by the class loaders.
        $file = $this->writeMigration($name, $table, $create);

        $output->writeln("<info>Created Migration:</info> $file");
    }

    /**
     * Write the migration file to disk.
     *
     * @param  string  $name
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    protected function writeMigration($name, $table, $create)
    {
        $path = $this->getMigrationPath();

        return pathinfo($this->generator->create($name, $path, $table, $create), PATHINFO_FILENAME);
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return 'migrations';
    }

}
