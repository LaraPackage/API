<?php
namespace LaraPackage\Api\Implementations;

use Illuminate\Contracts\Routing\ResponseFactory;

class RepresentationCreator implements \LaraPackage\Api\Contracts\RepresentationCreator
{
    /**
     * @var \LaraPackage\Api\Contracts\Request\Parser
     */
    protected $requestParser;
    /**
     * @var ResponseFactory
     */
    protected $response;
    /**
     * @var \LaraPackage\Api\Contracts\Factory\VersionFactory
     */
    protected $versionFactory;
    /**
     * @var int
     */
    private $version = 4;
    /**
     * @var \LaraPackage\Api\Contracts\Config\ApiVersion
     */
    private $versionInfoRetriever;

    public function __construct(
        \LaraPackage\Api\Contracts\Request\Parser $requestParser,
        ResponseFactory $response,
        \LaraPackage\Api\Contracts\Factory\VersionFactory $versionFactory,
        \LaraPackage\Api\Contracts\Config\ApiVersion $versionInfoRetriever
    ) {
        $this->requestParser = $requestParser;
        $this->response = $response;
        $this->versionFactory = $versionFactory;
        $this->versionInfoRetriever = $versionInfoRetriever;
    }

    /**
     * @inheritdoc
     */
    public function make($data, $statusCode = 200, array $headers = [])
    {
        $acceptedMediaType = $this->requestParser->acceptedMediaType();
        $mediaType = $this->versionFactory->makeMediaType($acceptedMediaType);

        $representation = $mediaType->format($data);

        $headers = $this->setContentTypeHeader($headers, $acceptedMediaType);

        return $this->response->make($representation, $statusCode, $headers);
    }

    /**
     * @param array $headers
     * @param       $mediaType
     *
     * @return array
     */
    protected function setContentTypeHeader(array $headers, $mediaType)
    {
        $headers['Content-Type'] = 'application/'.
            $this->versionInfoRetriever->vendor($this->version).
            $this->versionInfoRetriever->versionDesignator($this->version).'+'.$mediaType;

        return $headers;
    }
}
