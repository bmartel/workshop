<?php namespace Bmartel\Workshop\Console;

use Bmartel\Workshop\Builders\Base;
use Bmartel\Workshop\Builders\Event;
use Bmartel\Workshop\Builders\Listener;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BuildListenerCommand extends Command
{

    protected $event;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->event = new Event();
    }

    protected function configure()
    {

        $this
            ->setName('build:listener')
            ->setAliases(['make:listener'])
            ->setDescription('Generate an event listener class')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the class.'
            )->addOption(
                'event',
                null,
                InputOption::VALUE_REQUIRED,
                'The event class being listened for.'
            )->addOption(
                'queued',
                null,
                InputOption::VALUE_NONE,
                'Indicates the event listener should be queued.'
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
            $queued = $input->getOption('queued');
            list($eventClass, $eventNamespace) = $this->event->getClassAndFullNamespace($input->getOption('event'));

            $listenerFile = $this->builder
                ->addReplacement('DummyEvent', $eventClass)
                ->addReplacement('DummyFullEvent', $eventNamespace)
                ->create($name, null, compact('queued'));

            $output->writeln("<info>Created Listener:</info> $listenerFile");
        }

    }

    /**
     * @return Base
     */
    protected function getBuilder()
    {
        return new Listener();
    }
}
