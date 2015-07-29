<?php
namespace LaraPackage\Api\Contracts\Factory;

interface Factory
{
    /**
     * Returns a Payload class for the requested version
     *
     * @return \LaraPackage\Api\Contracts\Request\Payload
     */
    public function getRequestPayload();

    /**
     * Returns a PayloadCreator for the requested version
     *
     * @return \LaraPackage\Api\Contracts\PayloadCreator
     */
    public function makePayloadCreator();

    /**
     * Returns a RepresentationCreator for the requested version
     *
     * @return \LaraPackage\Api\Contracts\RepresentationCreator
     */
    public function makeRepresentationCreator();
}
