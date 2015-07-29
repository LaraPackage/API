<?php
namespace LaraPackage\Api\Contracts\MediaType;

interface MediaType
{
    /**
     * Format data into a representation of a specific format
     *
     * @param array|\Traversable $data
     *
     * @return string
     */
    public function format($data);
}
