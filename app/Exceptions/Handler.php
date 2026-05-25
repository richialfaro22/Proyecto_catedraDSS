<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ThrottleRequestsException) {
            return ApiResponse::error(
                'Demasiadas solicitudes. Intenta más tarde.',
                429
            );
        }
        
        // Error de autorización (Policies)
        if ($exception instanceof AuthorizationException) {
            return ApiResponse::error(
                'No tienes permisos para realizar esta acción',
                403
            );
        }

        // Error de validación
        if ($exception instanceof ValidationException) {
            return ApiResponse::error(
                'Error de validación',
                422,
                $exception->errors()
            );
        }

        // Recurso no encontrado
        if ($exception instanceof NotFoundHttpException) {
            return ApiResponse::error(
                'Recurso no encontrado',
                404
            );
        }

        // Error HTTP general
        if ($exception instanceof HttpException) {
            return ApiResponse::error(
                $exception->getMessage() ?: 'Error HTTP',
                $exception->getStatusCode()
            );
        }

        // Error interno (fallback)
        return ApiResponse::error(
            $exception->getMessage() ?: 'Error interno del servidor',
            500
        );
    }
    private function getStatusCode(Throwable $exception)
    {
        return match (true) {
            $exception instanceof AuthenticationException => 401,
            $exception instanceof AuthorizationException => 403,
            $exception instanceof ModelNotFoundException => 404,
            default => 500,
        };
    }
}