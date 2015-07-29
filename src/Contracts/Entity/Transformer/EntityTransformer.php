<?php
namespace LaraPackage\Api\Contracts\Entity\Transformer;

use LaraPackage\Api\Contracts;

interface EntityTransformer
{
    /**
     * returns an array of links for the entity
     *
     * @param Contracts\Entity\Entity $entity
     *
     * @return array
     */
    public function links(Contracts\Entity\Entity $entity);

    /**
     * returns an array of the public transformations
     *
     * @param Contracts\Entity\Entity $entity
     *
     * @return array
     */
    public function publicTransformations(Contracts\Entity\Entity $entity);
}
