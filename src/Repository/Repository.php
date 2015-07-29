<?php
namespace LaraPackage\Api\Repository;

use LaraPackage\Api\Repository\Factory;
use LaraPackage\Api\CollectionHelper;
use LaraPackage\Api\Model\AbstractModel;
use LaraPackage\Api\Contracts\Event\Event;
use LaraPackage\Api\Exceptions\InvalidArgumentException;
use LaraPackage\Api\Exceptions\RepositoryException;
use LaraPackage\Api\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class Repository implements \LaraPackage\Api\Contracts\Repository\Repository
{

    /**
     * @var AbstractModel
     */
    protected $model;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param AbstractModel $model
     * @param Validator     $validator
     * @param Factory       $factory
     */
    public function __construct(
        AbstractModel $model,
        Validator $validator,
        Factory $factory
    )
    {
        $this->model = $model;
        $this->validator = $validator;
        $this->factory = $factory;
    }

    /**
     * @inheritdoc
     */
    public function collection($currentPosition, $pageSize, $with = null)
    {
        $collection = $this->model->where('id', '>', $currentPosition);

        if ($with) {
            $collection = $collection->with($with);
        }

        $paginator = $collection->simplePaginate($pageSize);

        return $this->cursor($paginator);
    }

    /**
     * @inheritdoc
     */
    public function delete(array $ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * @inheritdoc
     */
    public function deleteRelation($table, array $relationIds, $itemColumn = null, array $itemIds = [], Event $event = null)
    {
        $itemColumnIsDefined = count($itemIds) > 0;

        $db = $this->deleteWhereRelation($table, $relationIds);

        if ($itemColumnIsDefined) {
            $db->whereIn($itemColumn, $itemIds);
        }

        $numberDeleted = $db->delete();

        if ($event !== null) {
            \Event::fire($event);
        }

        return $numberDeleted;
    }

    /**
     * @inheritdoc
     */
    public function entity($id)
    {
        $model = $this->model->find($id);

        if (is_null($model)) {
            throw new NotFoundHttpException('Model id not found');
        }

        return $this->makeEntity($model);
    }

    /**
     * @inheritdoc
     */
    public function patch($id, array $data)
    {
        $data = $this->addIdToKeyedArray($id, $data);
        $this->validate($data, $this->model->updateValidationRules($id));

        return $this->update($id, $data);
    }

    /**
     * @inheritdoc
     */
    public function post(array $data)
    {
        $this->validate($data, $this->model->createValidationRules());

        return $this->create($data);
    }

    /**
     * @inheritdoc
     */
    public function postRelations($table, array $collection)
    {
        $this->validate($collection, $this->model->createRelationsValidationRules());

        return $this->db()->table($table)->insert($collection);
    }

    /**
     * @inheritdoc
     */
    public function put($id, array $data)
    {
        $data = $this->addIdToKeyedArray($id, $data);
        $this->validate($data, $this->model->replaceValidationRules($id));

        return $this->update($id, $data);
    }

    /**
     * @inheritdoc
     */
    public function putRelations($table, array $relationIds, array $collection)
    {
        $this->validate($collection, $this->model->replaceRelationsValidationRules());

        $delete = $this->deleteWhereRelation($table, $relationIds);

        $delete->delete();

        return $this->db()->table($table)->insert($collection);
    }

    /**
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    protected function addIdToKeyedArray($id, array $data)
    {
        $data['id'] = $id;

        return $data;
    }

    /**
     * @param array $array
     *
     * @return \LaraPackage\Api\Contracts\Resource\Entity
     */
    protected function create(array $array)
    {
        $model = $this->model->create($array);

        return $this->makeEntity($model);
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    protected function createDeleteDataArray(array $ids)
    {
        return ['id' => $ids];
    }

    /**
     * @param Paginator $paginator
     *
     * @return \LaraPackage\Api\Contracts\Resource\Collection
     */
    protected function cursor(Paginator $paginator)
    {
        return $this->factory->cursor($paginator);
    }

    /**
     * @param int $id the id to find in the model
     * @param int $current
     * @param int $pageSize
     *
     * @return \LaraPackage\Api\Contracts\Resource\Collection
     */
    protected function cursorFromRelation($id, $current, $pageSize, $relation = null, $with = null)
    {
        if (is_null($relation)) {
            $relation = debug_backtrace(false)[1]['function'];
        }

        $model = $this->model->find($id);

        if ($model === null) {
            throw new InvalidArgumentException('Model id not found for cursorFromRelation');
        }

        $collection = $model->{$relation}()->where('id', '>', $current);

        if ($with) {
            $collection = $collection->with($with);
        }

        $collection = $collection->take($pageSize)->get()->unique();

        $paginator = $this->factory->paginator($collection, $pageSize, $current);

        return $this->cursor($paginator);
    }

    /**
     * @return \Illuminate\Database\DatabaseManager
     */
    protected function db()
    {
        return \App::make('db');
    }

    /**
     * @param string $table
     * @param array $relationIds
     *
     * @return mixed
     */
    protected function deleteWhereRelation($table, array $relationIds)
    {
        $delete = $this->db()->table($table);

        foreach ($relationIds as $column => $id) {
            $delete->where($column, $id);
        }

        return $delete;
    }

    /**
     * @param Model $model
     *
     * @return \LaraPackage\Api\Contracts\Resource\Entity
     */
    protected function makeEntity(Model $model)
    {
        return $this->factory->entity($model);
    }

    /**
     * @param array $data
     * @param array $rules
     *
     * @return array
     */
    protected function runValidation(array $data, array $rules)
    {
        $validation = $this->validator->make(
            $data,
            $rules,
            $this->model->validationMessages()
        );

        if ($validation->fails()) {
            $validation->setAttributeNames($this->model->frontEndAttributeNames());

            $messagesArray = $validation->messages()->getMessages();

            return $this->model->forwardTransformAttributeKeyNames($messagesArray);
        }

        return null;
    }

    /**
     * @param int $id
     * @param array $array
     *
     * @return \LaraPackage\Api\Contracts\Resource\Entity
     * @throws RepositoryException
     */
    protected function update($id, array $array)
    {
        /** @var AbstractModel $model */
        $model = $this->model->find($id);

        if (!$model->update($array)) {
            throw new RepositoryException('Could not be saved');
        }

        return $this->makeEntity($model);
    }

    /**
     * @param array $data an associative array of fields that map to the model's fields
     * @param array $rules
     *
     * @return $this
     * @throws ValidationException
     */
    protected function validate(array $data, array $rules)
    {
        if (CollectionHelper::isCollection($data)) {
            $validationMessages = [];
            $i = 0;
            foreach ($data as $item) {
                $validationMessages['collection_index_'.$i] = $this->runValidation($item, $rules);
                $i++;
            }

            $validationMessages = array_filter($validationMessages);
        } else {
            $validationMessages = $this->runValidation($data, $rules);
        }

        if (count($validationMessages) > 0) {
            throw new ValidationException($validationMessages, 'Validation failed');
        }
    }
}
