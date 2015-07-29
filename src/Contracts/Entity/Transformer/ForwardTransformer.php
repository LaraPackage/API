<?php
namespace LaraPackage\Api\Contracts\Entity\Transformer;

interface ForwardTransformer
{
    /**
     * Transforms an array from the internal api to the public api
     *
     * e.g.
     * [
     *     'private_api' => 1
     * ]
     *
     * transforms to
     *
     * [
     *      'public_api' => 1
     * ]
     *
     * This uses mappings()
     *
     * @param array|\Traversable $iterable
     *
     * @return array
     */
    public function forwardTransform($iterable);
}
