<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Request;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class BuildRequestCommand extends Command
{

    protected function configure()
    {

        $this
            ->setName('build:request')
            ->setAliases(['make:request'])
            ->setDescription('Generate a request class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the class.'
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

            $requestFile = $this->builder->create($name);

            $output->writeln("<info>Created Request:</info> $requestFile");
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Request();
    }
}
