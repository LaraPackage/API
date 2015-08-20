<?php

namespace spec\LaraPackage\Api\Request;

use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AcceptHeaderSpec extends ObjectBehavior
{
    function it_gets_the_media_type_from_the_accept_header(Request $request, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->requestHeaderExpectation($request, 'application/vnd.wps_api.v4+json');
        $version->isValid(4)->shouldBeCalledTimes(1)->willReturn(true);
        $version->mediaTypeIsValid('json', 4)->shouldBeCalledTimes(1)->willReturn(true);

        $this->acceptedMediaType()->shouldReturn('json');
    }

    function it_gets_the_media_type_from_the_accept_header_if_vendor_is_not_specified(Request $request, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->requestHeaderExpectation($request, 'application/json');
        $version->latest()->shouldBeCalledTimes(2)->willReturn(4);
        $version->isValid(4)->shouldBeCalledTimes(2)->willReturn(true);

        $version->mediaTypeIsValid(false, 4)->shouldBeCalledTimes(1)->willReturn(false);
        $version->mediaTypeIsValid('json', 4)->shouldBeCalledTimes(1)->willReturn(true);

        $this->acceptedMediaType()->shouldReturn('json');
    }

    function it_gets_the_version_from_the_accept_header(Request $request, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->requestHeaderExpectation($request, 'application/vnd.wps_api.v4+json');
        $version->isValid(4)->shouldBeCalledTimes(1)->willReturn(true);

        $this->version()->shouldReturn(4);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\Request\AcceptHeader');
    }

    function it_returns_the_default_if_content_type_is_not_specified(Request $request, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->requestHeaderExpectation($request, '');
        $version->latest()->shouldBeCalledTimes(3)->willReturn(4);
        $version->isValid(4)->shouldBeCalledTimes(3)->willReturn(true);
        $version->mediaTypeIsValid(false, 4)->shouldBeCalledTimes(2)->willReturn(false);
        $version->defaultMediaType(4)->shouldBeCalledTimes(1)->willReturn('json');

        $this->acceptedMediaType()->shouldReturn('json');
    }

    function it_returns_the_latest_version_if_no_version_is_requested(Request $request, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->requestHeaderExpectation($request, 'application/json');
        $version->latest()->shouldBeCalledTimes(1)->willReturn(4);
        $version->isValid(4)->shouldBeCalledTimes(1)->willReturn(true);

        $this->version()->shouldReturn(4);
    }

    function it_returns_the_latest_version_if_the_requested_version_is_invalid(Request $request, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->requestHeaderExpectation($request, 'application/vnd.wps_api.v500012+json');
        $version->isValid(500012)->shouldBeCalledTimes(1)->willReturn(false);
        $version->latest()->shouldBeCalledTimes(1)->willReturn(4);

        $this->version()->shouldReturn(4);
    }

    function let(Request $request, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->beConstructedWith($request, $version);
    }

    /**
     * @param Request $request
     * @param         $willReturn
     */
    protected function requestHeaderExpectation(Request $request, $willReturn)
    {
        $request->server('HTTP_ACCEPT', '')->shouldBeCalled()->willReturn($willReturn);
    }
}
