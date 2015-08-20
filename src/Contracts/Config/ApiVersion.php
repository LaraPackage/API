<?php
namespace LaraPackage\Api\Contracts\Config;

interface ApiVersion
{
    /**
     * @return array
     */
    public function availableVersions();

    /**
     * Returns the query parameter that is to be used to denote the current position in a collection
     *
     * @param int $version
     *
     * @return string
     */
    public function collectionCurrentPositionString($version);

    /**
     * The collection page size for the version
     *
     * @param int $version
     *
     * @return int
     */
    public function collectionPageSize($version);

    /**
     * @param string $version
     *
     * @return string
     */
    public function defaultMediaType($version);

    /**
     * Checks to see if the specified version is valid
     *
     * @param int $version
     *
     * @return bool
     */
    public function isValid($version);

    /**
     * @return int
     */
    public function latest();

    /**
     * Checks if a media type is valid for a version
     *
     * @param string $mediaType
     * @param int    $version
     *
     * @return bool
     */
    public function mediaTypeIsValid($mediaType, $version);

    /**
     * Returns an array of media types for a version
     *
     * @param int $version
     *
     * @return array
     */
    public function mediaTypes($version);

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
    public function resourceIdMap($resource, $version);

    /**
     * Returns the vendor string for the version
     *
     * @param int $version
     *
     * @return string
     */
    public function vendor($version);

    /**
     * Returns the version designator for all versions
     *
     * @param int $version
     *
     * @return string
     */
    public function versionDesignator($version);

    /**
     * @param $version
     *
     * @return \LaraPackage\Api\Contracts\Factory\VersionFactory
     */
    public function factory($version);
}
