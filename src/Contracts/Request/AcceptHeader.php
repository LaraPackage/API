<?php
namespace LaraPackage\Api\Contracts\Request;

interface AcceptHeader
{
    /**
     * Returns the media type that the client would like to receive as
     * a response.  If it is invalid, it returns the default media type
     * for the version.
     *
     * @return string
     */
    public function acceptedMediaType();

    /**
     * Returns the version that the client would like to work with.
     * If it is invalid, it returns the latest version.
     *
     * @return int
     */
    public function version();
}
