<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

abstract class Controller
{
    public function sendContent(array|JsonResource $content, ?int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($content, $code);
    }

    public function sendPaginated(Paginator|LengthAwarePaginator $paginator, $resource = null): JsonResponse
    {
        $content = $paginator->items();

        if(!is_null($resource)){
            $content = $resource::collection($paginator->items());
        }

        return response()->json([
            'content' => $content,
            'total' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'previousPageUrl' => $paginator->previousPageUrl(),
            'nextPageUrl' => $paginator->nextPageUrl()
        ]);
    }

    public function sendError($message, ?int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(['error' => $message], $code);
    }

    public function treatException(Exception $exception): JsonResponse
    {
        return match(get_class($exception)) {
            'Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException' => $this->sendError(
                message: $exception->getMessage(),
                code: Response::HTTP_UNPROCESSABLE_ENTITY
            ),
            default => $this->sendError(
                message: 'Ocorreu um erro inesperado',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            ),
        };
    }
}
