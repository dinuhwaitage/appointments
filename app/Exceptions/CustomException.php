<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => 'Custom error message',
            'details' => $this->getMessage(),
        ], 400);
    }
}
