<?php
namespace LaraPackage\Api\Config;

class ApiVersion implements \LaraPackage\Api\Contracts\Config\ApiVersion
{
    /**
     * @var \LaraPackage\Api\Contracts\Config\Api
     */
    protected $config;

    /**
     * @param \LaraPackage\Api\Contracts\Config\Api $config
     */
    public function __construct(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function availableVersions()
    {
        return \array_keys($this->config->getIndex('versions'));
    }

    /**
     * Returns the query parameter that is to be used to denote the current position in a collection
     *
     * @param int $version
     *
     * @return string
     */
    public function collectionCurrentPositionString($version)
    {
        return $this->config->getIndexForVersion('collection.current_position', $version);
    }

    /**
     * The collection page size for the version
     *
     * @param int $version
     *
     * @return int
     */
    public function collectionPageSize($version)
    {
        return (int)$this->config->getIndexForVersion('collection.page_size', $version);
    }

    /**
     * @param int $version
     *
     * @return string
     */
    public function defaultMediaType($version)
    {
        return $this->config->getIndexForVersion('media.default', $version);
    }

    /**
     * Checks to see if the specified version is valid
     *
     * @param int $version
     *
     * @return bool
     */
    public function isValid($version)
    {
        return \in_array($version, $this->availableVersions());
    }

    /**
     * Gets the latest version
     *
     * @return int
     */
    public function latest()
    {
        return (int)\max(\array_keys($this->config->getIndex('versions')));
    }

    /**
     * Checks if a media type is valid for a version
     *
     * @param string $mediaType
     * @param int    $version
     *
     * @return bool
     */
    public function mediaTypeIsValid($mediaType, $version)
    {
        return \in_array($mediaType, $this->mediaTypes($version));
    }

    /**
     * Returns an array of media types for a version
     *
     * @param int $version
     *
     * @return array
     */
    public function mediaTypes($version)
    {
        return $this->config->getIndexForVersion('media.types', $version);
    }

    /**
     * Returns an array of ids for a resource
     * or false if the resource is not found
     *
     * This is used for testing to retrieve random ids manually if the automatic way
     * doesn't work.
     *
     * @param string $resource
     * @param int    $version
     *
     * @return array|false
     */
    public function resourceIdMap($resource, $version)
    {
        $idMap = $this->config->getIndexForVersion('resourceIdsMap', $version);
        if (array_key_exists($resource, $idMap)) {
            return $idMap[$resource]();
        }

        return false;
    }

    /**
     * Returns the vendor string for the version
     *
     * @param int $version
     *
     * @return string
     */
    public function vendor($version)
    {
        return $this->config->getIndexForVersion('vendor', $version);
    }

    /**
     * Returns the version designator for all versions
     *
     * @param int $version
     *
     * @return string
     */
    public function versionDesignator($version)
    {
        return $this->config->getIndex('version_designator').$version;
    }
}
