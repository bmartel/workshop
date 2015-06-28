<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Controller;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BuildControllerCommand extends Command
{

    protected function configure()
    {

        $this
            ->setName('build:controller')
            ->setAliases(['make:controller'])
            ->setDescription('Generate a Laravel Controller.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the class.'
            )
            ->addOption(
                'plain',
                null,
                InputOption::VALUE_NONE,
                'Create a plain controller.'
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

            $plain = $input->getOption('plain');

            $controllerFile = $this->builder->create($name, null, compact('plain'));
            $output->writeln("<info>Created Controller:</info> $controllerFile");
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Controller();
    }
}
