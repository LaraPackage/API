<?php

namespace spec\LaraPackage\Api;

use Illuminate\Contracts\Routing\ResponseFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepresentationCreatorSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\Implementations\RepresentationCreator');
    }

    function it_makes_a_response(
        \LaraPackage\Api\Contracts\Request\Parser $requestParser,
        ResponseFactory $response,
        \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory,
        \LaraPackage\Api\Contracts\MediaType\Json $media,
        \Illuminate\Http\Response $illuminateResponse,
        \LaraPackage\Api\Contracts\Config\ApiVersion $versionInfoRetriever
    ) {
        $mediaType = 'json';
        $version = 4;
        $vendor = 'vnd.wps_api.';
        $versionDesignator = 'v4';
        $dataArray = ['data' => 'array'];
        $jsonData = json_encode($dataArray);

        $requestParser->acceptedMediaType()->shouldBeCalled()->willReturn($mediaType);

        $versionInfoRetriever->vendor($version)->shouldBeCalled()->willReturn($vendor);
        $versionInfoRetriever->versionDesignator($version)->shouldBeCalled()->willReturn($versionDesignator);

        $versionFactory->makeMediaType($mediaType)->shouldBeCalled()->willReturn($media);

        $media->format($dataArray)->shouldBeCalled()->willReturn($jsonData);

        $response->make($jsonData, 200, ['Content-Type' => 'application/'.$vendor.$versionDesignator.'+'.$mediaType])->shouldBeCalled()->willReturn($illuminateResponse);

        $this->make($dataArray)->shouldReturn($illuminateResponse);
    }

    function let(
        \LaraPackage\Api\Contracts\Request\Parser $requestParser,
        ResponseFactory $response,
        \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory,
        \LaraPackage\Api\Contracts\Config\ApiVersion $versionInfoRetriever
    ) {
        $this->beConstructedWith($requestParser, $response, $versionFactory, $versionInfoRetriever);
    }
}
