<?php
namespace LaraPackage\Api\MediaType;

use LaraPackage\Api\Exceptions\InvalidArgumentException;

class Json implements \LaraPackage\Api\Contracts\MediaType\Json
{

    /**
     * @inheritdoc
     */
    public function format($data)
    {
        if (!is_array($data) AND !$data instanceof \Traversable) {
            throw new InvalidArgumentException('Data to be converted must be an iterable');
        }

        if ($data instanceof \Traversable) {
            return json_encode(iterator_to_array($data));
        }

        return json_encode($data);
    }
}
