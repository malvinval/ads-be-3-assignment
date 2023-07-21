<?php

namespace App\Traits;

trait ApiResponse {
    public function success($message = null, $data = null, $status_code = 200) {
        return response()->json([
            "message" => $message,
            "data" => $data,
            "status_code" => $status_code,
        ], $status_code);
    }

    public function error($message, $data = null, $status_code) {
        return response()->json([
            "message" => $message,
            "data" => $data,
            "status_code" => $status_code,
        ], $status_code);
    }
}
