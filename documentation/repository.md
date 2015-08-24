# Repository


Extending `\LaraPackage\Api\Repository\Repository` provides collection, entity, and relational functionality.

Example repository implementation: 

```php
use LaraPackage\Api\Model\AbstractModel;
use Illuminate\Contracts\Validation\Factory as Validator;
use LaraPackage\Api\Repository\Factory;
use LaraPackage\Api\Repository\Repository;

class YourRepository extends Repository implements \App\Contracts\Repository\YourRepository
{
    protected $model;

    /**
     * @param AbstractModel     $model
     * @param Validator         $validator
     * @param Factory           $factory
     */
    public function __construct(AbstractModel $model, Validator $validator, Factory $factory)
    {
        parent::__construct($model, $validator, $factory);
    }
}
```
