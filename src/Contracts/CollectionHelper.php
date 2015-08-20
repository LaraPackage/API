<?php
namespace LaraPackage\Api\Contracts;

use LaraPackage\Api\Contracts\Entity\Transformer\Transformer;

interface CollectionHelper
{
    /**
     * @param string $outKey
     * @param string $inKey
     * @param array  $inArray
     * @param array  $outArray
     */
    public static function addToArrayIfSet($outKey, $inKey, $inArray, &$outArray);

    /**
     * @return int
     */
    public function currentPosition();

    /**
     * @param array $array
     *
     * @return bool
     */
    public static function isAssociative(array $array);

    /**
     * @param array $array
     *
     * @return bool
     */
    public static function isCollection(array $array);

    /**
     * @return int
     */
    public function pageSize();

    /**
     * @param Transformer $transformer
     *
     * @return array
     */
    public function query(Transformer $transformer);
}
