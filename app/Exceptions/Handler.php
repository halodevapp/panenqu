<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Exception $exception, $request) {
            if ($request->is('api/*')) {

                $name = (new ReflectionClass($exception))->getShortName();
                $message = $exception->getMessage() == "" ? $name : $exception->getMessage();
                $exceptionHasData = property_exists($exception, 'data');

                if ($exception instanceof ValidationException) {
                    return response()->json([
                        'data' => [
                            'exception' => $name,
                            'error' => $exception->errors()
                        ],
                        'success' => false,
                        'message' => $message
                    ], $exception->status);
                }

                $dataResponse = [
                    'exception' => $name
                ];

                if ($exceptionHasData) array_merge($dataResponse, $exception->data);

                return response()->json([
                    'data' => $dataResponse,
                    'success' => false,
                    'message' => $message,
                ], $exception->getCode() ?: 500);
            }
        });
    }
}
