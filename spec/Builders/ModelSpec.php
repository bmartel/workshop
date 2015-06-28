<?php namespace spec\Bmartel\Workshop\Builders;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class ModelSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bmartel\Workshop\Builders\Model');
    }

    function it_can_generate_a_namespace_and_path_to_house_models()
    {
        $this->getNamespaceAndPathForType('Thing')->shouldReturn(['Bmartel\\Workshop\\Models', 'src/Models/Thing.php']);
    }

    function it_can_generate_a_namespace_and_path_to_house_models_with_override()
    {
        $this->getNamespaceAndPathForType('Bmartel\\Workshop\\Domain\\Model')
            ->shouldReturn(['Bmartel\\Workshop\\Domain', 'src/Domain/Model.php']);
    }

    function it_throws_an_exception_when_a_full_namespace_is_not_correctly_provided()
    {
        $this->shouldThrow('UnexpectedValueException')->duringGetNamespaceAndPathForType('Domain\\Model');
    }
}
