<?php
// app/Http/Middleware/CheckAttendanceLocation.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CauHinhChamCong;

class CheckAttendanceLocation
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $ip = $request->ip();
        $mac = $request->header('X-MAC-Address');
        $wifi = $request->header('X-WiFi-SSID');

        // Kiểm tra IP cho phép
        $ipAllowed = CauHinhChamCong::isIPAllowed($ip);
        $wifiAllowed = CauHinhChamCong::isWiFiAllowed($wifi);
        $macAllowed = CauHinhChamCong::isMACAllowed($mac);

        \Log::info('CheckAttendanceLocation', [
            'user' => $user->email ?? 'guest',
            'ip' => $ip,
            'ip_allowed' => $ipAllowed,
            'wifi' => $wifi,
            'wifi_allowed' => $wifiAllowed,
        ]);

        if (!$ipAllowed && !$wifiAllowed && !$macAllowed) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vị trí chấm công không hợp lệ. Bạn phải ở trong công ty và kết nối WiFi công ty để chấm công.'
                ], 403);
            }
            
            return redirect()->back()->with('error', 'Vị trí chấm công không hợp lệ. Bạn phải ở trong công ty và kết nối WiFi công ty để chấm công.');
        }

        $method = 'manual';
        if ($ipAllowed) $method = 'ip';
        if ($wifiAllowed) $method = 'wifi';
        if ($macAllowed) $method = 'mac';

        $request->merge(['phuong_thuc_cham_cong' => $method]);

        return $next($request);
    }
}