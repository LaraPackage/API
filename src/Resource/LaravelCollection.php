<?php
namespace LaraPackage\Api\Resource;
use Illuminate\Pagination\Paginator;

class LaravelCollection implements \LaraPackage\Api\Contracts\Resource\Collection
{

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var \LaraPackage\Api\Contracts\Config\Api
     */
    protected $config;

    /**
     * @var \LaraPackage\Api\Contracts\Request\Parser
     */
    protected $requestParser;

    /**
     * @param Paginator                $paginator
     * @param \LaraPackage\Api\Contracts\Config\Api     $config
     * @param \LaraPackage\Api\Contracts\Request\Parser $requestParser
     */
    public function __construct(Paginator $paginator, \LaraPackage\Api\Contracts\Config\Api $config, \LaraPackage\Api\Contracts\Request\Parser $requestParser)
    {
        $this->paginator = $paginator;
        $this->config = $config;
        $this->requestParser = $requestParser;
    }

    /**
     * @inheritdoc
     */
    public function getCount()
    {
        return (int)$this->paginator->count();
    }

    /**
     * @inheritdoc
     */
    public function getCurrent()
    {

        $currentPositionParameter = $this->config->getIndexForVersion('collection.current_position', $this->requestParser->version());
        $current = $this->requestParser->query($currentPositionParameter);
        if (!empty($current)) {
            return (int)$current;
        }

        return 0;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->paginator->getIterator();
    }

    /**
     * @inheritdoc
     */
    public function getNext()
    {
        $last = $this->paginator->getCollection()->last();

        if (is_null($last)) {
            return 0;
        }

        return (int)$last->id;
    }

    /**
     * @inheritdoc
     */
    public function getPageSize()
    {
        return (int)$this->paginator->perPage();
    }

    /**
     * @inheritdoc
     */
    public function getPrevious()
    {
        return (int)($this->getCurrent() - $this->getCount()) >= 0 ? $this->getCurrent() - $this->getCount() : 0;
    }
}
