### Controllers

Extending  `\LaraPackage\Api\Http\Controllers\AbstractRestController` provides GET, POST, PUT, PATCH, DELETE routes out of the box.

You need to use the repository and transformer for the controller.

Example usage: 

```php

use App\Repositories;
use App\v1\Transformer;
use LaraPackage\Api\CollectionHelper;
use LaraPackage\Api\Http\Controllers\AbstractRestController;

class YourController extends AbstractRestController
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
