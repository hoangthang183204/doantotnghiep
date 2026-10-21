<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with('nguoiDung.hoSo')
            ->when($request->filled('q'), function ($q) use ($request) {
                $tu = $request->q;
                $q->where(function ($w) use ($tu) {
                    $w->where('route', 'like', "%{$tu}%")
                        ->orWhere('mo_ta', 'like', "%{$tu}%")
                        ->orWhere('hanh_dong', 'like', "%{$tu}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.audit-log.index', compact('logs'));
    }
}
