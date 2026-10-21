<?php

namespace App\Http\Middleware;

use App\Services\DanhMucCache;
use Closure;
use Illuminate\Http\Request;

class IdempotencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $key = (string) $request->header('Idempotency-Key', '');
        if ($key === '' || !auth()->check() || !preg_match('/^[A-Za-z0-9\-]{8,80}$/', $key)) {
            return $next($request);
        }

        $kho = DanhMucCache::kho();
        $resultKey = 'idem:face:' . auth()->id() . ':' . $key;
        $lockKey = $resultKey . ':lock';

        $daCo = $kho->get($resultKey);
        if (is_array($daCo)) {
            return response()->json($daCo['body'], $daCo['status']);
        }

        if (!$kho->add($lockKey, 1, 30)) {
            for ($i = 0; $i < 20; $i++) {
                usleep(300000);
                $daCo = $kho->get($resultKey);
                if (is_array($daCo)) {
                    return response()->json($daCo['body'], $daCo['status']);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Đang xử lý lượt chấm công trước, vui lòng đợi.',
            ], 409);
        }

        try {
            $response = $next($request);
            $status = $response->getStatusCode();
            $body = json_decode($response->getContent(), true);
            if ($status < 500 && is_array($body)) {
                $kho->put($resultKey, ['body' => $body, 'status' => $status], 600);
            }

            return $response;
        } finally {
            $kho->forget($lockKey);
        }
    }
}
