<?php
namespace LaraPackage\Api\Contracts\Repository\Helper;

use LaraPackage\Api\Exceptions\StupidProgrammerMistakeException;

interface Relational
{
    /**
     * @param $collection
     *
     * [
     *      ['image_id' => 1],
     *      ['image_id' => 2],
     * ]
     *
     * @return array
     *
     * [
     *      ['image_id' => 1, 'product_id' => 78],
     *      ['image_id' => 2, 'product_id' => 78],
     * ]
     */
    public function addRelationalIdsToEachItemInCollection($collection);

    /**
     * @param array $item ['image_id' => 6]
     *
     * @return array ['image_id' => 6, 'site_id' => 7, 'product_id' => 5]
     */
    public function addRelationalIdsToItem(array $item);

    /**
     * @param array $collection
     *
     * @return array
     */
    public function addTimestampsToEachItemInCollection(array $collection);

    /**
     * @param array $item
     *
     * @return array
     */
    public function addTimestampsToItem(array $item);

    /**
     * @return array
     */
    public function columns();

    /**
     * @return array
     */
    public function ids();

    /**
     * @return string e.g. 'site_id'
     */
    public function itemColumn();

    /**
     * @return array [2,6,5]
     */
    public function itemIds();

    /**
     * @return string e.g. 'site'
     */
    public function itemName();

    /**
     * @return array ['site_id' => 1, 'product_id' => 2]
     * @throws StupidProgrammerMistakeException
     */
    public function relationIds();

    /**
     * @return string
     */
    public function table();
}
