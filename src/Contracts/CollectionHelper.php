<?php
namespace LaraPackage\Api\Contracts;

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
}
