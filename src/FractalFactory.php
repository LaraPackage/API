<?php
namespace LaraPackage\Api;

use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class FractalFactory
{

    /**
     * @param mixed                                         $data
     * @param \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer
     * @param null|string                                   $resourceKey
     *
     * @return Collection
     */
    public function createCollection($data, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer, $resourceKey = null)
    {
        return new Collection($data, $transformer, $resourceKey);
    }

    /**
     * @param int $current
     * @param int $previous
     * @param int $next
     * @param int $count
     *
     * @return Cursor
     */
    public function createCursor($current, $previous, $next, $count)
    {
        return new Cursor($current, $previous, $next, $count);
    }

    /**
     * @param mixed                                         $data
     * @param \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer
     * @param null|string                                   $resourceKey
     *
     * @return Item
     */
    public function createEntity($data, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer, $resourceKey = null)
    {
        return new Item($data, $transformer, $resourceKey);
    }

    public function createManager()
    {
        return new Manager();
    }
}
