<?php

namespace App\Exceptions;

use Throwable;
use App\Utilities\Result;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        $class = get_class($e);
        $class = substr($class, strrpos($class, '\\') + 1);
        switch ($class) {
            case 'ValidationException': //422
                $result = new Result();
                $result->setMessage($e->getMessage());
                $result->setErrors($e->validator->errors());
                $result->setSuccess(false);
                return response()->json($result->response(), Response::HTTP_UNPROCESSABLE_ENTITY);
                break;
            case 'ModelNotFoundException': //404
            case 'NotFoundHttpException': //404
                $result = new Result();
                $result->setMessage($e->getMessage());
                $result->setErrors(null);
                $result->setSuccess(false);
                return response()->json($result->response(), Response::HTTP_NOT_FOUND);
                break;
            default:
        }
        return parent::render($request, $e);
    }
}
