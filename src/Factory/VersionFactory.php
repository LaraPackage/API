<?php
namespace LaraPackage\Api\Factory;

use Illuminate\Contracts\Container\Container as App;
use LaraPackage\Api\MediaType\Json;

class VersionFactory implements \LaraPackage\Api\Contracts\Factory\VersionFactory
{

    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @return \LaraPackage\Api\Contracts\Request\Payload
     */
    public function getRequestPayload()
    {
        return $this->app->make(\LaraPackage\Api\Request\Payload::class);
    }

    /**
     * @param string $mediaType
     *
     * @return \LaraPackage\Api\Contracts\MediaType\MediaType
     */
    public function makeMediaType($mediaType)
    {
        return new Json();
    }

    /**
     * @return \LaraPackage\Api\Contracts\PayloadCreator
     */
    public function makePayloadCreator()
    {
        return $this->app->make(\LaraPackage\Api\PayloadCreator::class);
    }

    /**
     * @return \LaraPackage\Api\Contracts\RepresentationCreator
     */
    public function makeRepresentationCreator()
    {
        return $this->app->make(\LaraPackage\Api\RepresentationCreator::class);
    }
}
