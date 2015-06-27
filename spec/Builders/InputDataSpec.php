<?php

namespace spec\Bmartel\Workshop\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InputDataSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bmartel\Workshop\Builders\InputData');
    }

    function it_can_parse_data_from_a_string_and_map_to_an_array()
    {

        $this->parse('')->shouldReturn([]);

        $this->parse('key1:value1,key2:value2')->shouldReturn([
            'key1' => 'value1',
            'key2' => 'value2'
        ]);
    }


}
