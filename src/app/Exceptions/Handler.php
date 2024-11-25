<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;

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
        // Bad Request (400)
        if ($exception instanceof ValidationException) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_BAD_REQUEST',
                'msg' => $exception->getMessage(),
            ], 400);
        }

        // Invalid Access Token (401)
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_ACCESS_TOKEN',
                'msg' => 'invalid access token',
            ], 401);
        }

        // Forbidden Access (403)
        if ($exception instanceof AccessDeniedHttpException) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_FORBIDDEN_ACCESS',
                'msg' => "user doesn't have enough authorization",
            ], 403);
        }

        // Not Found (404)
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_NOT_FOUND',
                'msg' => 'resource is not found',
            ], 404);
        }

        // Internal Server Error (500)
        return response()->json([
            'status' => 500,
            'err' => 'ERR_INTERNAL_ERROR',
            'msg' => $exception->getMessage()//'unable to connect into database',
        ], 500);
    }
}
