<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ApiResponseTrait
{

    public function success($data, string $message = null, int $status = 200)
    {
        $message = $message ?? __('Response successful');

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public function error($data = null, string $message = null, int $status = 400)
    {
        $message = $message ?? __('messages.error_occurred');

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data ?? [], // Default to an empty array if no data is provided
        ], $status);
    }
}
