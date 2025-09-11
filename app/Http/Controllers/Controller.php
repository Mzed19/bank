<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    public function sendContentAndCode($content, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($content, $code);
    }
}
