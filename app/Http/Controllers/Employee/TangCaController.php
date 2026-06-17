<?php
// app/Http/Controllers/Employee/TangCaController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TangCaController extends Controller
{
    /**
     * Danh sách đơn tăng ca của tôi
     */
    public function index()
    {
        $user = Auth::user();
        
        $donTangCa = DangKyTangCa::where('nguoi_dung_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $thongKe = [
            'tong' => DangKyTangCa::where('nguoi_dung_id', $user->id)->count(),
            'cho_duyet' => DangKyTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => DangKyTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => DangKyTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'tu_choi')->count(),
        ];

        return view('employee.tang-ca.index', compact('donTangCa', 'thongKe'));
    }

    /**
     * Form tạo đơn tăng ca mới
     */
    public function create()
    {
        return view('employee.tang-ca.create');
    }

    /**
     * Lưu đơn tăng ca mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ngay_tang_ca' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required',
            'gio_ket_thuc' => 'required|after:gio_bat_dau',
            'loai_tang_ca' => 'required|in:ngay_thuong,ngay_nghi,le_tet',
            'ly_do_tang_ca' => 'required|string|min:10',
        ]);

        $user = Auth::user();

        // Tính số giờ tăng ca
        $gioBatDau = Carbon::parse($request->gio_bat_dau);
        $gioKetThuc = Carbon::parse($request->gio_ket_thuc);
        $soGioTangCa = $gioBatDau->diffInHours($gioKetThuc);

        if ($soGioTangCa <= 0) {
            return back()->withErrors(['gio_ket_thuc' => 'Giờ kết thúc phải sau giờ bắt đầu']);
        }

        DB::beginTransaction();
        try {
            DangKyTangCa::create([
                'nguoi_dung_id' => $user->id,
                'ngay_tang_ca' => $request->ngay_tang_ca,
                'gio_bat_dau' => $request->gio_bat_dau,
                'gio_ket_thuc' => $request->gio_ket_thuc,
                'so_gio_tang_ca' => $soGioTangCa,
                'loai_tang_ca' => $request->loai_tang_ca,
                'ly_do_tang_ca' => $request->ly_do_tang_ca,
                'trang_thai' => 'cho_duyet',
            ]);

            DB::commit();

            return redirect()->route('employee.tang-ca.index')
                ->with('success', '✅ Đã gửi đơn xin tăng ca thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Tang ca error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Chi tiết đơn tăng ca
     */
    public function show($id)
    {
        $user = Auth::user();
        $donTangCa = DangKyTangCa::where('nguoi_dung_id', $user->id)
            ->findOrFail($id);

        return view('employee.tang-ca.show', compact('donTangCa'));
    }

    /**
     * Hủy đơn tăng ca
     */
    public function huy($id)
    {
        $user = Auth::user();
        $donTangCa = DangKyTangCa::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $donTangCa->update([
            'trang_thai' => 'huy'
        ]);

        return redirect()->route('employee.tang-ca.index')
            ->with('success', '🚫 Đã hủy đơn xin tăng ca!');
    }
}