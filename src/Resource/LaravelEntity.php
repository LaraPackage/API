<?php
namespace LaraPackage\Api\Resource;
use LaraPackage\Api\Exceptions\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class LaravelEntity implements \LaraPackage\Api\Contracts\Resource\Entity
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        if (false === ($model instanceof \LaraPackage\Api\Contracts\Entity\Entity)) {
            throw new InvalidArgumentException(get_class($model).' not an instanceof '.\LaraPackage\Api\Contracts\Entity\Entity::class);
        }

        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->model;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->model->id;
    }
}
