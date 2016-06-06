<?php

namespace LaraPackage\Api\Providers;

use LaraPackage\Api\Config;

class ApiServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../../config/api.php' => config_path('api.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\LaraPackage\Api\Contracts\Request\Parser::class, \LaraPackage\Api\Request\Parser::class);
        $this->app->singleton(\LaraPackage\Api\Contracts\Factory\Factory::class, \LaraPackage\Api\Factory\Factory::class);
        $this->app->singleton(\LaraPackage\Api\Contracts\Config\Api::class, \LaraPackage\Api\Config\Api::class);
        $this->app->singleton(\LaraPackage\Api\Contracts\Request\AcceptHeader::class, \LaraPackage\Api\Request\AcceptHeader::class);
        $this->app->singleton(\LaraPackage\Api\Contracts\Config\ApiVersion::class, \LaraPackage\Api\Config\ApiVersion::class);
        $this->app->singleton(\LaraPackage\Api\Contracts\ApiFacade::class, \LaraPackage\Api\ApiFacade::class);
        $this->app->singleton(\PrometheusApi\Utilities\Contracts\Uri\Parser::class, \PrometheusApi\Utilities\Uri\Parser::class);
        $this->app->singleton(\LaraPackage\RandomId\Contracts\Retriever::class, \LaraPackage\RandomId\Retriever::class);
        $this->app->singleton(\LaraPackage\Api\Contracts\Repository\Helper\Relational::class, \LaraPackage\Api\Repository\Helper\Relational::class);

        $this->app->singleton(\LaraPackage\Api\Contracts\Exceptions\ApiExceptionHandler::class, \LaraPackage\Api\ApiExceptionHandler::class);

        $this->app->singleton(\LaraPackage\Api\Contracts\PayloadCreator::class, \LaraPackage\Api\Implementations\PayloadCreator::class);
        $this->app->singleton(\LaraPackage\Api\Contracts\RepresentationCreator::class, \LaraPackage\Api\Implementations\RepresentationCreator::class);

        $this->app->bind(\LaraPackage\Api\Contracts\Resource\Collection::class, function ($app, $params) {
            $paginator = $params[0];

            return new \LaraPackage\Api\Resource\LaravelCollection(
                $paginator,
                $this->app->make(\LaraPackage\Api\Contracts\Config\Api::class),
                $this->app->make(\LaraPackage\Api\Contracts\Request\Parser::class)
            );
        });

        $this->app->bind(\LaraPackage\Api\Contracts\Resource\Entity::class, function ($app, $parameters) {
            $model = $parameters[0];

            return new \LaraPackage\Api\Resource\LaravelEntity($model);
        });
    }
}
