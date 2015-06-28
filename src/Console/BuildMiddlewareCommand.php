<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Middleware;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BuildMiddlewareCommand extends Command
{

    protected function configure()
    {

        $this
            ->setName('build:middleware')
            ->setAliases(['make:middleware'])
            ->setDescription('Generate a middleware class')
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

            $middlewareFile = $this->builder->create($name);
            $output->writeln("<info>Created Middleware:</info> $middlewareFile");
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Middleware();
    }
}
