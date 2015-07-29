<?php
namespace LaraPackage\Api\Contracts;

use IteratorAggregate;

interface PayloadCreator extends IteratorAggregate
{
    /**
     * Returns a cursorized collection
     *
     * @param \LaraPackage\Api\Contracts\Resource\Collection $cursor
     * @param \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer
     *
     * @return void
     */
    public function cursor(\LaraPackage\Api\Contracts\Resource\Collection $cursor, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer);

    /**
     * Returns a single entity
     *
     * @param \LaraPackage\Api\Contracts\Resource\Entity $entity
     * @param \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer
     *
     * @return void
     */
    public function entity(\LaraPackage\Api\Contracts\Resource\Entity $entity, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer);

    /**
     * @return \ArrayIterator
     */
    public function getIterator();
}
