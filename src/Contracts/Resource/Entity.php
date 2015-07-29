<?php
namespace LaraPackage\Api\Contracts\Resource;

interface Entity extends Type
{
    /**
     * Needs to return something that can be iterated
     *
     * @return \ArrayIterator
     */
    public function getData();

    /**
     * @return int
     */
    public function getId();
}
