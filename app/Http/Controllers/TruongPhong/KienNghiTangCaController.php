<?php

namespace App\Http\Controllers\TruongPhong;

use App\Http\Controllers\Controller;
use App\Models\DangKyTangCa;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Services\NotificationService;
use App\Helpers\OvertimeHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KienNghiTangCaController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Lấy danh sách nhân viên trong phòng ban
     */
    private function getNhanVienIdsTrongPhong()
    {
        $user = Auth::user();

        $phongBan = PhongBan::where('truong_phong_id', $user->id)->first();
        if (!$phongBan) {
            $phongBan = PhongBan::find($user->phong_ban_id);
        }

        if (!$phongBan) {
            return [];
        }

        return NguoiDung::where('phong_ban_id', $phongBan->id)
            ->where('trang_thai', 1)
            ->where('id', '!=', $user->id)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Danh sách kiến nghị tăng ca
     */
    public function index(Request $request)
    {
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $query = DangKyTangCa::with(['nguoi_dung.hoSo', 'nguoi_duyet.hoSo'])
            ->whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereNull('ngay_tang_ca');

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('tu_ngay')) {
            $query->whereDate('created_at', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('created_at', '<=', $request->den_ngay);
        }

        $kienNghis = $query->orderBy('created_at', 'desc')->paginate(15);

        $thongKe = [
            'tong' => (clone $query)->count(),
            'cho_xu_ly' => (clone $query)->where('trang_thai', 'cho_duyet')->count(),
            'da_dong_y' => (clone $query)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => (clone $query)->where('trang_thai', 'tu_choi')->count(),
        ];

        $phongBan = PhongBan::where('truong_phong_id', Auth::id())->first();
        if (!$phongBan) {
            $phongBan = PhongBan::find(Auth::user()->phong_ban_id);
        }

        return view('truong-phong.kien-nghi-tang-ca.index', compact('kienNghis', 'thongKe', 'phongBan'));
    }

    /**
     * Chi tiết kiến nghị
     */
    public function show($id)
    {
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $kienNghi = DangKyTangCa::with(['nguoi_dung.hoSo', 'nguoi_dung.phongBan', 'nguoi_duyet.hoSo'])
            ->whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereNull('ngay_tang_ca')
            ->findOrFail($id);

        return view('truong-phong.kien-nghi-tang-ca.show', compact('kienNghi'));
    }

    /**
     * Đồng ý kiến nghị
     */
    public function dongY($id)
    {
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $kienNghi = DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereNull('ngay_tang_ca')
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $kienNghi->update([
            'trang_thai' => 'da_duyet',
            'nguoi_duyet_id' => Auth::id(),
            'thoi_gian_duyet' => now(),
        ]);

        $this->notificationService->notifyKienNghiTangCa($kienNghi, $kienNghi->nguoi_dung, 'approved');

        return redirect()->route('truong-phong.kien-nghi-tang-ca.create-don', $kienNghi->id)
            ->with('success', '✅ Đã đồng ý kiến nghị. Vui lòng tạo đơn tăng ca!');
    }

    /**
     * Form tạo đơn tăng ca từ kiến nghị
     */
    public function createDon($id)
    {
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $kienNghi = DangKyTangCa::with(['nguoi_dung.hoSo', 'nguoi_dung.phongBan'])
            ->whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereNull('ngay_tang_ca')
            ->where('trang_thai', 'da_duyet')
            ->findOrFail($id);

        return view('truong-phong.kien-nghi-tang-ca.create-don', compact('kienNghi'));
    }

    /**
     * Lưu đơn tăng ca từ kiến nghị
     */
    public function storeDon(Request $request, $id)
    {
        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $kienNghi = DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereNull('ngay_tang_ca')
            ->where('trang_thai', 'da_duyet')
            ->findOrFail($id);

        $request->validate([
            'ngay_tang_ca' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
            'loai_tang_ca' => 'required|in:ngay_thuong,ngay_nghi,le_tet', // ⭐ THÊM le_tet
            'ly_do_tang_ca' => 'required|string|min:10|max:500',
        ]);

        $user = Auth::user();
        $ngayTangCa = $request->ngay_tang_ca;

        $gioBatDau = Carbon::parse($request->gio_bat_dau);
        $gioKetThuc = Carbon::parse($request->gio_ket_thuc);
        $soGioTangCa = $gioBatDau->diffInHours($gioKetThuc);

        $kiemTra = OvertimeHelper::kiemTraGioiHan($kienNghi->nguoi_dung_id, $ngayTangCa, $soGioTangCa);
        if (!$kiemTra['valid']) {
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => $kiemTra['message']]);
        }

        DB::beginTransaction();
        try {
            // Tạo đơn tăng ca
            $donTangCa = DangKyTangCa::create([
                'nguoi_dung_id' => $kienNghi->nguoi_dung_id,
                'nguoi_tao_id' => $user->id,
                'loai_tao' => 'truong_phong',
                'ngay_tang_ca' => $ngayTangCa,
                'gio_bat_dau' => $request->gio_bat_dau,
                'gio_ket_thuc' => $request->gio_ket_thuc,
                'so_gio_tang_ca' => $soGioTangCa,
                'loai_tang_ca' => $request->loai_tang_ca,
                'ly_do_tang_ca' => $request->ly_do_tang_ca,
                'trang_thai' => 'da_duyet',
                'nguoi_duyet_id' => $user->id,
                'thoi_gian_duyet' => now(),
            ]);

            // Cập nhật kiến nghị - chuyển thành đơn tăng ca
            $kienNghi->update([
                'ngay_tang_ca' => $ngayTangCa,
                'gio_bat_dau' => $request->gio_bat_dau,
                'gio_ket_thuc' => $request->gio_ket_thuc,
                'so_gio_tang_ca' => $soGioTangCa,
                'loai_tang_ca' => $request->loai_tang_ca,
                'don_tang_ca_id' => $donTangCa->id,
            ]);

            $this->notificationService->notifyOvertime($donTangCa, 'created_by_manager');

            DB::commit();

            return redirect()->route('truong-phong.kien-nghi-tang-ca.index')
                ->with('success', '✅ Đã tạo đơn tăng ca cho nhân viên!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Store don tang ca error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Từ chối kiến nghị
     */
    public function tuChoi(Request $request, $id)
    {
        $request->validate([
            'ly_do_tu_choi' => 'required|string|max:500',
        ]);

        $nhanVienIds = $this->getNhanVienIdsTrongPhong();

        $kienNghi = DangKyTangCa::whereIn('nguoi_dung_id', $nhanVienIds)
            ->whereNull('ngay_tang_ca')
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $kienNghi->update([
            'trang_thai' => 'tu_choi',
            'nguoi_duyet_id' => Auth::id(),
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
            'thoi_gian_duyet' => now(),
        ]);

        $this->notificationService->notifyKienNghiTangCa($kienNghi, $kienNghi->nguoi_dung, 'rejected');

        return redirect()->route('truong-phong.kien-nghi-tang-ca.index')
            ->with('success', '✅ Đã từ chối kiến nghị tăng ca!');
    }
}
