<?php
namespace LaraPackage\Api\Transformer;

use LaraPackage\Api\CollectionHelper;
use League\Fractal;

abstract class AbstractTransformer extends Fractal\TransformerAbstract implements \LaraPackage\Api\Contracts\Entity\Transformer\Transformer
{
    /**
     * /**
     * @inheritdoc
     */
    public function forwardTransform($iterable)
    {
        $iterable = $this->toArray($iterable);

        if (CollectionHelper::isCollection($iterable)) {

            return array_map(function ($array) {
                return $this->forward($this->mappings(), $array);
            }, $iterable);
        }

        return $this->forward($this->mappings(), $iterable);
    }


    /**
     * @inheritdoc
     */
    public function reverseTransform($iterable)
    {
        $iterable = $this->toArray($iterable);

        if (CollectionHelper::isCollection($iterable)) {

            return array_map(function ($array) {
                return $this->reverse($this->mappings(), $array);
            }, $iterable);
        }

        return $this->reverse($this->mappings(), $iterable);
    }

    /**
     * @param string $outKey
     * @param string $inKey
     * @param array  $inArray
     * @param array  $outArray
     */
    protected function addElementToArrayIfSet($outKey, $inKey, $inArray, &$outArray)
    {
        CollectionHelper::addToArrayIfSet($outKey, $inKey, $inArray, $outArray);
    }

    protected function forward($mappings, $array)
    {
        $outArray = [];

        foreach ($mappings as $privateKey => $publicKey) {
            $this->addElementToArrayIfSet($publicKey, $privateKey, $array, $outArray);
        }

        return $outArray;
    }

    /**
     * @param string $value
     *
     * @return int|null
     */
    protected function intOrNull($value)
    {
        return $value ? (int)$value : null;
    }

    /**
     * @param array $mappings
     * @param array $array
     *
     * @return array
     */
    protected function reverse($mappings, $array)
    {
        $outArray = [];

        foreach ($mappings as $privateKey => $publicKey) {
            $this->addElementToArrayIfSet($privateKey, $publicKey, $array, $outArray);
        }

        return $outArray;
    }

    /**
     * @param $iterable
     *
     * @return array
     */
    protected function toArray($iterable)
    {
        if ($iterable instanceof \Traversable) {
            $iterable = iterator_to_array($iterable);

            return $iterable;
        }

        return $iterable;
    }
}
