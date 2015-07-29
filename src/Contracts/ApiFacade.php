<?php
namespace LaraPackage\Api\Contracts;

use LaraPackage\Api\Contracts\Entity\Transformer\ReverseTransformer;
use LaraPackage\Api\Contracts\Entity\Transformer\Transformer;
use LaraPackage\Api\Contracts\Repository\Repository;
use LaraPackage\Api\Contracts\Event\Event;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

interface ApiFacade
{
    /**
     * @param \Exception $e
     * @param string     $message
     *
     * @return \Illuminate\Http\Response
     */
    public function badRequest(\Exception $e, $message = '');

    /**
     * @param \LaraPackage\Api\Contracts\Resource\Collection            $cursor
     * @param \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function collection(\LaraPackage\Api\Contracts\Resource\Collection $cursor, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer);

    /**
     * @param \Exception $e
     * @param string     $message
     *
     * @return \Illuminate\Http\Response
     */
    public function conflict(\Exception $e, $message = '');

    /**
     * @param                   $idsString
     * @param Repository        $repository
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($idsString, Repository $repository);

    /**
     * @param Repository $repository
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteRelation(Repository $repository, Event $event = null);

    /**
     * @param Repository $repository
     * @param Event|null $event
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteRelationalCollection(Repository $repository, Event $event = null);

    /**
     * @param \LaraPackage\Api\Contracts\Resource\Entity                $entity
     * @param \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer
     *
     * @return \Illuminate\Http\Response
     */
    public function entity(\LaraPackage\Api\Contracts\Resource\Entity $entity, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer, $responseCode = 200);

    /**
     * @param \Exception $e
     * @param string     $message
     *
     * @return \Illuminate\Http\Response
     */
    public function iAmATeapot(\Exception $e, $message = '');

    /**
     * @param \Exception $e
     * @param string     $message
     *
     * @return \Illuminate\Http\Response
     */
    public function internalError(\Exception $e, $message = '');

    /**
     * @param MethodNotAllowedHttpException $e
     * @param string                        $message
     *
     * @return \Illuminate\Http\Response
     */
    public function methodNotAllowed(MethodNotAllowedHttpException $e, $message = '');

    /**
     * @param \Exception $e
     * @param string     $message
     *
     * @return \Illuminate\Http\Response
     */
    public function notFound(\Exception $e, $message = '');

    /**
     * @param                    $id
     * @param Repository         $repository
     * @param ReverseTransformer $transformer
     *
     * @return \Illuminate\Http\Response
     */
    public function patch($id, Repository $repository, ReverseTransformer $transformer);

    /**
     * @param Repository  $repository
     * @param Transformer $transformer
     *
     * @return \Illuminate\Http\Response
     */
    public function post(Repository $repository, Transformer $transformer);

    /**
     * @param Repository         $repository
     * @param ReverseTransformer $transformer
     */
    public function postRelation(Repository $repository, ReverseTransformer $transformer);

    /**
     * @param                    $id
     * @param Repository         $repository
     * @param ReverseTransformer $transformer
     *
     * @return \Illuminate\Http\Response
     */
    public function put($id, Repository $repository, ReverseTransformer $transformer);

    /**
     * @param Repository         $repository
     * @param ReverseTransformer $transformer
     */
    public function putRelationalCollection(Repository $repository, ReverseTransformer $transformer);

    /**
     * @param \Exception $e
     * @param string     $message
     *
     * @return \Illuminate\Http\Response
     */
    public function unauthorized(\Exception $e, $message = '');

    /**
     * @param \LaraPackage\Api\Exceptions\ValidationException $e
     *
     * @return \Illuminate\Http\Response
     */
    public function validationError(\LaraPackage\Api\Exceptions\ValidationException $e);
}
