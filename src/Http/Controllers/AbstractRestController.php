<?php
namespace LaraPackage\Api\Http\Controllers;

use LaraPackage\Api\CollectionHelper;
use LaraPackage\Api\Contracts\Entity\Transformer\Transformer;
use Illuminate\Routing\Controller;

class AbstractRestController extends Controller
{
    /**
     * @var \LaraPackage\Api\Contracts\Repository\Repository
     */
    protected $repository;

    /**
     * @var Transformer
     */
    protected $transformer;

    /**
     * @var \LaraPackage\Api\ApiFacade
     */
    protected $api;

    /**
     * @var CollectionHelper
     */
    protected $collectionHelper;

    /**
     * @param \LaraPackage\Api\Contracts\Repository\Repository $repository
     * @param Transformer                     $transformer
     * @param \LaraPackage\Api\Contracts\ApiFacade             $api
     * @param CollectionHelper                $collectionHelper
     */
    public function __construct(
        \LaraPackage\Api\Contracts\Repository\Repository $repository,
        Transformer $transformer,
        \LaraPackage\Api\Contracts\ApiFacade $api,
        CollectionHelper $collectionHelper
    )
    {
        $this->repository = $repository;
        $this->transformer = $transformer;
        $this->api = $api;
        $this->collectionHelper = $collectionHelper;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->api->post($this->repository, $this->transformer);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->api->delete($id, $this->repository);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cursor = $this->repository->collection($this->collectionHelper->currentPosition(), $this->collectionHelper->pageSize());

        return $this->api->collection($cursor, $this->transformer);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function replace($id)
    {
        return $this->api->put($id, $this->repository, $this->transformer);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entity = $this->repository->entity($id);

        return $this->api->entity($entity, $this->transformer);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->api->patch($id, $this->repository, $this->transformer);
    }
}
