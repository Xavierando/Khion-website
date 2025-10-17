<?php

namespace App\Traits;

trait ApiResponses
{
    protected function ok(string $message, Array $data = [])
    {
        return $this->success($message, $data, 200);
    }

    protected function success(string $message, Array $data = [], int $statusCode = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
        ], 200);
    }

    protected function error(string | Array $errors = [], int $statusCode = 200)
    {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode,
            ], 200);
        }

        return response()->json([
            'errors' => $errors,
        ]);
    }

    protected function notAuthorized(string $message)
    {
        return $this->error($message,401);
    }
}
