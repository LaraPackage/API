<?php
namespace LaraPackage\Api\Exceptions;


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
use LaraPackage\Api\Contracts as Contracts;

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
            return $this->apiFacade->notFound($e, $e->getMessage());
        }

        if ($this->exceptionIs(MethodNotAllowedHttpException::class, $e)) {
            return $this->apiFacade->methodNotAllowed($e, $e->getMessage());
        }

        if ($this->exceptionIs(BadRequestHttpException::class, $e)) {
            return $this->apiFacade->badRequest($e, $e->getMessage());
        }

        if ($this->exceptionIs(UnauthorizedHttpException::class, $e)) {
            return $this->apiFacade->unauthorized($e, $e->getMessage());
        }

        if ($this->exceptionIs(ConflictHttpException::class, $e)) {
            return $this->apiFacade->conflict($e, $e->getMessage());
        }

        if ($this->exceptionIs(QueryException::class, $e)) {
            return $this->apiFacade->internalError($e, $e->getMessage());
        }

        if ($this->exceptionIs(ErrorException::class, $e)) {
            return $this->apiFacade->internalError($e, $e->getMessage());
        }

        if ($this->exceptionIs(ValidationException::class, $e)) {
            return $this->apiFacade->validationError($e, $e->getMessage());
        }

        if ($this->exceptionIs(BindingResolutionException::class, $e)) {
            return $this->apiFacade->internalError($e, $e->getMessage());
        }

        if ($this->exceptionIs(\RuntimeException::class, $e)) {
            return $this->apiFacade->internalError($e, $e->getMessage());
        }

        if ($this->exceptionIs(TeapotException::class, $e)) {
            return $this->apiFacade->iAmATeapot($e, $e->getMessage());
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
