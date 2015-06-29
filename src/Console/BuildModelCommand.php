<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Migration;
use Bmartel\Workshop\Builders\Model;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BuildModelCommand extends Command
{

    protected $migration;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->migration = new Migration();
    }

    protected function configure()
    {

        $this
            ->setName('build:model')
            ->setAliases(['make:model'])
            ->setDescription('Generate an Eloquent model')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the class.'
            )
            ->addOption(
                'migration',
                null,
                InputOption::VALUE_NONE,
                'Create a new migration file for the model.'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if (parent::execute($input, $output)) {

            $name = $input->getArgument('name');

            $migration = $input->getOption('migration');

            $modelFile = $this->builder->create($name);
            $output->writeln("<info>Created Model:</info> $modelFile");

            if ($migration) {
                list($namespace, $class) = $this->migration->extractClassFromNamespace($name);

                $table = Str::plural(Str::snake($class));
                $migrationFile = pathinfo($this->migration->create("create_{$table}_table", 'migrations', $table, true), PATHINFO_FILENAME);

                $output->writeln("<info>Created Migration:</info> $migrationFile");
            }
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Model();
    }
}
