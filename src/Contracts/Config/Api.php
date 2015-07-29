<?php
namespace LaraPackage\Api\Contracts\Config;

interface Api
{
    /**
     * Retrieves an index from the API config file
     *
     * @param string $index
     *
     * @return mixed
     */
    public function getIndex($index);

    /**
     * Retrieves an index for a version from the API config file
     *
     * @param string $index
     * @param int    $version
     *
     * @return mixed
     */
    public function getIndexForVersion($index, $version);
}
