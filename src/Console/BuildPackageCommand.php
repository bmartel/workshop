<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Package;
use Bmartel\Workshop\Support\InputData;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BuildPackageCommand extends Command
{

    protected function configure()
    {

        $this
            ->setName('build')
            ->setDescription('Generate a Laravel package skeleton.')
            ->addArgument(
                'package',
                InputArgument::REQUIRED,
                'Ex. vendor/package. What vendor name should this package be published under? What is the name of this package? (This is typically your name, or the name of the company the package is written for)'
            )
            ->addOption(
                'data',
                null,
                InputOption::VALUE_OPTIONAL,
                'Template and file replacement data'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        list($vendor, $package) = explode('/', $input->getArgument('package'));

        // If either the vendor or package is missing, error
        if (!$vendor || !$package) {
            return $output->writeln('<error>Must provide vendor/package.</error>');
        }

        // Check if some data was provided and map it accordingly
        $data = $input->getOption('data') ?: [];

        if ($data) {
            $data = (new InputData)->parse($data);
        }

        // Create the package
        $this->builder->create($vendor, $package, $data);

        return $output->writeln('<info>Package generated successfully!</info>');
    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Package(__DIR__ . '/../../templates/package');
    }
}
