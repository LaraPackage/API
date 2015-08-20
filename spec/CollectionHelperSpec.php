<?php

namespace spec\LaraPackage\Api;

use LaraPackage\Api\Contracts\Config\ApiVersion;
use LaraPackage\Api\Contracts\Entity\Transformer\Transformer;
use LaraPackage\Api\Contracts\Request\Parser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionHelperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\CollectionHelper');
    }

    function let(Parser $parser, ApiVersion $apiVersion)
    {
        $this->beConstructedWith($parser, $apiVersion);
    }

    function it_returns_the_query(Parser $parser, Transformer $transformer)
    {
        $query = ['foo' => 'bar', 'baz' => 'boo'];
        $transformed = ['foofoo' => 'bar', 'bazbaz' => 'boo'];
        $parser->query()->shouldBeCalled()->willReturn($query);
        $transformer->reverseTransform($query)->shouldBeCalled()->willReturn($transformed);

        $this->query($transformer)->shouldReturn($transformed);
    }

    function it_returns_the_page_size(Parser $parser, ApiVersion $apiVersion)
    {
        $version = 4;
        $expectedPageSize = 10;
        $parser->version()->shouldBeCalled()->willReturn($version);

        $apiVersion->collectionPageSize($version)->shouldBeCalled()->willReturn($expectedPageSize);

        $this->pageSize()->shouldReturn($expectedPageSize);
    }

    function it_returns_the_current_position(Parser $parser, ApiVersion $apiVersion)
    {
        $version = 4;
        $postionString = 'current';
        $currentPosition = 20;

        $parser->version()->shouldBeCalled()->willReturn($version);
        $apiVersion->collectionCurrentPositionString($version)->shouldBeCalled()->willReturn($postionString);
        $parser->query($postionString)->shouldBeCalled()->willReturn($currentPosition);

        $this->currentPosition()->shouldReturn(20);
    }

    function it_returns_the_current_position_even_if_it_is_null(Parser $parser, ApiVersion $apiVersion)
    {
        $version = 4;
        $postionString = 'current';

        $parser->version()->shouldBeCalled()->willReturn($version);
        $apiVersion->collectionCurrentPositionString($version)->shouldBeCalled()->willReturn($postionString);
        $parser->query($postionString)->shouldBeCalled()->willReturn(null);

        $this->currentPosition()->shouldReturn(0);
    }
}
