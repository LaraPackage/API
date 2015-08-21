<?php

namespace spec\LaraPackage\Api\Factory;

use Illuminate\Contracts\Container\Container as App;
use LaraPackage\Api\Contracts\Config\ApiVersion;
use LaraPackage\Api\Contracts\Factory\VersionFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    protected $version = 4;

    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\Factory\Factory');
    }

    function it_makes_a_payload_creator_for_a_version(
        App $app,
        \LaraPackage\Api\Contracts\Request\Parser $requestParser,
        \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory,
        \LaraPackage\Api\Contracts\PayloadCreator $resourceCreator,
        ApiVersion $apiVersion
    ) {
        $this->versionFactoryExpectations($app, $requestParser, $versionFactory, $apiVersion);
        $versionFactory->makePayloadCreator()->shouldBeCalledTimes(1)->willReturn($resourceCreator);
        $this->makePayloadCreator()->shouldReturn($resourceCreator);
    }

    function it_makes_a_representation_creator(
        App $app,
        \LaraPackage\Api\Contracts\Request\Parser $requestParser,
        \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory,
        \LaraPackage\Api\Contracts\RepresentationCreator $responseCreator,
        ApiVersion $apiVersion
    ) {
        $this->versionFactoryExpectations($app, $requestParser, $versionFactory, $apiVersion);
        $versionFactory->makeRepresentationCreator()->shouldBeCalledTimes(1)->willReturn($responseCreator);
        $this->makeRepresentationCreator()->shouldReturn($responseCreator);
    }

    function let(App $app, \LaraPackage\Api\Contracts\Request\Parser $requestParser, ApiVersion $apiVersion)
    {
        $this->beConstructedWith($app, $requestParser, $apiVersion);
    }

    /**
     * @param App                                               $app
     * @param \LaraPackage\Api\Contracts\Request\Parser         $requestParser
     * @param \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory
     */
    protected function versionFactoryExpectations(
        App $app,
        \LaraPackage\Api\Contracts\Request\Parser $requestParser,
        \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory,
        ApiVersion $apiVersion
    ) {
        $requestParser->version()->shouldBeCalled()->willReturn($this->version);
        $apiVersion->factory($this->version)->shouldBeCalled()->willReturn(VersionFactory::class);
        $app->instance(\LaraPackage\Api\Contracts\Factory\VersionFactory::class, $versionFactory)->shouldBeCalled();
        $app->make(VersionFactory::class)->shouldBeCalledTimes(1)->willReturn($versionFactory);
    }

}
