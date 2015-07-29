<?php
namespace LaraPackage\Api\Contracts\Entity\Transformer;

interface ReverseTransformer
{
    /**
     * returns an array of private => public key mappings
     *
     * [ privateKey => publicKey ]
     *
     * @return array
     */
    public function mappings();

    /**
     * Transforms an array from the public api to the private api
     *
     * e.g.
     * [
     *     'public_api' => 1
     * ]
     *
     * transforms to
     *
     * [
     *      'private_api' => 1
     * ]
     *
     * This uses mappings()
     *
     * @param array|\Traversable $iterable
     *
     * @return array
     */
    public function reverseTransform($iterable);
}
