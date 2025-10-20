<?php

namespace Modules\ApiResponder\Traits;

use Modules\ApiResponder\Services\ApiResponse;

trait RespondsWithApi
{
    protected function ok($data = null, string $message = '')
    {
        return ApiResponse::success($data, $message);
    }

    protected function fail(string $message, $errors = null, $code = 400)
    {
        $exceptionCodes = [0, 23000, '42S02', '42S22'];
        return ApiResponse::error($message, $errors, in_array($code, $exceptionCodes) ? 400 : $code);
    }
}
