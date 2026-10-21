<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $response;
        }
        if (!auth()->check()) {
            return $response;
        }

        $route = optional($request->route())->getName() ?: $request->path();
        if ($this->boQua($route)) {
            return $response;
        }

        try {
            $payload = $request->except([
                'password', 'password_confirmation', '_token', 'image', 'image_base64',
            ]);
            foreach ($payload as $key => $value) {
                if (is_string($value) && strlen($value) > 300) {
                    $payload[$key] = mb_substr($value, 0, 300) . '…';
                }
            }

            AuditLog::create([
                'nguoi_dung_id' => auth()->id(),
                'hanh_dong' => $request->method(),
                'route' => mb_substr((string) $route, 0, 180),
                'mo_ta' => mb_substr($request->path(), 0, 180),
                'du_lieu' => $payload,
                'ip' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            // Nhật ký không được làm hỏng nghiệp vụ chính.
        }

        return $response;
    }

    private function boQua(string $route): bool
    {
        foreach (['authenticate', 'warmup', 'notifications', 'mark-read', 'mark-as-read'] as $needle) {
            if (str_contains($route, $needle)) {
                return true;
            }
        }
        return false;
    }
}
