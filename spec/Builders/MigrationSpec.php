<?php

namespace spec\Bmartel\Workshop\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MigrationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bmartel\Workshop\Builders\Migration');
    }
}
