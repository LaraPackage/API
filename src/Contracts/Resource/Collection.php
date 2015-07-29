<?php
namespace LaraPackage\Api\Contracts\Resource;

interface Collection extends Type
{
    /**
     * Returns the count of the current chunk
     *
     * @return int
     */
    public function getCount();

    /**
     * Returns the current cursor position
     *
     * @return int
     */
    public function getCurrent();

    /**
     * @return \ArrayIterator
     */
    public function getData();

    /**
     * Returns the next cursor position
     *
     * @return int
     */
    public function getNext();

    /**
     * Returns the page size
     *
     * @return int
     */
    public function getPageSize();

    /**
     * returns the previous cursor position
     *
     * @return int
     */
    public function getPrevious();
}
