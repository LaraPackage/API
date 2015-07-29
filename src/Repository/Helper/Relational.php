<?php
namespace LaraPackage\Api\Repository\Helper;

use Carbon\Carbon;
use Illuminate\Http\Request;
use LaraPackage\Api\Exceptions\StupidProgrammerMistakeException;
use LaraPackage\RandomId\TableHelper;
use PrometheusApi\Utilities\Contracts\Uri\Parser;

class Relational implements \LaraPackage\Api\Contracts\Repository\Helper\Relational
{
    /**
     * @var Request
     */

    private $request;
    /**
     * @var Parser
     */

    private $parser;

    /**
     * @var TableHelper
     */
    private $tableHelper;

    /**
     * @param Request                           $request
     * @param Parser                            $parser
     * @param \LaraPackage\RandomId\TableHelper $tableHelper
     */
    public function __construct(
        Request $request,
        Parser $parser,
        TableHelper $tableHelper
    ) {
        $this->request = $request;
        $this->parser = $parser;
        $this->tableHelper = $tableHelper;
    }

    /**
     * @inheritdoc
     */
    public function addRelationalIdsToEachItemInCollection($collection)
    {
        $relationIds = $this->relationIds();

        return $this->addToEachItemOfCollection($relationIds, $collection);
    }

    /**
     * @inheritdoc
     */
    public function addRelationalIdsToItem(array $item)
    {
        $relationIds = $this->relationIds();

        return $this->addToItem($relationIds, $item);
    }

    /**
     * @inheritdoc
     */
    public function addTimestampsToEachItemInCollection(array $collection)
    {
        return $this->addToEachItemOfCollection($this->timestamps(), $collection);
    }

    /**
     * @inheritdoc
     */
    public function addTimestampsToItem(array $item)
    {
        return $this->addToItem($this->timestamps(), $item);
    }

    /**
     * @inheritdoc
     */
    public function columns()
    {
        return $this->tableHelper->getIdColumnNames($this->entities(), $this->idEntities());
    }

    /**
     * @inheritdoc
     */
    public function ids()
    {
        return $this->parser->ids($this->uri());
    }

    /**
     * @inheritdoc
     */
    public function itemColumn()
    {
        $this->isAnItemResource();

        return $this->tableHelper->getLastEntityAsIdColumnName($this->entities());
    }

    /**
     * @inheritdoc
     */
    public function itemIds()
    {
        $ids = $this->ids();

        return (array)array_pop($ids);
    }

    /**
     * @inheritdoc
     */
    public function itemName()
    {
        $entities = $this->entities();

        return $this->tableHelper->singularize(array_pop($entities));
    }

    /**
     * @inheritdoc
     */
    public function relationIds()
    {
        $entities = $this->entities();
        $idEntities = $this->idEntities();
        $ids = $this->ids();
        $columnNames = $this->tableHelper->getIdColumnNames($entities, $idEntities);

        if (count($entities) === count($idEntities)) {
            array_pop($ids);
            array_pop($columnNames);

            return array_combine($columnNames, $ids);
        } elseif (count($entities) > count($idEntities)) {
            return array_combine($columnNames, $ids);
        }

        throw new StupidProgrammerMistakeException('the entities identities count is off');
    }

    /**
     * @inheritdoc
     */
    public function table()
    {
        return $this->tableHelper->getTable($this->entities());
    }

    /**
     * @param array $add
     * @param array $collection
     *
     * @return array
     */
    protected function addToEachItemOfCollection(array $add, array $collection)
    {
        return array_map(function ($item) use ($add) {
            return $this->addToItem($add, $item);
        }, $collection);
    }

    /**
     * @param array $add
     *
     * @param array $item
     *
     * @return array
     */
    protected function addToItem(array $add, array $item)
    {
        return array_merge($add, $item);
    }

    /**
     * @return array
     */
    protected function entities()
    {
        return $this->parser->entities($this->uri());
    }

    /**
     * @return array
     */
    protected function idEntities()
    {
        return $this->parser->idEntities($this->uri());
    }

    /**
     * @throws StupidProgrammerMistakeException
     */
    protected function isACollectionResource()
    {
        if (!(count($this->entities()) > count($this->idEntities()))) {
            throw new StupidProgrammerMistakeException('this is not a collection resource');
        }
    }

    /**
     * @throws StupidProgrammerMistakeException
     */
    protected function isAnItemResource()
    {
        if (count($this->entities()) > count($this->idEntities())) {
            throw new StupidProgrammerMistakeException('this is not an item resource');
        }
    }

    /**
     * @return array
     */
    protected function timestamps()
    {
        $timestamps = [];
        $timestamps['created_at'] = Carbon::now();
        $timestamps['updated_at'] = Carbon::now();

        return $timestamps;
    }

    /**
     * @return string
     */
    protected function uri()
    {
        return $this->request->getRequestUri();
    }
}
