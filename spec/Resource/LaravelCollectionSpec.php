<?php

namespace spec\LaraPackage\Api\Resource;

use App\Contracts;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LaravelCollectionSpec extends ObjectBehavior
{
    function it_gets_the_array_of_data(Paginator $paginator)
    {
        $expected = new \ArrayIterator(['test']);
        $paginator->getIterator()->shouldBeCalledTimes(1)->willReturn($expected);
        $this->getData()->shouldReturn($expected);
    }

    function it_gets_the_count(Paginator $paginator)
    {
        $expected = 5;
        $this->countExpectations($paginator, $expected);
        $this->getCount()->shouldReturn($expected);
    }

    function it_gets_the_current_cursor_position(\LaraPackage\Api\Contracts\Config\Api $config, \LaraPackage\Api\Contracts\Request\Parser $requestParser)
    {
        $expectedReturn = 43;
        $this->currentExpectations($config, $requestParser, $expectedReturn);
        $this->getCurrent()->shouldReturn($expectedReturn);
    }

    function it_gets_the_next_position(Paginator $paginator, Collection $collection)
    {
        $next = 4;
        $model = new \stdClass();
        $model->id = $next;
        $paginator->getCollection()->shouldBeCalledTimes(1)->willReturn($collection);
        $collection->last()->shouldBeCalledTimes(1)->willReturn($model);
        $this->getNext()->shouldReturn($next);
    }

    function it_gets_the_page_size(Paginator $paginator)
    {
        $perPage = 10;
        $this->pageSizeExpectations($paginator, $perPage);
        $this->getPageSize()->shouldReturn($perPage);
    }

    function it_gets_the_previous_position(Paginator $paginator, \LaraPackage\Api\Contracts\Config\Api $config, \LaraPackage\Api\Contracts\Request\Parser $requestParser)
    {
        $current = 10;
        $count = 5;

        $this->currentExpectations($config, $requestParser, $current);
        $this->countExpectations($paginator, $count);

        $this->getPrevious()->shouldReturn(($current - $count));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\Resource\LaravelCollection');
    }

    function it_return_0_for_the_previous_if_current_minus_count_is_equal_or_less(Paginator $paginator, \LaraPackage\Api\Contracts\Config\Api $config, \LaraPackage\Api\Contracts\Request\Parser $requestParser)
    {
        $current = 3;
        $count = 4;

        $this->currentExpectations($config, $requestParser, $current);
        $this->countExpectations($paginator, $count);

        $this->getPrevious()->shouldReturn(0);
    }

    function it_returns_0_for_current_if_it_is_empty_in_the_query(\LaraPackage\Api\Contracts\Config\Api $config, \LaraPackage\Api\Contracts\Request\Parser $requestParser)
    {
        $requestParserReturn = null;
        $expectedReturn = 0;
        $this->currentExpectations($config, $requestParser, $requestParserReturn);
        $this->getCurrent()->shouldReturn($expectedReturn);
    }

    function let(Paginator $paginator, \LaraPackage\Api\Contracts\Config\Api $config, \LaraPackage\Api\Contracts\Request\Parser $requestParser)
    {
        $this->beConstructedWith($paginator, $config, $requestParser);
    }

    /**
     * @param Paginator $paginator
     * @param           $paginatorCountReturn
     */
    protected function countExpectations(Paginator $paginator, $paginatorCountReturn)
    {
        $paginator->count()->shouldBeCalled()->willReturn($paginatorCountReturn);
    }

    /**
     * @param \LaraPackage\Api\Contracts\Config\Api $config
     * @param \LaraPackage\Api\Contracts\Request\Parser         $requestParser
     * @param                                  $requestParserReturn
     */
    protected function currentExpectations(\LaraPackage\Api\Contracts\Config\Api $config, \LaraPackage\Api\Contracts\Request\Parser $requestParser, $requestParserReturn)
    {
        $version = 4;
        $currentString = 'current';

        $requestParser->version()->shouldBeCalled()->willReturn($version);
        $config->getIndexForVersion('collection.current_position', $version)->shouldBeCalled()->willReturn($currentString);
        $requestParser->query($currentString)->shouldBeCalled()->willReturn($requestParserReturn);
    }

    /**
     * @param Paginator $paginator
     * @param           $perPage
     */
    protected function pageSizeExpectations(Paginator $paginator, $perPage)
    {
        $paginator->perPage()->shouldBeCalled()->willReturn($perPage);
    }
}
