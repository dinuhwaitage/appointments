<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation error',
                'messages' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'error' => 'Unauthenticated',
            ], 401);
        }

        if ($exception instanceof QueryException && str_contains($exception->getMessage(), 'foreign key') || str_contains($exception->getMessage(), 'Integrity constraint violation')) {
            return response()->json([
                'error' => 'Delete blocked',
                'message' => 'This item is being used by another record and cannot be deleted.',
            ], 409);
        }

        return response()->json([
            'error' => 'Server error',
            'message' => 'Something went wrong. Please try again later.',
        ], 500);
    }
}
