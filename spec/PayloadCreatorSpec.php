<?php

namespace spec\LaraPackage\Api;

use App\Contracts;
use LaraPackage\Api\FractalFactory;
use League\Fractal;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PayloadCreatorSpec extends ObjectBehavior
{

    protected $transformer = 'site';
    protected $transformerParameters = ['test'];

    function it_blows_up_if_you_try_to_access_the_payload_without_building_a_resource_first()
    {
        $this->shouldThrow(\LaraPackage\Api\Exceptions\InvalidArgumentException::class)->during('getIterator');
    }

    function it_builds_a_cursor_resource(
        \LaraPackage\Api\Contracts\Resource\Collection $cursor,
        Fractal\Pagination\Cursor $fractalCursor,
        FractalFactory $fractalFactory,
        \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer,
        Fractal\Resource\Collection $fractalCollection,
        Fractal\Manager $manager,
        Fractal\Scope $scope
    ) {
        $expected = ['test'];
        $cursorData = new \ArrayIterator($expected);
        $current = 10;
        $previous = 5;
        $next = 15;
        $count = 5;

        $fractalFactory->createCollection($cursorData, $transformer)->shouldBeCalled()->willReturn($fractalCollection);
        $fractalFactory->createCursor($current, $previous, $next, $count)->shouldBeCalled()->willReturn($fractalCursor);

        $this->setPayloadAssertions($fractalFactory, $fractalCollection, $manager, $scope, $cursorData);

        $fractalCollection->setCursor($fractalCursor)->shouldBeCalled();

        $cursor->getData()->shouldBeCalled()->willReturn($cursorData);
        $cursor->getCurrent()->shouldBeCalled()->willReturn($current);
        $cursor->getPrevious()->shouldBeCalled()->willReturn($previous);
        $cursor->getNext()->shouldBeCalled()->willReturn($next);
        $cursor->getCount()->shouldBeCalled()->willReturn($count);

        $this->cursor($cursor, $transformer);
        $this->getIterator()->getArrayCopy()->shouldReturn($expected);
    }

    function it_builds_an_entity_resource(
        \LaraPackage\Api\Contracts\Resource\Entity $entity,
        FractalFactory $fractalFactory,
        \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory,
        \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer,
        Fractal\Resource\Item $item,
        Fractal\Manager $manager,
        Fractal\Scope $scope
    ) {
        $expected = ['test'];
        $entityData = new \ArrayIterator(['test']);

        $entity->getData()->shouldBeCalled()->willReturn($entityData);
        $fractalFactory->createEntity($entityData, $transformer)->shouldBeCalled()->willReturn($item);

        $this->setPayloadAssertions($fractalFactory, $item, $manager, $scope, $entityData);

        $this->entity($entity, $transformer);

        $this->getIterator()->getArrayCopy()->shouldReturn($expected);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\v4\PayloadCreator');
    }

    function it_returns_an_empty_collection_for_no_results(
        \LaraPackage\Api\Contracts\Resource\Collection $cursor,
        Fractal\Pagination\Cursor $fractalCursor,
        FractalFactory $fractalFactory,
        \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer,
        Fractal\Resource\Collection $fractalCollection,
        Fractal\Manager $manager,
        Fractal\Scope $scope
    )
    {
        $expected = [];
        $cursorData = new \ArrayIterator($expected);
        $current = 0;
        $previous = 0;
        $next = 0;
        $count = 0;

        $fractalFactory->createCollection($cursorData, $transformer)->shouldBeCalled()->willReturn($fractalCollection);
        $fractalFactory->createCursor($current, $previous, $next, $count)->shouldBeCalled()->willReturn($fractalCursor);

        $this->setPayloadAssertions($fractalFactory, $fractalCollection, $manager, $scope, $cursorData);

        $fractalCollection->setCursor($fractalCursor)->shouldBeCalled();

        $cursor->getData()->shouldBeCalled()->willReturn($cursorData);
        $cursor->getCurrent()->shouldBeCalled()->willReturn($current);
        $cursor->getPrevious()->shouldBeCalled()->willReturn($previous);
        $cursor->getNext()->shouldBeCalled()->willReturn($next);
        $cursor->getCount()->shouldBeCalled()->willReturn($count);

        $this->cursor($cursor, $transformer);
        $this->getIterator()->getArrayCopy()->shouldReturn($expected);
    }

    function let(\LaraPackage\Api\Contracts\Request\Parser $requestParser, FractalFactory $fractalFactory, \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory)
    {
        $this->beConstructedWith($requestParser, $fractalFactory, $versionFactory);
    }

    /**
     * @param FractalFactory                     $fractalFactory
     * @param Fractal\Resource\ResourceInterface $resource
     * @param Fractal\Manager                    $manager
     * @param Fractal\Scope                      $scope
     * @param \ArrayIterator                     $data
     */
    protected function setPayloadAssertions(
        FractalFactory $fractalFactory,
        Fractal\Resource\ResourceInterface $resource,
        Fractal\Manager $manager,
        Fractal\Scope $scope,
        \ArrayIterator $data
    ) {
        $fractalFactory->createManager()->shouldBeCalled()->willReturn($manager);
        $manager->createData($resource)->shouldBeCalled()->willReturn($scope);
        $scope->toArray()->shouldBeCalled()->willReturn($data->getArrayCopy());
    }
}
