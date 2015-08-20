<?php
namespace LaraPackage\Api;

use LaraPackage\Api\Contracts\Entity\Transformer\ReverseTransformer;
use LaraPackage\Api\Contracts\Entity\Transformer\Transformer;
use LaraPackage\Api\Contracts\Repository\Helper\Relational;
use LaraPackage\Api\Contracts\Repository\Repository;
use LaraPackage\Api\Contracts\Event\Event;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class ApiFacade implements Contracts\ApiFacade
{

    /**
     * @var \LaraPackage\Api\Contracts\Factory\Factory
     */
    protected $factory;

    /**
     * @var Relational
     */
    private $relational;

    /**
     * @param \LaraPackage\Api\Contracts\Factory\Factory $factory
     * @param Relational                $relational
     */
    public function __construct(\LaraPackage\Api\Contracts\Factory\Factory $factory, Relational $relational)
    {
        $this->factory = $factory;
        $this->relational = $relational;
    }

    /**
     * @inheritdoc
     */
    public function badRequest(\Exception $e, $message = '')
    {
        $message = $message ?: 'The request could not be understood by the server due to malformed syntax.';

        return $this->error(400, $message, $e);
    }

    /**
     * @inheritdoc
     */
    public function collection(\LaraPackage\Api\Contracts\Resource\Collection $cursor, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer)
    {
        $payloadCreator = $this->factory->makePayloadCreator();
        $payloadCreator->cursor($cursor, $transformer);

        return $this->factory->makeRepresentationCreator()->make($payloadCreator);
    }

    /**
     * @inheritdoc
     */
    public function conflict(\Exception $e, $message = '')
    {
        $message = $message ?: 'The request could not be completed due to a conflict with the current state of the resource.';

        return $this->error(409, $message, $e);
    }

    /**
     * @inheritdoc
     */
    public function delete($idsString, Repository $repository)
    {
        $repository->delete($this->getArrayOfIdsFromString($idsString));

        return $this->factory->makeRepresentationCreator()->make([], 204);
    }

    /**
     * @inheritdoc
     */
    public function deleteRelation(Repository $repository, Event $event = null)
    {

        $repository->deleteRelation(
            $this->relational->table(),
            $this->relational->relationIds(),
            $this->relational->itemColumn(),
            $this->relational->itemIds(),
            $event
        );

        return $this->factory->makeRepresentationCreator()->make([], 204);
    }

    /**
     * @inheritdoc
     */
    public function deleteRelationalCollection(Repository $repository, Event $event = null)
    {
        $repository->deleteRelation(
            $this->relational->table(),
            $this->relational->relationIds(),
            null,
            [],
            $event
        );

        return $this->factory->makeRepresentationCreator()->make([], 204);
    }

    /**
     * @inheritdoc
     */
    public function entity(\LaraPackage\Api\Contracts\Resource\Entity $entity, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer, $responseCode = 200)
    {
        return $this->buildEntityResponse($transformer, $entity, $responseCode);
    }

    /**
     * @inheritdoc
     */
    public function iAmATeapot(\Exception $e, $message = '')
    {
        $message = $message ?: 'If you think this is not an http status code--you are wrong: (RFC 2324)';

        return $this->error(418, $message, $e);
    }

    /**
     * @inheritdoc
     */
    public function internalError(\Exception $e, $message = '')
    {
        $message = $message ?: 'The server encountered an unexpected condition which prevented it from fulfilling the request.';

        return $this->error(500, $message, $e);
    }

    /**
     * @inheritdoc
     */
    public function methodNotAllowed(MethodNotAllowedHttpException $e, $message = '')
    {
        $message = $message ?: 'The method specified in the Request-Line is not allowed for the resource identified by the Request-URI.';

        return $this->error(405, $message, $e);
    }

    /**
     * @inheritdoc
     */
    public function notFound(\Exception $e, $message = '')
    {
        $message = $message ?: 'The server did not find anything matching the requested URI.';

        return $this->error(404, $message, $e);
    }

    /**
     * @inheritdoc
     */
    public function patch($id, Repository $repository, ReverseTransformer $transformer)
    {
        $payload = $this->factory->getRequestPayload()->getIterator();
        $payload = $transformer->reverseTransform($payload);
        $entity = $repository->patch($id, $payload);

        return $this->buildEntityResponse($transformer, $entity, 200);
    }

    /**
     * @inheritdoc
     */
    public function post(Repository $repository, Transformer $transformer)
    {
        $payload = $this->factory->getRequestPayload()->getIterator();
        $payload = $transformer->reverseTransform($payload);
        $entity = $repository->post($payload);

        return $this->buildEntityResponse($transformer, $entity, 201);
    }

    /**
     * @inheritdoc
     */
    public function postRelation(Repository $repository, ReverseTransformer $transformer)
    {
        $payload = $this->factory->getRequestPayload()->getIterator();
        $body = $transformer->reverseTransform($payload);

        $insert = $this->relational->addRelationalIdsToEachItemInCollection($body);
        $insert = $this->relational->addTimestampsToEachItemInCollection($insert);

        $repository->postRelations($this->relational->table(), $insert);
    }

    /**
     * @inheritdoc
     */
    public function put($id, Repository $repository, ReverseTransformer $transformer)
    {
        $payload = $this->factory->getRequestPayload()->getIterator();
        $payload = $transformer->reverseTransform($payload);
        $entity = $repository->put($id, $payload);

        return $this->buildEntityResponse($transformer, $entity, 200);
    }

    /**
     * @inheritdoc
     */
    public function putRelationalCollection(Repository $repository, ReverseTransformer $transformer)
    {
        $payload = $this->factory->getRequestPayload()->getIterator();
        $body = $transformer->reverseTransform($payload);
        $collection = $this->relational->addRelationalIdsToEachItemInCollection($body);
        $collection = $this->relational->addTimestampsToEachItemInCollection($collection);
        $repository->putRelations($this->relational->table(), $this->relational->relationIds(), $collection);
    }

    /**
     * @inheritdoc
     */
    public function unauthorized(\Exception $e, $message = '')
    {
        $message = $message ?: 'The request requires user authentication.';

        return $this->error(401, $message, $e);
    }

    /**
     * @inheritdoc
     */
    public function validationError(Exceptions\ValidationException $e)
    {
        return $this->error(400, json_encode($e->failed()), $e);
    }

    /**
     * @inheritdoc
     */
    protected function buildEntityResponse(ReverseTransformer $transformer, $entity, $statusCode)
    {
        $representation = $this->factory->makePayloadCreator();
        $representation->entity($entity, $transformer);

        return $this->factory->makeRepresentationCreator()->make($representation, $statusCode);
    }

    /**
     * @inheritdoc
     */
    protected function getArrayOfIdsFromString($idsString)
    {
        $ids = explode(',', $idsString);

        return $ids;
    }

    /**
     * @inheritdoc
     */
    protected function isCollection(array $array)
    {
        $filtered = array_filter($array, function ($value) {
            if (!is_array($value)) {
                return $value;
            }
        });

        if (!empty($filtered)) {
            // some of the elements are not arrays
            return false;
        }

        // all of the elements are arrays
        return true;
    }

    /**
     * @inheritdoc
     */
    private function debug(\Exception $e)
    {
        return $debug = [
            'message'    => $e->getMessage(),
            'file'       => $e->getFile(),
            'line'       => $e->getLine(),
            'stacktrace' => $e->getTraceAsString(),
        ];
    }

    /**
     * @inheritdoc
     */
    private function error($statusCode, $message, \Exception $e, $headers = [])
    {

        $responseArray = [
            'error' => [
                'http_status' => $statusCode,
                'message'     => $message,
            ],
        ];

        // Other than production, debug information will be included with
        // the returned query if an exception is thrown and caught by the handler
        $appEnv = getenv('APP_ENV');
        if ($appEnv AND $appEnv !== 'production') {
            $responseArray['debug'] = $this->debug($e);
        }

        return $this->factory->makeRepresentationCreator()->make($responseArray, $statusCode, $headers);
    }
}
