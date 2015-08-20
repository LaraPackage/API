<?php
namespace LaraPackage\Api\Factory;

use Illuminate\Contracts\Container\Container as App;

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
     * @param App                                       $app
     * @param \LaraPackage\Api\Contracts\Request\Parser $requestParser
     */
    public function __construct(App $app, \LaraPackage\Api\Contracts\Request\Parser $requestParser)
    {
        $this->app = $app;
        $this->requestParser = $requestParser;
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
        $factory = $this->versionFactoryName($version);
        $instance = $this->app->make($factory);
        $this->app->instance(\LaraPackage\Api\Contracts\Factory\VersionFactory::class, $instance);

        return $instance;
    }

    /**
     * @param int $version
     *
     * @return string
     */
    protected function versionFactoryName($version)
    {
        $factory = __NAMESPACE__.'\\'.'v'.$version.'\\'.'Factory';

        return $factory;
    }
}
