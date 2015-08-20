<?php

namespace spec\LaraPackage\Api\Repository\Helper;

use PrometheusApi\Utilities\Contracts\Uri\Parser;
use LaraPackage\RandomId\TableHelper;
use LaraPackage\Api\Exceptions\StupidProgrammerMistakeException;
use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RelationalSpec extends ObjectBehavior
{
    public function getMatchers()
    {
        return [
            'haveKeysInArray'           => function (array $array, array $keys) {
                return $this->keysAreInArray($keys, $array);
            },
            'haveKeysInCollectionItems' => function (array $collection, array $keys) {
                $result = [];
                foreach ($collection as $item) {
                    $result[] = $this->keysAreInArray($keys, $item);
                }

                return !in_array(false, $result, true);
            },
        ];
    }

    function it_adds_relational_ids_to_a_collection(
        Request $request,
        Parser $parser,
        TableHelper $tableHelper
    ) {
        $uri = '/sites/1/products/2/images';
        $entities = ['sites', 'products', 'images'];
        $idEntities = ['sites', 'products'];
        $ids = [1, 2];
        $idEntityColumnNames = ['site_id', 'product_id'];
        $collection = [['item_id' => 1], ['item_id' => 2]];
        $resultingCollection = [
            ['site_id' => 1, 'product_id' => 2, 'item_id' => 1],
            ['site_id' => 1, 'product_id' => 2, 'item_id' => 2],
        ];

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);

        $parser->entities($uri)->shouldBeCalled()->willReturn($entities);
        $parser->idEntities($uri)->shouldBeCalled()->willReturn($idEntities);
        $parser->ids($uri)->shouldBeCalled()->willReturn($ids);

        $tableHelper->getIdColumnNames($entities, $idEntities)->willReturn($idEntityColumnNames);

        $this->addRelationalIdsToEachItemInCollection($collection)->shouldReturn($resultingCollection);
    }

    function it_adds_relational_ids_to_an_item(
        Request $request,
        Parser $parser,
        TableHelper $tableHelper
    ) {
        $uri = '/sites/1/products/2/images/1';
        $entities = ['sites', 'products', 'images'];
        $idEntities = ['sites', 'products', 'images'];
        $ids = [1, 2, 1];
        $idEntityColumnNames = ['site_id', 'product_id', 'image_id'];
        $item = ['item_id' => 1];
        $resultingItem = ['site_id' => 1, 'product_id' => 2, 'item_id' => 1];

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);

        $parser->entities($uri)->shouldBeCalled()->willReturn($entities);
        $parser->idEntities($uri)->shouldBeCalled()->willReturn($idEntities);
        $parser->ids($uri)->shouldBeCalled()->willReturn($ids);

        $tableHelper->getIdColumnNames($entities, $idEntities)->shouldBeCalled()->willReturn($idEntityColumnNames);

        $this->addRelationalIdsToItem($item)->shouldReturn($resultingItem);
    }

    function it_adds_timestamps_to_a_collection()
    {
        $collection = [['test' => 5], ['y' => 7]];
        $this->addTimestampsToEachItemInCollection($collection)->shouldHaveKeysInCollectionItems(['created_at', 'updated_at']);
    }

    function it_adds_timestamps_to_an_item()
    {
        $item = ['test' => 5];
        $this->addTimestampsToItem($item)->shouldHaveKeysInArray(['created_at', 'updated_at']);
    }

    function it_blows_up_if_the_last_entity_is_not_an_id(
        Request $request,
        Parser $parser
    ) {
        $uri = '/sites/1/products/2/images';
        $entities = ['sites', 'products', 'images'];
        $idEntities = ['sites', 'products'];

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);
        $parser->entities($uri)->shouldBeCalled()->willReturn($entities);
        $parser->idEntities($uri)->shouldBeCalled()->willReturn($idEntities);

        $this->shouldThrow(StupidProgrammerMistakeException::class)->during('itemColumn');
    }

    function it_gets_a_table_name(Request $request, Parser $parser, TableHelper $tableHelper)
    {
        $uri = '/sites/1/products/2/images/1,2,3';
        $entities = ['sites', 'products', 'images'];
        $table = 'image_product_site';

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);

        $parser->entities($uri)->shouldBeCalled()->willReturn($entities);
        $tableHelper->getTable($entities)->shouldBeCalled()->willReturn($table);

        $this->table()->shouldReturn($table);
    }

    function it_gets_relation_ids_from_a_collection_resource(
        Request $request,
        Parser $parser,
        TableHelper $tableHelper
    ) {
        $uri = '/sites/1/products/2/images';
        $entities = ['sites', 'products', 'images'];
        $idEntities = ['sites', 'products'];
        $ids = [1, 2];
        $result = ['site_id' => 1, 'product_id' => 2];

        $idEntityColumnNames = ['site_id', 'product_id'];

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);

        $parser->entities($uri)->shouldBeCalled()->willReturn($entities);
        $parser->idEntities($uri)->shouldBeCalled()->willReturn($idEntities);
        $parser->ids($uri)->shouldBeCalled()->willReturn($ids);

        $tableHelper->getIdColumnNames($entities, $idEntities)->willReturn($idEntityColumnNames);

        $this->relationIds()->shouldReturn($result);
    }

    function it_gets_relation_ids_from_an_item_resource(
        Request $request,
        Parser $parser,
        TableHelper $tableHelper
    ) {
        $uri = '/sites/1/products/2/images/1,4,5';
        $entities = ['sites', 'products', 'images'];
        $idEntities = ['sites', 'products', 'images'];
        $ids = [1, 2, [1, 4, 5]];
        $result = ['site_id' => 1, 'product_id' => 2];
        $idEntityColumnNames = ['site_id', 'product_id', 'image_id'];

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);

        $parser->entities($uri)->shouldBeCalled()->willReturn($entities);
        $parser->idEntities($uri)->shouldBeCalled()->willReturn($idEntities);
        $parser->ids($uri)->shouldBeCalled()->willReturn($ids);

        $tableHelper->getIdColumnNames($entities, $idEntities)->willReturn($idEntityColumnNames);

        $this->relationIds()->shouldReturn($result);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LaraPackage\Api\Repository\Helper\Relational');
    }

    function it_returns_an_item_column(
        Request $request,
        Parser $parser,
        TableHelper $tableHelper
    ) {
        $uri = '/sites/1/products/2/images/1,2,4';
        $entities = ['sites', 'products', 'images'];
        $idEntities = ['sites', 'products', 'images'];
        $result = 'image_id';

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);
        $parser->entities($uri)->shouldBeCalled()->willReturn($entities);
        $parser->idEntities($uri)->shouldBeCalled()->willReturn($idEntities);

        $tableHelper->getLastEntityAsIdColumnName($entities)->willReturn($result);

        $this->itemColumn()->shouldReturn($result);
    }

    function it_returns_an_item_name(Request $request, Parser $parser, TableHelper $tableHelper)
    {
        $uri = '/sites/1/products/2/images/1,4,5';
        $entities = ['sites', 'products', 'images'];
        $rawItem = 'images';
        $expected = 'Image';

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);
        $parser->entities($uri)->shouldBeCalled()->willReturn($entities);
        $tableHelper->singularize($rawItem)->shouldBeCalled()->willReturn($expected);
        $this->itemName()->shouldReturn($expected);
    }

    function it_returns_item_ids(
        Request $request,
        Parser $parser
    ) {
        $uri = '/sites/1/products/2/images/1,4,5';
        $ids = [1, 2, [1, 4, 5]];
        $result = [1, 4, 5];

        $request->getRequestUri()->shouldBeCalled()->willReturn($uri);

        $parser->ids($uri)->shouldBeCalled()->willReturn($ids);

        $this->itemIds()->shouldReturn($result);
    }

    function let(Request $request, Parser $parser, TableHelper $tableHelper)
    {
        $this->beConstructedWith($request, $parser, $tableHelper);
    }

    protected function keysAreInArray(array $keys, array $array)
    {
        $result = [];
        foreach ($keys as $search) {
            $result[] = array_key_exists($search, $array);
        }

        return !in_array(false, $result, true);
    }
}
