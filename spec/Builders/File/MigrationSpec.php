<?php

namespace spec\Bmartel\Workshop\Builders\File;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MigrationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bmartel\Workshop\Builders\File\Migration');
    }
}
