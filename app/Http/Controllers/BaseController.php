<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    /**
     * Generate a JSON response.
     *
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    protected function jsonResponse($data, $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    /**
     * Handle not found scenarios.
     *
     * @param string $entity
     * @return JsonResponse
     */
    protected function notFoundResponse($entity = 'Resource'): JsonResponse
    {
        return $this->jsonResponse(['message' => "$entity not found"], 404);
    }
}
