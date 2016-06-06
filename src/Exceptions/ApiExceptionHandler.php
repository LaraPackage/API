<?php
namespace LaraPackage\Api;


use ErrorException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\QueryException;
use LaraPackage\Api\Contracts\ApiFacade;
use LaraPackage\Api\Exceptions\TeapotException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiExceptionHandler implements Contracts\Exceptions\ApiExceptionHandler
{
    /**
     * @var ApiFacade
     */
    private $apiFacade;

    public function __construct(ApiFacade $apiFacade)
    {
        $this->apiFacade = $apiFacade;
    }


    public function handle(\Exception $e)
    {
        if ($this->exceptionIs(NotFoundHttpException::class, $e)) {
            return $this->apiFacade->notFound($e);
        }

        if ($this->exceptionIs(MethodNotAllowedHttpException::class, $e)) {
            return $this->apiFacade->methodNotAllowed($e);
        }

        if ($this->exceptionIs(BadRequestHttpException::class, $e)) {
            return $this->apiFacade->badRequest($e);
        }

        if ($this->exceptionIs(UnauthorizedHttpException::class, $e)) {
            return $this->apiFacade->unauthorized($e);
        }

        if ($this->exceptionIs(ConflictHttpException::class, $e)) {
            return $this->apiFacade->conflict($e);
        }

        if ($this->exceptionIs(QueryException::class, $e)) {
            return $this->apiFacade->internalError($e);
        }

        if ($this->exceptionIs(ErrorException::class, $e)) {
            return $this->apiFacade->internalError($e);
        }

        if ($this->exceptionIs(ValidationException::class, $e)) {
            return $this->apiFacade->validationError($e);
        }

        if ($this->exceptionIs(BindingResolutionException::class, $e)) {
            return $this->apiFacade->internalError($e);
        }

        if ($this->exceptionIs(\RuntimeException::class, $e)) {
            return $this->apiFacade->internalError($e);
        }

        if ($this->exceptionIs(TeapotException::class, $e)) {
            return $this->apiFacade->iAmATeapot($e);
        }

        return $this->apiFacade->internalError($e, 'Unknown error');
    }

    /**
     * @param $exceptionType
     * @param $e
     *
     * @return bool
     */
    private function exceptionIs($exceptionType, $e)
    {
        return is_a($e, $exceptionType);
    }
}
