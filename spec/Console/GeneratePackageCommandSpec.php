<?php namespace spec\Bmartel\Workshop\Console;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class GeneratePackageCommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bmartel\Workshop\Console\GeneratePackageCommand');
    }
}
