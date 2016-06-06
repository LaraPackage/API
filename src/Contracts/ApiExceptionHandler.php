<?php
namespace LaraPackage\Api\Contracts;

interface ApiExceptionHandler
{
    public function handle(\Exception $exception);
}
