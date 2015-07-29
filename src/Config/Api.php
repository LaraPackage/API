<?php
namespace LaraPackage\Api\Config;

use Illuminate\Contracts\Container\Container as App;

class Api implements \LaraPackage\Api\Contracts\Config\Api
{
    const API = 'api.';

    /**
     * @var App|\ArrayAccess
     */
    protected $app;

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Retrieves an index from the API config file
     *
     * @param string $index
     *
     * @return mixed
     */
    public function getIndex($index)
    {
        return $this->config()->get(self::API.$index);
    }

    /**
     * Retrieves an index for a version from the API config file
     *
     * @param string $index
     * @param int    $version
     *
     * @return mixed
     */
    public function getIndexForVersion($index, $version)
    {
        return $this->config()->get(self::API.'versions.'.$version.'.'.$index);
    }

    /**
     * @return \Illuminate\Config\Repository
     */
    protected function config()
    {
        return $this->app->offsetGet('config');
    }
}
