<?php

namespace spec\LaraPackage\Api\Request;

use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PayloadSpec extends ObjectBehavior
{
    function let(Request $request)
    {
        $this->beConstructedWith($request);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\Request\Payload');
    }

    function it_retrieves_the_payload(Request $request)
    {

        $array = ['test'];
        $request->getContent()->shouldBeCalled()->willReturn($this->json($array));
        $this->getIterator()->shouldReturnAnInstanceOf(\ArrayIterator::class);
    }

    protected function json(array $array)
    {
        return json_encode($array, true);
    }

    function it_throws_if_the_payload_cannot_be_parsed(Request $request)
    {
        $request->getContent()->shouldBeCalled()->willReturn($this->gibberish());
        $this->shouldThrow(\LaraPackage\Api\Exceptions\RequestException::class)->during('getIterator');
    }

    protected function gibberish()
    {
        return 'alawejjafsadoif asfdlkjsadf asdlfjk asdf;lkjsadflkj sadfdfsdsf';
    }
}
