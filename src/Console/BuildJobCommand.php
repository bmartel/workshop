<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Job;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BuildJobCommand extends Command
{

    protected function configure()
    {

        $this
            ->setName('build:job')
            ->setAliases(['make:job'])
            ->setDescription('Generate a job class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the class.'
            )
            ->addOption(
                'queued',
                null,
                InputOption::VALUE_NONE,
                'Indicates the job should be queued.'
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
            $queued = $input->getArgument('queued');

            $jobFile = $this->builder->create($name, null, compact('queued'));

            $output->writeln("<info>Created Job:</info> $jobFile");
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Job();
    }
}
