<?php

namespace spec\LaraPackage\Api\MediaType;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonSpec extends ObjectBehavior
{
    function it_blows_up_if_given_a_non_iterable()
    {
        $notIterable = 'fubar';
        $this->shouldThrow(\LaraPackage\Api\Exceptions\InvalidArgumentException::class)
            ->during('format', [$notIterable]);
    }

    function it_formats_an_array_into_json()
    {
        $array = ['foo' => 'bar'];
        $expected = json_encode($array);

        $this->format($array)->shouldReturn($expected);
    }

    function it_formats_an_iterator_aggregate_into_json()
    {
        $array = ['foo' => 'bar'];
        $iteratorAggregate = new IteratorAggregateStub($array);
        $expected = json_encode($array);

        $this->format($iteratorAggregate)->shouldReturn($expected);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\v4\MediaType\Json');
    }
}

class IteratorAggregateStub implements \IteratorAggregate
{

    /**
     * @var array
     */
    private $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }
}
