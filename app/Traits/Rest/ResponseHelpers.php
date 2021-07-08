<?php

namespace App\Traits\Rest;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseHelpers
{
    /**
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    public function createApiResponse($data = null, int $status = Response::HTTP_OK): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }

        return response()->json($data, $status);
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string|null $message
     * @return JsonResponse
     */
    protected function badRequest(string $message = null): JsonResponse
    {
        return $this->createApiResponse(['errors' => [$message]], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Returns json response for a page not found
     *
     * @param string|null $message
     * @return JsonResponse
     */
    protected function notFound(string $message = null): JsonResponse
    {
        return $this->createApiResponse(['errors' => [$message]], Response::HTTP_NOT_FOUND);
    }

    /**
     * Returns json response for a successfully request
     * but no contains response content
     *
     * @param string|null $message
     * @return JsonResponse
     */
    protected function okButNoContent(string $message = null): JsonResponse
    {
        return $this->createApiResponse($message, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function createApiResponseErrors(string $message, int $status): JsonResponse
    {
        return $this->createApiResponse(['errors' => [$message]], $status);
    }
}
