<?php
namespace LaraPackage\Api\Request;

use Illuminate\Http\Request;

class AcceptHeader implements \LaraPackage\Api\Contracts\Request\AcceptHeader
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \LaraPackage\Api\Contracts\Config\ApiVersion
     */
    protected $version;

    /**
     * @param Request                               $request
     * @param \LaraPackage\Api\Contracts\Config\ApiVersion           $version
     */
    public function __construct(Request $request, \LaraPackage\Api\Contracts\Config\ApiVersion $version)
    {
        $this->request = $request;
        $this->version = $version;
    }

    /**
     * @inheritdoc
     */
    public function acceptedMediaType()
    {
        if ($this->mediaTypeAfterVendorIsValid()) {
            return $this->acceptHeaderMediaTypeAfterVendor();
        }

        if ($this->mediaTypeAfterApplicationIsValid()) {
            return $this->acceptHeaderMediaTypeAfterApplication();
        }

        return $this->version->defaultMediaType($this->version());
    }

    /**
     * @inheritdoc
     */
    public function version()
    {
        $version = $this->parseVersion($this->acceptHeader());

        if ($this->invalidVersion($version)) {
            return $this->version->latest();
        }

        return (int)$version;
    }

    /**
     * @return string
     */
    protected function acceptHeader()
    {
        $header = $this->request->server('HTTP_ACCEPT', '');

        // Sometimes the accept header may contain something after application/json;
        return explode(';', $header)[0];
    }

    /**
     * @return bool|string
     */
    protected function acceptHeaderMediaTypeAfterApplication()
    {
        return $this->getAfter('/', $this->acceptHeader());
    }

    /**
     * @return bool|string
     */
    protected function acceptHeaderMediaTypeAfterVendor()
    {
        return $this->getAfter('+', $this->acceptHeader());
    }

    /**
     * @return string
     */
    protected function application()
    {
        return 'application';
    }

    /**
     * @param string $character
     * @param string $string
     *
     * @return bool|string
     */
    protected function getAfter($character, $string)
    {
        if (($pos = strpos($string, $character)) !== false) {
            return substr($string, $pos + 1);
        }

        return false;
    }

    /**
     * @param string $start
     * @param string $end
     * @param string $string
     *
     * @return bool
     * @throws \Exception
     */
    protected function getBetween($start, $end, $string)
    {
        $success = \preg_match('/'.$start.'(.*?)'.$end.'/s', $string, $matches);
        if ($success) {
            return $matches[1];
        }
        if ($success === false) {
            throw new \Exception('preg_match did not work.');
        }

        return false;
    }

    /**
     * @param $version
     *
     * @return bool
     */
    protected function invalidVersion($version)
    {
        return $this->version->isValid($version) === false;
    }

    /**
     * @return string
     */
    protected function mediaTypeAfterApplicationIsValid()
    {
        return $this->version->mediaTypeIsValid($this->acceptHeaderMediaTypeAfterApplication(), $this->version());
    }

    /**
     * @return string
     */
    protected function mediaTypeAfterVendorIsValid()
    {
        return $this->version->mediaTypeIsValid($this->acceptHeaderMediaTypeAfterVendor(), $this->version());
    }

    /**
     * @param string $string
     *
     * @return int
     */
    protected function parseVersion($string)
    {

        $version = \preg_replace('/[^0-9]/', '', $string);

        if (!is_numeric($version)) {
            return (int)$this->version->latest();
        }

        return (int)$version;
    }
}
