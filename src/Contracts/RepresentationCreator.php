<?php
namespace LaraPackage\Api\Contracts;

interface RepresentationCreator
{
    /**
     * Creates a representation of a payload as a response
     *
     * @param array|\IteratorAggregate $data
     * @param int                      $statusCode
     * @param array                    $headers
     *
     * @return \Illuminate\Http\Response
     */
    public function make($data, $statusCode = 200, array $headers = []);
}
