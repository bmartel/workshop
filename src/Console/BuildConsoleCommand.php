<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BuildConsoleCommand extends Command
{

    protected $pluralizeNamespace = false;

    protected function configure()
    {

        $this
            ->setName('build:console')
            ->setAliases(['make:console'])
            ->setDescription('Generate an Artisan Command.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the command.'
            )
            ->addOption(
                'command',
                null,
                InputOption::VALUE_OPTIONAL,
                'The terminal command that should be assigned.',
                'command:name'
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

            $command = $input->getOption('command');

            $consoleFile = $this->builder
                ->addReplacement('dummy:command', $command)
                ->create($name);

            $output->writeln("<info>Created Controller:</info> $consoleFile");
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Console();
    }
}
