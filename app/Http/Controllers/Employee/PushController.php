<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use App\Services\ThongBaoDaKenhService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushController extends Controller
{
    public function vapid(ThongBaoDaKenhService $service)
    {
        return response()->json(['publicKey' => $service->vapidPublicKey()]);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string|max:1000',
            'keys.p256dh' => 'nullable|string',
            'keys.auth' => 'nullable|string',
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'nguoi_dung_id' => Auth::id(),
                'p256dh' => $data['keys']['p256dh'] ?? null,
                'auth' => $data['keys']['auth'] ?? null,
            ]
        );

        return response()->json(['ok' => true]);
    }
}
