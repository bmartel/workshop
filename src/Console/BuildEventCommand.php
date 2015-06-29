<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Event;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class BuildEventCommand extends Command
{

    protected function configure()
    {

        $this
            ->setName('build:event')
            ->setAliases(['make:event'])
            ->setDescription('Generate an event class')
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

            $eventFile = $this->builder->create($name);
            $output->writeln("<info>Created Event:</info> $eventFile");
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Event();
    }
}
