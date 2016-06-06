<?php
namespace LaraPackage\Api\Contracts\Exceptions;

interface ApiExceptionHandler
{
    public function handle(\Exception $exception);
}
