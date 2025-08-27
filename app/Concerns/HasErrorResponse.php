<?php

namespace App\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

trait HasErrorResponse
{
    public function exceptionRes(Throwable $th, string $logMes, ?string $errorMes = null): JsonResponse
    {
        Log::error($logMes, [
            'code'         => $th->getCode(),
            'errorMessage' => $th->getMessage(),
        ]);

        return response()->json([
            'status'  => 'error',
            'message' => $errorMes ?? $th->getMessage(),
        ]);
    }
}
