<?php
namespace LaraPackage\Api\Contracts\Validation;

interface Transform
{
    /**
     * Takes an array with private keys and transforms it to public keys
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
     * @param array $array
     *
     * @return array
     */
    public function forwardTransformAttributeKeyNames(array $array);

    /**
     * Returns an array of public keys
     *
     * @return array
     */
    public function frontEndAttributeNames();
}
