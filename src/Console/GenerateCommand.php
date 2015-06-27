<?php

namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\InputData;
use Bmartel\Workshop\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{

    private $packageGenerator;

    public function __construct($name = null)
    {

        parent::__construct($name);

        $this->packageGenerator = new Generator(__DIR__ . '/../../templates');
    }

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
            )

            // TODO: Implement options below
//			->addOption(
//				'blueprint',
//				null,
//				InputOption::VALUE_OPTIONAL,
//				'Path to blueprint file to use to generate a package from'
//			)
//			->addOption(
//				'templates',
//				null,
//				InputOption::VALUE_OPTIONAL,
//				'Path to the templates that the generator should use'
//			)
//			->addOption(
//				'path',
//				null,
//				InputOption::VALUE_OPTIONAL,
//				'The path the generator should output the package'
//			)
//			->addOption(
//				'include',
//				null,
//				InputOption::VALUE_OPTIONAL,
//				'Only generate the files specified from the blueprint'
//			)
//			->addOption(
//				'exclude',
//				null,
//				InputOption::VALUE_OPTIONAL,
//				'Generate all files from the blueprint except those excluded'
//			)
        ;
    }

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
        $this->packageGenerator->createPackage($vendor, $package, $data);

        return $output->writeln('<info>Package generated successfully!</info>');

    }


}
