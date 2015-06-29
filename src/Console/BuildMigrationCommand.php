<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Migration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BuildMigrationCommand extends Command
{

    protected function configure()
    {

        $this
            ->setName('build:migration')
            ->setAliases(['make:migration'])
            ->setDescription('Generate a database migration')
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

            $table = $input->getOption('table');

            $create = $input->getOption('create');

            if (!$table && is_string($create)) {
                $table = $create;
            }

            $file = pathinfo($this->builder->create($name, 'migrations', $table, $create), PATHINFO_FILENAME);

            return $output->writeln("<info>Created Migration:</info> $file");
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Migration();
    }
}
