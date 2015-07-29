<?php
namespace LaraPackage\Api\Contracts\Repository\Actions;

use LaraPackage\Api\Contracts\Event\Event;

interface REST
{
    /**
     * Returns a cursorized collection
     *
     * @param int  $currentPosition
     * @param int  $pageSize
     *
     * @param string|null $with
     *
     * @return \LaraPackage\Api\Contracts\Resource\Collection
     */
    public function collection($currentPosition, $pageSize, $with = null);

    /**
     * Deletes records using the specified ids
     *
     * @param array $ids
     *
     * @return int
     */
    public function delete(array $ids);

    /**
     * Deletes relations from pivot tables.
     *
     * You can pass all of the column names and ids using $relationIds[]
     * or just some of them to do a bulk WHERE delete.
     * You can also send the relational ids using $relationIds and
     * the target column name and ids using $itemColumn and $itemIds to delete multiple target ids.
     * An Event can also be triggered on deletion by passing it in through $event.
     *
     * @param string      $table
     * @param array       $relationIds An associative array containing the column name as the key and the id as the value
     * @param null|string $itemColumn  An optional target column name for deleting multiple ids using $itemIds
     * @param array       $itemIds     An Optional array of ids to delete using the target column name $itemColumn
     * @param Event|null  $event
     *
     * @return int The number of records deleted
     */
    public function deleteRelation($table, array $relationIds, $itemColumn = null, array $itemIds = [], Event $event = null);

    /**
     * Retrieve a single record
     *
     * @param int $id
     *
     * @return \LaraPackage\Api\Contracts\Resource\Entity
     */
    public function entity($id);

    /**
     * Partially update a single record
     *
     * @param int $id
     * @param array $data an associative array of fields that map to the model's fields
     *
     * @return \LaraPackage\Api\Contracts\Resource\Entity
     */
    public function patch($id, array $data);

    /**
     * Create a new record
     *
     * @param array $data An associative array of fields that map to the model's fields
     *
     * @return \LaraPackage\Api\Contracts\Resource\Entity
     */
    public function post(array $data);

    /**
     * Create a record in a pivot table
     *
     * @param string $table
     * @param array  $collection
     *
     * ['image_id' => 5, 'product_id' => 7, 'site_id' => 10]
     *
     * @return bool
     */
    public function postRelations($table, array $collection);

    /**
     * Replace a record
     *
     * @param int   $id
     * @param array $data
     *
     * @return \LaraPackage\Api\Contracts\Resource\Entity
     */
    public function put($id, array $data);

    /**
     * Deletes then replaces the collection
     *
     * @param string $table
     * @param array  $collection
     *
     * [
     *   ['image_id' => 5, 'product_id' => 7, 'site_id' => 10],
     *   ['image_id' => 7, 'product_id' => 7, 'site_id' => 10],
     * ]
     *
     * @return bool
     */
    public function putRelations($table, array $relationIds, array $collection);
}
