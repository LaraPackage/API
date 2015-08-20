<?php
namespace LaraPackage\Api\Factory;

use Illuminate\Contracts\Container\Container as App;
use LaraPackage\Api\Contracts\Config\ApiVersion;

class Factory implements \LaraPackage\Api\Contracts\Factory\Factory
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var \LaraPackage\Api\Contracts\Request\Parser
     */
    protected $requestParser;

    /**
     * @var \LaraPackage\Api\Contracts\Factory\VersionFactory
     */
    protected $versionFactory;

    /**
     * @var ApiVersion
     */
    private $apiVersion;

    /**
     * @param App                                       $app
     * @param \LaraPackage\Api\Contracts\Request\Parser $requestParser
     */
    public function __construct(App $app, \LaraPackage\Api\Contracts\Request\Parser $requestParser, ApiVersion $apiVersion)
    {
        $this->app = $app;
        $this->requestParser = $requestParser;
        $this->apiVersion = $apiVersion;
    }

    /**
     * @return \LaraPackage\Api\Contracts\Request\Payload
     */
    public function getRequestPayload()
    {
        return $this->createVersionFactory()->getRequestPayload();
    }

    /**
     * @return \LaraPackage\Api\Contracts\PayloadCreator
     */
    public function makePayloadCreator()
    {
        return $this->createVersionFactory()->makePayloadCreator();
    }

    /**
     * @return \LaraPackage\Api\Contracts\RepresentationCreator
     */
    public function makeRepresentationCreator()
    {
        return $this->createVersionFactory()->makeRepresentationCreator();
    }

    /**
     * Create the VersionFactory for the Version
     *
     * @return \LaraPackage\Api\Contracts\Factory\VersionFactory
     * @internal param $version
     *
     */
    protected function createVersionFactory()
    {
        $version = $this->requestParser->version();
        $factory = $this->apiVersion->factory($version);
        $instance = $this->app->make($factory);
        $this->app->instance(\LaraPackage\Api\Contracts\Factory\VersionFactory::class, $instance);

        return $instance;
    }
}
