# Getting Started

### Setup

1. Add `\LaraPackage\Api\Providers\ApiServiceProvider::class,` to your provider's array in `config/app.php`

2. Publish the config: `php artisan vendor:publish`
    
    - Change the config definitions to your needs.
    
    
### Controllers

Extending  `\LaraPackage\Api\Http\Controllers\AbstractRestController` provides GET, POST, PUT, PATCH, DELETE routes out of the box.

Example usage: 

```php

use App\Repositories;
use App\v1\Transformer;
use LaraPackage\Api\CollectionHelper;
use LaraPackage\Api\Http\Controllers\AbstractRestController;

class AttributekeysController extends AbstractRestController
{

    /**
     * @param Repositories\YourRepository $repository
     * @param Transformer\YourTransformer $transformer
     * @param \LaraPackage\Api\ApiFacade  $api
     * @param CollectionHelper            $collectionHelper
     */
    public function __construct(Repositories\YourRepository $repository, Transformer\YourTransformer $transformer, \LaraPackage\Api\ApiFacade $api, CollectionHelper $collectionHelper)
    {
        parent::__construct($attribute, $transformer, $api, $collectionHelper);
    }
}

```
