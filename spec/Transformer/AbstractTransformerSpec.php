<?php

namespace spec\LaraPackage\Api\Transformer;

use LaraPackage\Api\Transformer\AbstractTransformer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractTransformerSpec extends ObjectBehavior
{
    function it_changes_an_arrays_keys_from_external_to_internal()
    {
        $externalArray = [
            'identifier' => 5,
            'title' => 'foobar',
        ];

        $interalArray = [
            'id'   => 5,
            'name' => 'foobar',
        ];

        $this->reverseTransform($externalArray)->shouldReturn($interalArray);
    }

    function it_changes_an_arrays_keys_from_internal_to_external()
    {
        $interalArray = [
            'id'   => 5,
            'name' => 'foobar',
        ];

        $externalArray = [
            'identifier' => 5,
            'title' => 'foobar',
        ];

        $this->forwardTransform($interalArray)->shouldReturn($externalArray);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\Transformer\AbstractTransformer');
    }

    function it_transforms_an_associative_array_in_place_and_not_as_a_collection()
    {
        $interalArray = [
            'id'   => [5],
            'name' => ['foobar'],
        ];

        $externalArray = [
            'identifier' => [5],
            'title'      => ['foobar'],
        ];

        $this->forwardTransform($interalArray)->shouldReturn($externalArray);
        $this->reverseTransform($externalArray)->shouldReturn($interalArray);
    }

    function it_transforms_using_an_interator()
    {
        $interalArray = [
            'id'   => [5],
            'name' => ['foobar'],
        ];

        $externalArray = [
            'identifier' => [5],
            'title'      => ['foobar'],
        ];

        $interalIterator = new \ArrayIterator($interalArray);
        $externalIterator = new \ArrayIterator($externalArray);

        $this->forwardTransform($interalIterator)->shouldReturn($externalArray);
        $this->reverseTransform($externalIterator)->shouldReturn($interalArray);
    }

    function let()
    {
        $this->beAnInstanceOf('spec\LaraPackage\Api\Transformer\AbstractTransformerStub');
    }
}

class AbstractTransformerStub extends AbstractTransformer
{
    /**
     * [ privateKey => publicKey ]
     *
     * @return array
     */
    public function mappings()
    {
        return [
            'id'   => 'identifier',
            'name' => 'title',
        ];
    }
}
