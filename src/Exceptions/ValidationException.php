<?php
namespace LaraPackage\Api\Exceptions;

use Exception;

class ValidationException extends \Illuminate\Contracts\Validation\ValidationException
{
    /**
     * @var array
     */
    protected $failed;

    /**
     * @param array  $failed  failed message array
     * @param string $message a global message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($failed = [], $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->failed = $failed;
    }

    /**
     * returns the failed messages
     *
     * @return array
     */
    public function failed()
    {
        return $this->failed;
    }
}
