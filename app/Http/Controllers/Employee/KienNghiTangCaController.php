<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\KienNghiTangCa;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Services\NotificationService;
use Carbon\Carbon;
use App\Helpers\OvertimeHelper;
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
     * Danh sách kiến nghị tăng ca của nhân viên
     */
    public function index()
    {
        $user = Auth::user();

        $kienNghis = KienNghiTangCa::where('nguoi_dung_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $thongKe = [
            'tong' => KienNghiTangCa::where('nguoi_dung_id', $user->id)->count(),
            'cho_xu_ly' => KienNghiTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'cho_xu_ly')->count(),
            'da_dong_y' => KienNghiTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'da_dong_y')->count(),
            'tu_choi' => KienNghiTangCa::where('nguoi_dung_id', $user->id)->where('trang_thai', 'tu_choi')->count(),
        ];

        return view('employee.kien-nghi-tang-ca.index', compact('kienNghis', 'thongKe'));
    }

    /**
     * Form gửi kiến nghị tăng ca
     */
    public function create()
    {
        return view('employee.kien-nghi-tang-ca.create');
    }

    /**
     * Lưu kiến nghị tăng ca
     */
    public function store(Request $request)
    {
        $request->validate([
            'ngay_de_nghi' => 'required|date|after_or_equal:today',
            'gio_bat_dau' => 'required|date_format:H:i',
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau',
            'loai_tang_ca' => 'required|in:ngay_thuong,ngay_nghi',
            'ly_do' => 'required|string|min:10|max:500',
            'noi_dung_cong_viec' => 'nullable|string|max:500',
            'ghi_chu' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Tính số giờ
        $gioBatDau = Carbon::parse($request->gio_bat_dau);
        $gioKetThuc = Carbon::parse($request->gio_ket_thuc);
        $soGio = $gioBatDau->diffInHours($gioKetThuc);

        // Kiểm tra giới hạn (chỉ cảnh báo, không chặn)
        $kiemTra = OvertimeHelper::kiemTraGioiHan($user->id, $request->ngay_de_nghi, $soGio);
        if (!$kiemTra['valid']) {
            return back()
                ->withInput()
                ->withErrors(['gio_bat_dau' => $kiemTra['message']]);
        }

        // Tìm trưởng phòng
        $truongPhong = $this->getTruongPhong($user);
        if (!$truongPhong) {
            return back()
                ->withInput()
                ->with('error', '❌ Không tìm thấy trưởng phòng để gửi kiến nghị!');
        }

        DB::beginTransaction();
        try {
            $kienNghi = KienNghiTangCa::create([
                'nguoi_dung_id' => $user->id,
                'ngay_de_nghi' => $request->ngay_de_nghi,
                'gio_bat_dau' => $request->gio_bat_dau,
                'gio_ket_thuc' => $request->gio_ket_thuc,
                'so_gio' => $soGio,
                'loai_tang_ca' => $request->loai_tang_ca,
                'ly_do' => $request->ly_do,
                'noi_dung_cong_viec' => $request->noi_dung_cong_viec,
                'ghi_chu' => $request->ghi_chu,
                'trang_thai' => 'cho_xu_ly',
            ]);

            // Gửi thông báo cho trưởng phòng
            $this->notificationService->notifyKienNghiTangCa($kienNghi, $truongPhong);

            DB::commit();

            return redirect()->route('employee.kien-nghi-tang-ca.index')
                ->with('success', '✅ Đã gửi kiến nghị tăng ca đến trưởng phòng!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Kien nghi tang ca error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Chi tiết kiến nghị
     */
    public function show($id)
    {
        $user = Auth::user();
        $kienNghi = KienNghiTangCa::with(['nguoi_dung.hoSo', 'nguoi_xu_ly.hoSo', 'don_tang_ca'])
            ->findOrFail($id);

        if ($kienNghi->nguoi_dung_id !== $user->id) {
            abort(403, 'Không có quyền xem kiến nghị này');
        }

        return view('employee.kien-nghi-tang-ca.show', compact('kienNghi'));
    }

    /**
     * Hủy kiến nghị (chỉ khi đang chờ xử lý)
     */
    public function huy($id)
    {
        $user = Auth::user();
        $kienNghi = KienNghiTangCa::findOrFail($id);

        if ($kienNghi->nguoi_dung_id !== $user->id) {
            return back()->with('error', 'Không có quyền hủy kiến nghị này');
        }

        if ($kienNghi->trang_thai !== 'cho_xu_ly') {
            return back()->with('error', 'Chỉ có thể hủy kiến nghị đang chờ xử lý');
        }

        $kienNghi->delete();

        return redirect()->route('employee.kien-nghi-tang-ca.index')
            ->with('success', '✅ Đã hủy kiến nghị tăng ca!');
    }

    /**
     * Lấy trưởng phòng của nhân viên
     */
    private function getTruongPhong($user)
    {
        if ($user->phong_ban_id) {
            $phongBan = PhongBan::find($user->phong_ban_id);
            if ($phongBan && $phongBan->truong_phong_id) {
                return NguoiDung::find($phongBan->truong_phong_id);
            }
        }

        $phongBan = PhongBan::where('truong_phong_id', '!=', null)->first();
        if ($phongBan) {
            return NguoiDung::find($phongBan->truong_phong_id);
        }

        return NguoiDung::whereHas('vaiTros', function ($q) {
            $q->whereIn('name', ['truong_phong', 'quan_ly', 'manager']);
        })->first();
    }
}