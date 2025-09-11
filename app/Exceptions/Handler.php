<?php

namespace App\Exceptions;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace'   => config('app.debug') ? $e->getTrace() : [],
            ], 500);
        }

        return parent::render($request, $e);
    }
}
