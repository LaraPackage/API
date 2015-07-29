<?php

namespace spec\LaraPackage\Api\Request;

use App\Contracts;
use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;

class ParserSpec extends ObjectBehavior
{
    function let(Request $request, \LaraPackage\Api\Contracts\Request\AcceptHeader $acceptHeaderParser)
    {
        $this->beConstructedWith($request, $acceptHeaderParser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Request\Parser');
    }

    function it_gets_the_version_from_the_accept_header(\LaraPackage\Api\Contracts\Request\AcceptHeader $acceptHeaderParser)
    {
        $acceptHeaderParser->version()->shouldBeCalledTimes(1)->willReturn(4);
        $this->version()->shouldReturn(4);
    }

    function it_gets_the_media_type_from_the_accept_header(\LaraPackage\Api\Contracts\Request\AcceptHeader $acceptHeaderParser)
    {
        $acceptHeaderParser->acceptedMediaType()->shouldBeCalledTimes(1)->willReturn('json');
        $this->acceptedMediaType()->shouldReturn('json');
    }

    function it_gets_the_includes_from_the_request(Request $request, ParameterBag $parameterBag)
    {
        $includes = 'product,image';

        $request->query = $parameterBag;
        $parameterBag->get('include')->shouldBeCalledTimes(1)->willReturn($includes);
        $this->includes()->shouldReturn($includes);
    }

    function it_checks_if_an_item_is_in_the_includes(Request $request, ParameterBag $parameterBag)
    {
        $includes = 'product,image';
        $entity = 'image';

        $request->query = $parameterBag;
        $parameterBag->get('include')->shouldBeCalledTimes(1)->willReturn($includes);
        $this->inIncludes($entity)->shouldReturn(true);
    }

    function it_gets_an_item_from_the_query(Request $request, ParameterBag $parameterBag)
    {
        $item = 'foo';
        $return = 'bar';

        $request->query = $parameterBag;
        $parameterBag->get($item)->shouldBeCalled(1)->willReturn($return);

        $this->query($item)->shouldReturn($return);
    }

    function it_gets_an_item_from_the_header(Request $request, HeaderBag $headerBag)
    {
        $item = 'foo';
        $return = 'bar';

        $request->headers = $headerBag;
        $headerBag->get($item)->shouldBeCalled(1)->willReturn($return);

        $this->header($item)->shouldReturn($return);
    }
}
