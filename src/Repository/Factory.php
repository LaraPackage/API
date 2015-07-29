<?php
namespace LaraPackage\Api\Repository;

use Illuminate\Contracts\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Factory
{
    /**
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param Paginator $paginator
     *
     * @return \LaraPackage\Api\Resource\LaravelCollection
     */
    public function cursor(Paginator $paginator)
    {
        return new \LaraPackage\Api\Resource\LaravelCollection(
            $paginator,
            $this->app->make(\LaraPackage\Api\Contracts\Config\Api::class),
            $this->app->make(\LaraPackage\Api\Contracts\Request\Parser::class)
        );
    }

    /**
     * @param Model $model
     *
     * @return \LaraPackage\Api\Resource\LaravelEntity
     */
    public function entity(Model $model)
    {
        return new \LaraPackage\Api\Resource\LaravelEntity($model);
    }

    /**
     * @param $collection
     * @param $pageSize
     * @param $current
     *
     * @return Paginator
     */
    public function paginator($collection, $pageSize, $current)
    {
        return new Paginator($collection, $pageSize, $current);
    }
}
