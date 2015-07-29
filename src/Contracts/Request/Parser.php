<?php
namespace LaraPackage\Api\Contracts\Request;

interface Parser
{
    /**
     * The media type that the client would like to receive in response
     *
     * @return string
     */
    public function acceptedMediaType();

    /**
     * Get an item from the headers
     *
     * @param string $item
     *
     * @return string
     */
    public function header($item);

    /**
     * Check if an entity was requested to be included
     *
     * @param string $entity
     *
     * @return bool
     */
    public function inIncludes($entity);

    /**
     * Get all requested includes
     *
     * @return string
     */
    public function includes();

    /**
     * Get an item from the query
     *
     * @param string $item
     *
     * @return string
     */
    public function query($item);

    /**
     * The request's content type
     *
     * @return string
     */
    public function requestedMediaType();

    /**
     * Returns an int of the latest version or the requested one.
     *
     * @return int
     */
    public function version();
}
