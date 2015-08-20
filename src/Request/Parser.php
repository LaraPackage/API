<?php
namespace LaraPackage\Api\Request;

use LaraPackage\Api\Config\ApiVersion;
use Illuminate\Http\Request;

class Parser implements \LaraPackage\Api\Contracts\Request\Parser
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ApiVersion
     */
    protected $version;

    /**
     * @var \LaraPackage\Api\Contracts\Request\AcceptHeader
     */
    private $acceptHeader;


    /**
     * @param Request                        $request
     * @param \LaraPackage\Api\Contracts\Request\AcceptHeader $acceptHeader
     */
    public function __construct(Request $request, \LaraPackage\Api\Contracts\Request\AcceptHeader $acceptHeader)
    {
        $this->request = $request;
        $this->acceptHeader = $acceptHeader;
    }

    /**
     * @inheritdoc
     */
    public function acceptedMediaType()
    {
        return $this->acceptHeader->acceptedMediaType();
    }

    /**
     * @inheritdoc
     */
    public function header($item)
    {
        return $this->request->headers->get($item);
    }

    /**
     * @inheritdoc
     */
    public function inIncludes($entity)
    {
        $includesArray = \explode(',', $this->includes());

        return \in_array($entity, $includesArray);
    }

    /**
     * @inheritdoc
     */
    public function includes()
    {
        return $this->request->query->get('include');
    }

    /**
     * @inheritdoc
     */
    public function query($item = null)
    {
        return $this->request->query($item);
    }

    /**
     * @inheritdoc
     */
    public function requestedMediaType()
    {
        $header = $this->request->header('Content-Type');

        return explode(';', $header)[0];
    }

    /**
     * @inheritdoc
     */
    public function version()
    {
        return $this->acceptHeader->version();
    }
}
