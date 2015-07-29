<?php
namespace LaraPackage\Api\Contracts\Request;

interface Payload extends \IteratorAggregate
{
    /**
     * Returns the payload content
     *
     * @return \ArrayIterator
     * @throws \LaraPackage\Api\Exceptions\RequestException
     */
    public function getIterator();
}
