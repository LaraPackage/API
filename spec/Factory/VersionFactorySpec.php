<?php

namespace spec\LaraPackage\Api\Factory;

use Illuminate\Contracts\Container\Container as App;
use LaraPackage\Api\Contracts\Request\Payload;
use LaraPackage\Api\Implementations\PayloadCreator;
use LaraPackage\Api\Implementations\RepresentationCreator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VersionFactorySpec extends ObjectBehavior
{
    function it_gets_the_request_payload(App $app, Payload $payload)
    {
        $app->make(\LaraPackage\Api\Request\Payload::class)->shouldBeCalled()->willReturn($payload);
        $this->getRequestPayload()->shouldReturn($payload);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\Factory\VersionFactory');
    }

    function it_makes_a_media_type()
    {
        $this->makeMediaType('json')->shouldReturnAnInstanceOf(\LaraPackage\Api\MediaType\Json::class);
    }

    function it_makes_a_payload_creator(App $app, PayloadCreator $payloadCreator)
    {
        $app->make(\LaraPackage\Api\Implementations\PayloadCreator::class)->shouldBeCalled()->willReturn($payloadCreator);
        $this->makePayloadCreator()->shouldReturn($payloadCreator);
    }

    function it_makes_a_representation_creator(App $app, RepresentationCreator $responseCreator)
    {
        $app->make(\LaraPackage\Api\Implementations\RepresentationCreator::class)->shouldBeCalled()->willReturn($responseCreator);
        $this->makeRepresentationCreator()->shouldReturn($responseCreator);
    }

    function let(App $app)
    {
        $this->beConstructedWith($app);
    }
}
