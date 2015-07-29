<?php
namespace LaraPackage\Api\Exceptions;

class TeapotException extends \Exception
{
    /**
     * @return string
     */
    public function boil()
    {
        return 'hot';
    }

    /**
     * @return string
     */
    public function pour()
    {
        return 'into cups';
    }
}
