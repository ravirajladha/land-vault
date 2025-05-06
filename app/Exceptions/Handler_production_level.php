<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            return $this->renderHttpException($exception);
        }
    
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('error.404', [], 404);
        }
    
        if ($exception instanceof AccessDeniedHttpException) {
            return response()->view('error.403', [], 403);
        }
    
        if ($exception instanceof ServiceUnavailableHttpException) {
            return response()->view('error.503', [], 503);
        }
    
        // Custom error view for unhandled exceptions
        return response()->view('error.500', [], 500);
    }
    

}
