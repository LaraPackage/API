<?php
namespace LaraPackage\Api\Implementations;

use InvalidArgumentException;
use LaraPackage\Api\Contracts\Factory\VersionFactory;
use LaraPackage\Api\Contracts\Request\Parser;
use LaraPackage\Api\FractalFactory;
use League\Fractal;

class PayloadCreator implements \LaraPackage\Api\Contracts\PayloadCreator
{
    /**
     * The payload to be returned
     *
     * @var array
     */
    protected $payload;

    /**
     * @var Parser
     */
    protected $requestParser;

    /**
     * @var FractalFactory
     */
    protected $fractalFactory;
    
    /**
     * @var VersionFactory
     */
    private $versionFactory;

    public function __construct(\LaraPackage\Api\Contracts\Request\Parser $requestParser, FractalFactory $fractalFactory, VersionFactory $versionFactory)
    {
        $this->requestParser = $requestParser;
        $this->fractalFactory = $fractalFactory;
        $this->versionFactory = $versionFactory;
    }

    /**
     * @inheritdoc
     */
    public function cursor(\LaraPackage\Api\Contracts\Resource\Collection $cursor, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer)
    {
        $resource = $this->fractalFactory->createCollection($cursor->getData(), $transformer);

        $fractalCursor = $this->fractalFactory->createCursor($cursor->getCurrent(), $cursor->getPrevious(), $cursor->getNext(), $cursor->getCount());

        $resource->setCursor($fractalCursor);

        $this->setPayload($resource);

        $this->removePrevCursor($payload);
    }

    /**
     * @inheritdoc
     */
    public function entity(\LaraPackage\Api\Contracts\Resource\Entity $entity, \LaraPackage\Api\Contracts\Entity\Transformer\Transformer $transformer)
    {
        $resource = $this->fractalFactory->createEntity($entity->getData(), $transformer);

        $this->setPayload($resource);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        if (!is_array($this->payload)) {
            throw new InvalidArgumentException('You must run a payload method before trying to use getIterator');
        }

        return new \ArrayIterator($this->payload);
    }

    /**
     * @param &$payload
     */
    protected function removePrevCursor(&$payload)
    {
        // this removes the previous count from the cursor
        if (isset($payload['meta']['cursor']['prev'])) {
            unset($payload['meta']['cursor']['prev']);
        }
    }

    /**
     * @param Fractal\Resource\ResourceInterface $resource
     *
     * @return void
     */
    protected function setPayload(Fractal\Resource\ResourceInterface $resource)
    {
        $includes = $this->requestParser->includes();
        $manager = $this->fractalFactory->createManager();

        if ($includes) {
            $manager = $manager->parseIncludes($includes);
        }

        $payload = $manager->createData($resource)->toArray();

        $this->payload = $payload;
    }
}
