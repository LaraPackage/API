<?php
namespace LaraPackage\Api\Contracts\Factory;

interface VersionFactory
{
    /**
     * @return \LaraPackage\Api\Contracts\Request\Payload
     */
    public function getRequestPayload();

    /**
     * Make a media type class for the current version
     *
     * @param string $mediaType
     *
     * @return \LaraPackage\Api\Contracts\MediaType\MediaType
     */
    public function makeMediaType($mediaType);

    /**
     * Returns a PayloadCreator for the current version
     *
     * @return \LaraPackage\Api\Contracts\PayloadCreator
     */
    public function makePayloadCreator();

    /**
     * Returns a RepresentationCreator for the current version
     *
     * @return \LaraPackage\Api\Contracts\RepresentationCreator
     */
    public function makeRepresentationCreator();
}
