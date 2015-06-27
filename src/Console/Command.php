<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Symfony\Component\Console\Command\Command as SymphonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymphonyCommand {

    /**
     * @var Base
     */
    protected $builder;

    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->builder = $this->getBuilder();
    }

    /**
     * @return Base
     */
    abstract protected function getBuilder();

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        if($isNotPackage = $this->builder->isNotPackageRoot()) {
            $output->writeln("<error>Cannot generate file. Generator commands must be run from the root of a composer package.</error>");
        }

        return !$isNotPackage;
    }
}