<?php

namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Migration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMigrationCommand extends Command
{

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if(parent::execute($input, $output)) {

            $name = $input->getArgument('name');

            $table = $input->getOption('table');

            $create = $input->getOption('create');

            if (!$table && is_string($create)) {
                $table = $create;
            }

            $file = $this->writeMigration($name, $table, $create);

            return $output->writeln("<info>Created Migration:</info> $file");
        }

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

        return pathinfo($this->builder->create($name, $path, $table, $create), PATHINFO_FILENAME);
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

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Migration();
    }
}
