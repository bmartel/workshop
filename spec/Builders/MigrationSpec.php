<?php namespace spec\Bmartel\Workshop\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class MigrationSpec extends ObjectBehavior
{
    protected $composerPath;

    function let()
    {
        $this->composerPath =  __DIR__ . '/composer.json';

        $composer = file_get_contents(__DIR__ . '/../stubs/composer.json');

        file_put_contents($this->composerPath, $composer);
    }

    function letGo()
    {
        if(file_exists($this->composerPath)) {
            unlink($this->composerPath);
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Bmartel\Workshop\Builders\Migration');
    }

    function it_can_retrieve_package_root_namespace_for_psr4()
    {
        $this->getRootNamespaceAndPath(__DIR__)->shouldReturn(['Acme\\Packagename', 'src']);
    }

    function it_can_retrieve_package_root_namespace_for_psr0()
    {
        $composer = json_decode(file_get_contents($this->composerPath), true);
        $composer['autoload']['psr-0'] = $composer['autoload']['psr-4'];
        unset($composer['autoload']['psr-4']);

        file_put_contents($this->composerPath, json_encode($composer));

        $this->getRootNamespaceAndPath(__DIR__)->shouldReturn(['Acme\\Packagename', 'src/Acme/Packagename']);
    }
}
