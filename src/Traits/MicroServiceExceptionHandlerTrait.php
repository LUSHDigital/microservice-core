<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Traits\MicroServiceExceptionHandlerTrait.
 */

namespace LushDigital\MicroServiceCore\Traits;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use LushDigital\MicroServiceCore\Enum\Status;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * A trait for handling common services exceptions.
 *
 * @package LushDigital\MicroServiceCore\Traits
 */
trait MicroServiceExceptionHandlerTrait
{
    use MicroServiceJsonResponseTrait;

    /**
     * List of exception types we want to handle.
     *
     * @var array
     */
    protected $microServicesExceptionTypes = [
        ModelNotFoundException::class,
        HttpException::class,
        ValidationException::class,
    ];

    /**
     * Check if the supplied exception is of any of the types we handle.
     *
     * @param Exception $e
     * @return bool
     */
    public function isMicroServiceException(Exception $e)
    {
        foreach ($this->microServicesExceptionTypes as $exceptionType) {
            if ($e instanceof $exceptionType) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle the exception and produce a valid JSON response.
     *
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleMicroServiceException(Exception $e)
    {
        // Determine the status code and message to return.
        if ($e instanceof HttpException) {
            return $this->handleHttpException($e);
        } elseif ($e instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException($e);
        } elseif ($e instanceof ValidationException) {
            return $this->handleValidationException($e);
        } else {
            return $this->handleGenericException($e);
        }
    }

    /**
     * Handle a HTTP exception and build return data.
     *
     * @param Exception $e
     * @return mixed
     */
    protected function handleHttpException(Exception $e)
    {
        $statusCode = $e->getStatusCode();

        // if there is no exception message just get the standard HTTP text.
        if (empty($e->getMessage())) {
            $message = Response::$statusTexts[$statusCode];
        } else {
            $message = $e->getMessage();
        }

        return $this->generateResponse('', null, $statusCode, Status::FAIL, $message);
    }

    /**
     * Handle a model not found exception and build return data.
     *
     * @param Exception $e
     * @return mixed
     */
    protected function handleModelNotFoundException(Exception $e)
    {
        // Get the status code and build the message.
        $statusCode = 404;
        $reflection = new \ReflectionClass($e->getModel());
        $message = $reflection->getShortName() . ' not found';

        return $this->generateResponse('', null, $statusCode, Status::FAIL, $message);
    }

    /**
     * Handle a validation exception and build return data.
     *
     * @param Exception $e
     * @return mixed
     */
    protected function handleValidationException(Exception $e)
    {
        // Get the status code and build the message.
        $statusCode = 422;
        $message = $e->getMessage();
        $errorData = $e->getResponse()->getData();

        return $this->generateResponse('errors', $errorData, $statusCode, Status::FAIL, $message);
    }

    /**
     * Handle a generic exception and build return data.
     *
     * @param Exception $e
     * @return mixed
     */
    protected function handleGenericException(Exception $e)
    {
        // Get the status code and build the message.
        $statusCode = 500;
        $message = $e->getMessage();

        return $this->generateResponse('', null, $statusCode, Status::FAIL, $message);
    }
}