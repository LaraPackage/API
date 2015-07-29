<?php
namespace LaraPackage\Api;

class CollectionHelper implements \LaraPackage\Api\Contracts\CollectionHelper
{
    /**
     * @var \LaraPackage\Api\Contracts\Request\Parser
     */
    private $requestParser;

    /**
     * @var \LaraPackage\Api\Contracts\Config\ApiVersion
     */
    private $version;

    /**
     * @param \LaraPackage\Api\Contracts\Request\Parser              $requestParser
     * @param \LaraPackage\Api\Contracts\Config\ApiVersion           $version
     */
    public function __construct(\LaraPackage\Api\Contracts\Request\Parser $requestParser, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->requestParser = $requestParser;
        $this->version = $version;
    }

    /**
     * @inheritdoc
     */
    public static function addToArrayIfSet($outKey, $inKey, $inArray, &$outArray)
    {
        if (isset($inArray[$inKey])) {
            $outArray[$outKey] = $inArray[$inKey];
        }
    }

    /**
     * @inheritdoc
     */
    public static function isAssociative(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * @inheritdoc
     */
    public static function isCollection(array $array)
    {
        if (self::isAssociative($array)) {
            return false;
        }

        $arrayElementCount = count(array_filter($array, 'is_array'));
        if (count($array) === $arrayElementCount && !empty($array)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function currentPosition()
    {
        return (int)$this->requestParser->query($this->version->collectionCurrentPositionString($this->requestParser->version())) ?: 0;
    }

    /**
     * @inheritdoc
     */
    public function pageSize()
    {
        return $this->version->collectionPageSize($this->requestParser->version());
    }

}
