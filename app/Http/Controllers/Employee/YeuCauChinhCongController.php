<?php
// app/Http/Controllers/Employee/YeuCauChinhCongController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\YeuCauDieuChinhCong;
use App\Models\ChamCong;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class YeuCauChinhCongController extends Controller
{
    /**
     * Danh sách yêu cầu chỉnh công
     */
    public function index()
    {
        $user = Auth::user();
        
        $yeuCaus = YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $thongKe = [
            'tong' => YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)->count(),
            'cho_duyet' => YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)->where('trang_thai', 'tu_choi')->count(),
        ];

        return view('employee.yeu-cau-chinh-cong.index', compact('yeuCaus', 'thongKe'));
    }

    /**
     * Form tạo yêu cầu mới
     */
    public function create()
    {
        return view('employee.yeu-cau-chinh-cong.create');
    }

    /**
     * Lưu yêu cầu mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'ngay' => 'required|date|before_or_equal:today',
            'gio_vao' => 'nullable|date_format:H:i',
            'gio_ra' => 'nullable|date_format:H:i|after:gio_vao',
            'ly_do' => 'required|string|min:10',
            'tep_dinh_kem' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();

        // Kiểm tra xem ngày này đã có yêu cầu chưa
        $existing = YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)
            ->where('ngay', $request->ngay)
            ->where('trang_thai', 'cho_duyet')
            ->first();

        if ($existing) {
            return back()->withErrors(['ngay' => 'Bạn đã có yêu cầu chỉnh công cho ngày này đang chờ duyệt!']);
        }

        // Kiểm tra xem ngày này đã có chấm công chưa
        $chamCong = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $request->ngay)
            ->first();

        if (!$chamCong) {
            return back()->withErrors(['ngay' => 'Ngày này chưa có dữ liệu chấm công!']);
        }

        // Xử lý file đính kèm
        $tepDinhKem = null;
        if ($request->hasFile('tep_dinh_kem')) {
            $tepDinhKem = $request->file('tep_dinh_kem')->store('yeu-cau-chinh-cong', 'public');
        }

        DB::beginTransaction();
        try {
            YeuCauDieuChinhCong::create([
                'nguoi_dung_id' => $user->id,
                'ngay' => $request->ngay,
                'gio_vao' => $request->gio_vao,
                'gio_ra' => $request->gio_ra,
                'ly_do' => $request->ly_do,
                'tep_dinh_kem' => $tepDinhKem,
                'trang_thai' => 'cho_duyet',
            ]);

            DB::commit();

            return redirect()->route('employee.yeu-cau-chinh-cong.index')
                ->with('success', '✅ Đã gửi yêu cầu chỉnh công thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Yeu cau chinh cong error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Chi tiết yêu cầu
     */
    public function show($id)
    {
        $user = Auth::user();
        $yeuCau = YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)
            ->findOrFail($id);

        return view('employee.yeu-cau-chinh-cong.show', compact('yeuCau'));
    }

    /**
     * Hủy yêu cầu
     */
    public function huy($id)
    {
        $user = Auth::user();
        $yeuCau = YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $yeuCau->update([
            'trang_thai' => 'tu_choi'
        ]);

        return redirect()->route('employee.yeu-cau-chinh-cong.index')
            ->with('success', '🚫 Đã hủy yêu cầu chỉnh công!');
    }

    /**
     * Download file đính kèm
     */
    public function download($id)
    {
        $user = Auth::user();
        $yeuCau = YeuCauDieuChinhCong::where('nguoi_dung_id', $user->id)
            ->findOrFail($id);

        if (!$yeuCau->tep_dinh_kem || !Storage::disk('public')->exists($yeuCau->tep_dinh_kem)) {
            abort(404, 'File không tồn tại');
        }

        return Storage::disk('public')->download($yeuCau->tep_dinh_kem);
    }
}