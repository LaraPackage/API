<?php

namespace spec\LaraPackage\Api;

use LaraPackage\Api\Contracts\Entity\Transformer\ReverseTransformer;
use LaraPackage\Api\Contracts\Entity\Transformer\Transformer;
use LaraPackage\Api\Contracts\Factory\Factory;
use LaraPackage\Api\Contracts\PayloadCreator;
use LaraPackage\Api\Contracts\Repository\Helper\Relational;
use LaraPackage\Api\Contracts\Repository\Repository;
use LaraPackage\Api\Contracts\RepresentationCreator;
use LaraPackage\Api\Contracts\Request\Payload;
use LaraPackage\Api\Contracts\Resource\Collection;
use LaraPackage\Api\Contracts\Resource\Entity;
use Illuminate\Http\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ApiFacadeSpec extends ObjectBehavior
{

    function it_creates_a_bad_request_response(
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        \Exception $exception
    ) {
        $message = 'The request could not be understood by the server due to malformed syntax.';
        $this->errorExpectations(400, $message, $factory, $representationCreator, $response);
        $this->badRequest($exception)->shouldReturn($response);
    }

    function it_creates_a_collection_response(
        PayloadCreator $payload,
        RepresentationCreator $representationCreator,
        Factory $factory,
        Collection $cursor,
        Response $response,
        Transformer $transformer
    )
    {
        $factory->makePayloadCreator()->shouldBeCalled(1)->willReturn($payload);
        $factory->makeRepresentationCreator()->shouldBeCalled(1)->willReturn($representationCreator);
        $payload->cursor($cursor, $transformer)->shouldBeCalled(1);
        $representationCreator->make($payload)->shouldBeCalled()->willReturn($response);

        $this->collection($cursor, $transformer)->shouldReturn($response);
    }

    function it_creates_a_conflict_response(
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        \Exception $exception
    ) {
        $message = 'The request could not be completed due to a conflict with the current state of the resource.';
        $this->errorExpectations(409, $message, $factory, $representationCreator, $response);
        $this->conflict($exception)->shouldReturn($response);
    }

    function it_creates_a_i_am_a_teapot_response(
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        \Exception $exception
    ) {
        $message = 'If you think this is not an http status code--you are wrong: (RFC 2324)';
        $this->errorExpectations(418, $message, $factory, $representationCreator, $response);
        $this->iAmATeapot($exception)->shouldReturn($response);
    }

    function it_creates_a_method_not_allowed_response(
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        MethodNotAllowedHttpException $exception
    ) {
        $message = 'The method specified in the Request-Line is not allowed for the resource identified by the Request-URI.';
        $this->errorExpectations(405, $message, $factory, $representationCreator, $response);
        $this->methodNotAllowed($exception)->shouldReturn($response);
    }

    function it_creates_a_not_found_error_response(
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        \Exception $exception
    ) {
        $message = 'The server did not find anything matching the requested URI.';
        $this->errorExpectations(404, $message, $factory, $representationCreator, $response);
        $this->notFound($exception)->shouldReturn($response);
    }

    function it_creates_a_validation_error_response(
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        \LaraPackage\Api\Exceptions\ValidationException $exception
    ) {
        $failed = ['test_field' => 'no spaces'];
        $message = json_encode($failed);

        $exception->failed()->shouldBeCalled()->willReturn($failed);
        $this->errorExpectations(400, $message, $factory, $representationCreator, $response);
        $this->validationError($exception)->shouldReturn($response);
    }

    function it_creates_an_entity_response(
        PayloadCreator $payloadCreator,
        Response $response,
        Factory $factory,
        Entity $entity,
        RepresentationCreator $representationCreator,
        Transformer $transformer
    )
    {
        $factory->makePayloadCreator()->shouldBeCalled(1)->willReturn($payloadCreator);
        $payloadCreator->entity($entity, $transformer)->shouldBeCalled(1)->willReturn($payloadCreator);
        $factory->makeRepresentationCreator()->shouldBeCalled(1)->willReturn($representationCreator);

        $factory->makeRepresentationCreator()->shouldBeCalled()->willReturn($representationCreator);
        $representationCreator->make($payloadCreator, 200)->shouldBeCalled()->willReturn($response);

        $this->entity($entity, $transformer)->shouldReturn($response);
    }

    function it_creates_an_internal_error_response(
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        \Exception $exception
    ) {
        $message = 'The server encountered an unexpected condition which prevented it from fulfilling the request.';
        $this->errorExpectations(500, $message, $factory, $representationCreator, $response);
        $this->internalError($exception)->shouldReturn($response);
    }

    function it_creates_an_unauthorized_response(
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        \Exception $exception
    ) {
        $message = 'The request requires user authentication.';
        $this->errorExpectations(401, $message, $factory, $representationCreator, $response);
        $this->unauthorized($exception)->shouldReturn($response);
    }

    function it_deletes_a_relational_collection(
        Repository $repository,
        Response $response,
        Factory $factory,
        RepresentationCreator $responseCreator,
        Relational $relational
    ) {
        $table = 'image_product_site';
        $relationIds = ['site_id' => 2, 'product_id' => 7];

        $relational->table()->shouldBeCalled()->willReturn($table);
        $relational->relationIds()->shouldBeCalled()->willReturn($relationIds);

        $factory->makeRepresentationCreator()->shouldBeCalled()->willReturn($responseCreator);
        $responseCreator->make([], 204)->shouldBeCalled()->willReturn($response);

        $this->deleteRelationalCollection($repository)->shouldReturn($response);
    }

    function it_deletes_a_resource(
        Repository $repository,
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response
    )
    {
        $ids = '1,2';
        $idsArray = ['1', '2'];

        $factory->makeRepresentationCreator()->shouldBeCalled()->willReturn($representationCreator);
        $representationCreator->make([], 204)->shouldBeCalled()->willReturn($response);
        $repository->delete($idsArray)->shouldBeCalled();
        $this->delete($ids, $repository)->shouldReturn($response);
    }

    function it_deletes_relations(
        Repository $repository,
        Response $response,
        Factory $factory,
        RepresentationCreator $representationCreator,
        Relational $relational
    ) {
        $table = 'image_product_site';
        $relationIds = ['site_id' => 2, 'product_id' => 7];
        $itemColumn = 'image_id';
        $itemIds = [6, 7, 10];

        $relational->table()->shouldBeCalled()->willReturn($table);
        $relational->relationIds()->shouldBeCalled()->willReturn($relationIds);
        $relational->itemColumn()->shouldBeCalled()->willReturn($itemColumn);
        $relational->itemIds()->shouldBeCalled()->willReturn($itemIds);

        $repository->deleteRelation($table, $relationIds, $itemColumn, $itemIds, null)->shouldBeCalled();

        $factory->makeRepresentationCreator()->shouldBeCalled()->willReturn($representationCreator);
        $representationCreator->make([], 204)->shouldBeCalled()->willReturn($response);

        $this->deleteRelation($repository)->shouldReturn($response);
    }

    function it_patches_a_resource(
        Payload $payload,
        Factory $factory,
        PayloadCreator $payloadCreator,
        Repository $dbRepository,
        Transformer $transformer,
        Entity $entity,
        RepresentationCreator $representationCreator,
        Response $response
    )
    {
        $id = 3;
        $array = ['test'];
        $factory->getRequestPayload()->shouldBeCalled()->willReturn($payload);
        $payload->getIterator()->shouldBeCalled()->willReturn($array);
        $transformer->reverseTransform($array)->shouldBeCalled()->willReturn($array);
        $dbRepository->patch($id, $array)->shouldBeCalled()->willReturn($entity);

        $factory->makePayloadCreator()->shouldBeCalled()->willReturn($payloadCreator);
        $payloadCreator->entity($entity, $transformer)->shouldBeCalled()->willReturn($payloadCreator);
        $factory->makeRepresentationCreator()->shouldBeCalled()->willReturn($representationCreator);

        $representationCreator->make($payloadCreator, 200)->shouldBeCalled()->willReturn($response);


        $this->patch($id, $dbRepository, $transformer)->shouldReturn($response);
    }

    function it_posts_a_resource(
        Payload $payload,
        Factory $factory,
        PayloadCreator $payloadCreator,
        Repository $dbRepository,
        Transformer $transformer,
        Entity $entity,
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response
    ) {
        $array = ['test'];

        $factory->getRequestPayload()->shouldBeCalled()->willReturn($payload);
        $payload->getIterator()->shouldBeCalled()->willReturn($array);
        $transformer->reverseTransform($array)->shouldBeCalled()->willReturn($array);
        $dbRepository->post($array)->shouldBeCalled()->willReturn($entity);

        $factory->makePayloadCreator()->shouldBeCalled()->willReturn($payloadCreator);
        $payloadCreator->entity($entity, $transformer)->shouldBeCalled()->willReturn($payloadCreator);
        $factory->makeRepresentationCreator()->shouldBeCalled()->willReturn($representationCreator);

        $representationCreator->make($payloadCreator, 201)->shouldBeCalled()->willReturn($response);

        $this->post($dbRepository, $transformer)->shouldReturn($response);
    }

    function it_posts_multiple_relations(
        Repository $repository,
        Response $response,
        Payload $payload,
        Factory $factory,
        RepresentationCreator $responseCreator,
        ReverseTransformer $transformer,
        Relational $relational
    )
    {
        $table = 'image_product_site';
        $columnsValues = [
            ['image_id' => 5, 'product_id' => 7, 'site_id' => 10],
            ['image_id' => 7, 'product_id' => 7, 'site_id' => 10],
        ];

        $columnsValuesWithTimestamps = $this->addTimestampsToCollection($columnsValues);

        $rawBody = [['id' => 5], ['id', 7]];
        $body = [['image_id' => 5], ['image_id', 7]];

        $factory->getRequestPayload()->shouldBeCalled()->willReturn($payload);

        $payload->getIterator()->shouldBeCalled()->willReturn($rawBody);
        $transformer->reverseTransform($rawBody)->shouldBeCalled()->willReturn($body);

        $relational->addRelationalIdsToEachItemInCollection($body)->shouldBeCalled()->willReturn($columnsValues);
        $relational->addTimestampsToEachItemInCollection($columnsValues)->shouldBeCalled()->willReturn($columnsValuesWithTimestamps);

        $relational->table()->shouldBeCalled()->willReturn($table);
        $repository->postRelations($table, $columnsValuesWithTimestamps)->shouldBeCalled();

        $this->postRelation($repository, $transformer);
    }

    function it_puts_a_collection_to_a_relation(
        Repository $repository,
        Response $response,
        Payload $payload,
        Factory $factory,
        RepresentationCreator $responseCreator,
        ReverseTransformer $transformer,
        Relational $relational
    )
    {
        $table = 'image_product_site';
        $columnsValues = [
            ['image_id' => 5, 'product_id' => 7, 'site_id' => 10],
            ['image_id' => 7, 'product_id' => 7, 'site_id' => 10],
        ];

        $columnsValuesWithTimestamps = $this->addTimestampsToCollection($columnsValues);

        $rawBody = [['id' => 5], ['id', 7]];
        $body = [['image_id' => 5], ['image_id', 7]];

        $relationIds = ['site_id' => 10, 'product_id' => 7];


        $relational->relationIds()->shouldBeCalled()->willReturn($relationIds);

        $factory->getRequestPayload()->shouldBeCalled()->willReturn($payload);

        $payload->getIterator()->shouldBeCalled()->willReturn($rawBody);
        $transformer->reverseTransform($rawBody)->shouldBeCalled()->willReturn($body);

        $relational->addRelationalIdsToEachItemInCollection($body)->shouldBeCalled()->willReturn($columnsValues);
        $relational->addTimestampsToEachItemInCollection($columnsValues)->shouldBeCalled()->willReturn($columnsValuesWithTimestamps);

        $relational->table()->shouldBeCalled()->willReturn($table);
        $repository->putRelations($table, $relationIds, $columnsValuesWithTimestamps)->shouldBeCalled();

        $this->putRelationalCollection($repository, $transformer);
    }

    function it_puts_a_resource(
        Payload $payload,
        Factory $factory,
        PayloadCreator $payloadCreator,
        Repository $dbRepository,
        Transformer $transformer,
        Entity $entity,
        RepresentationCreator $representationCreator,
        Response $response
    ) {
        $id = 3;
        $array = ['test'];
        $factory->getRequestPayload()->shouldBeCalled()->willReturn($payload);
        $payload->getIterator()->shouldBeCalled()->willReturn($array);
        $transformer->reverseTransform($array)->shouldBeCalled()->willReturn($array);
        $dbRepository->put($id, $array)->shouldBeCalled()->willReturn($entity);

        $factory->makePayloadCreator()->shouldBeCalled()->willReturn($payloadCreator);
        $payloadCreator->entity($entity, $transformer)->shouldBeCalled()->willReturn($payloadCreator);
        $factory->makeRepresentationCreator()->shouldBeCalled()->willReturn($representationCreator);

        $representationCreator->make($payloadCreator, 200)->shouldBeCalled()->willReturn($response);

        $this->put($id, $dbRepository, $transformer)->shouldReturn($response);
    }

    function let(Factory $factory, Relational $relational)
    {
        $this->beConstructedWith($factory, $relational);
    }

    protected function addTimestampsToArray(array $array)
    {
        return array_merge($array, ['created_at' => 'now', 'updated_at' => 'now']);
    }

    protected function addTimestampsToCollection(array $collection)
    {
        $out = [];

        foreach ($collection as $item) {
            $out[] = $this->addTimestampsToArray($item);
        }

        return $out;
    }

    protected function errorExpectations(
        $statusCode,
        $message,
        Factory $factory,
        RepresentationCreator $representationCreator,
        Response $response,
        $headers = []
    )
    {
        $responseArray = [
            'error' => [
                'http_status' => $statusCode,
                'message' => $message,
            ],
        ];

        $factory->makeRepresentationCreator()->shouldBeCalled()->willReturn($representationCreator);
        $representationCreator->make($responseArray, $statusCode, $headers)->shouldBeCalled()->willReturn($response);
    }
}
