<?php
namespace LaraPackage\Api\Request;

use LaraPackage\Api\Exceptions\RequestException;
use Illuminate\Http\Request;

class Payload implements \LaraPackage\Api\Contracts\Request\Payload
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        $content = $this->request->getContent();
        $array = json_decode($content, true);

        if (is_null($array)) {
            throw new RequestException('Payload could not be parsed from json');
        }

        return new \ArrayIterator($array);
    }
}
