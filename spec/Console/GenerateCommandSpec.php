<?php

namespace spec\Bmartel\LaravelPackage\Console;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenerateCommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bmartel\LaravelPackage\Console\GenerateCommand');
    }
}
