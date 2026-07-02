<?php
// app/Http/Controllers/Employee/DonNghiController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\DonXinNghi;
use App\Models\LoaiNghiPhep;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DonNghiController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    private function getSoDuNghiPhep($userId)
    {
        $namHienTai = Carbon::now()->year;

        // Lấy cấu hình phép từ bảng so_du_phep
        $soDuPhep = \App\Models\SoDuPhep::where('nguoi_dung_id', $userId)
            ->where('nam', $namHienTai)
            ->first();

        // Nếu dữ liệu năm nay chưa được khởi tạo, tự động tạo nhanh bản ghi mẫu để tránh crash giao diện
        if (!$soDuPhep) {
            $soDuPhep = \App\Models\SoDuPhep::create([
                'nguoi_dung_id' => $userId,
                'nam' => $namHienTai,
                'phep_nam_moi' => 12.0,
                'phep_cu_chuyen_sang' => 0.0,
                'phep_da_dung' => 0.0
            ]);
        }

        $tongPhepDuocHuong = $soDuPhep->phep_nam_moi + $soDuPhep->phep_cu_chuyen_sang;
        $soNgayDaNghi = $soDuPhep->phep_da_dung;
        $soDuConLai = max(0, $tongPhepDuocHuong - $soNgayDaNghi);

        // Bật trạng thái cảnh báo nếu số dư còn dưới hoặc bằng 3 ngày
        $canhBaoSapHet = $soDuConLai <= 3.0;

        return [
            'so_ngay_phep_nam' => $tongPhepDuocHuong,
            'phep_nam_moi' => $soDuPhep->phep_nam_moi,
            'phep_cu_chuyen_sang' => $soDuPhep->phep_cu_chuyen_sang,
            'so_ngay_da_nghi' => $soNgayDaNghi,
            'so_du_con_lai' => $soDuConLai,
            'canh_bao_sap_het' => $canhBaoSapHet,
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = DonXinNghi::where('nguoi_dung_id', $user->id);

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }
        if ($request->filled('tu_ngay')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->tu_ngay);
        }
        if ($request->filled('den_ngay')) {
            $query->whereDate('ngay_bat_dau', '<=', $request->den_ngay);
        }

        $danhSachDon = $query->orderBy('created_at', 'desc')->paginate(10);

        $thongKe = [
            'tong' => DonXinNghi::where('nguoi_dung_id', $user->id)->count(),
            'cho_duyet' => DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'da_duyet')->count(),
            'tu_choi' => DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'tu_choi')->count(),
            'huy_bo' => DonXinNghi::where('nguoi_dung_id', $user->id)->where('trang_thai', 'huy_bo')->count(),
        ];

        $soDu = $this->getSoDuNghiPhep($user->id);

        return view('employee.don-nghi.index', compact('danhSachDon', 'thongKe', 'soDu'));
    }

    public function create()
    {
        $loaiNghiPheps = LoaiNghiPhep::where('trang_thai', 1)->get();
        $user = Auth::user();
        $soDu = $this->getSoDuNghiPhep($user->id);

        return view('employee.don-nghi.create', compact('loaiNghiPheps', 'soDu'));
    }

    public function store(Request $request)
    {
        Log::info('🚀=== STORE FUNCTION CALLED ===🚀');

        $request->validate([
            'loai_nghi_id' => 'required|exists:loai_nghi_phep,id',
            'ngay_bat_dau' => 'required|date|after_or_equal:today',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'so_ngay_nghi' => 'required|numeric|min:0.5',
            'ly_do' => 'required|string|min:10',
            'ghi_chu' => 'nullable|string',
        ]);

        $user = Auth::user();
        Log::info('📝 User ID: ' . $user->id . ' - ' . $user->email);

        $soDu = $this->getSoDuNghiPhep($user->id);

        if ($request->so_ngay_nghi > $soDu['so_du_con_lai']) {
            return back()->withErrors(['so_ngay_nghi' => 'Số ngày nghỉ vượt quá số dư nghỉ phép hiện tại (' . $soDu['so_du_con_lai'] . ' ngày)']);
        }

        $exists = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->where(function ($q) use ($request) {
                $q->whereBetween('ngay_bat_dau', [$request->ngay_bat_dau, $request->ngay_ket_thuc])
                    ->orWhereBetween('ngay_ket_thuc', [$request->ngay_bat_dau, $request->ngay_ket_thuc]);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['ngay_bat_dau' => 'Bạn đã có đơn nghỉ trùng khoảng thời gian này đang chờ duyệt!']);
        }

        $latestDon = DonXinNghi::orderBy('id', 'desc')->first();
        $nextId = $latestDon ? $latestDon->id + 1 : 1;
        $maDonNghi = 'DN' . date('Ymd') . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            $donNghi = DonXinNghi::create([
                'ma_don_nghi' => $maDonNghi,
                'nguoi_dung_id' => $user->id,
                'loai_nghi_phep_id' => $request->loai_nghi_id,
                'ngay_bat_dau' => $request->ngay_bat_dau,
                'ngay_ket_thuc' => $request->ngay_ket_thuc,
                'so_ngay_nghi' => $request->so_ngay_nghi,
                'ly_do' => $request->ly_do,
                'ghi_chu' => $request->ghi_chu,
                'trang_thai' => 'cho_duyet',
                'cap_duyet_hien_tai' => 1,
            ]);

            Log::info('📝 DonNghi created: ' . $donNghi->id);

            // ⭐⭐ SỬA LẠI: GỬI THÔNG BÁO CHO ADMIN + TRƯỞNG PHÒNG ⭐⭐
            try {
                Log::info('📝 Bắt đầu gửi thông báo...');

                // Lấy ADMIN + TRƯỞNG PHÒNG
                $recipients = \App\Models\NguoiDung::whereHas('vaiTros', function ($q) {
                    $q->whereIn('name', ['admin', 'Super Admin', 'truong_phong']);
                })->get();

                Log::info('📝 Số người nhận: ' . $recipients->count());

                foreach ($recipients as $recipient) {
                    $recipient->notify(new \App\Notifications\LeaveRequestNotification($donNghi, 'created'));
                    Log::info('📝 Đã gửi thông báo đến: ' . $recipient->email);
                }

                Log::info('📝 Gửi thông báo thành công!');
            } catch (\Exception $e) {
                Log::error('❌ Lỗi gửi thông báo: ' . $e->getMessage());
                // Không throw exception để vẫn tạo được đơn
            }

            DB::commit();

            return redirect()->route('employee.don-nghi.index')
                ->with('success', '✅ Đã gửi đơn xin nghỉ phép thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Don nghi error: ' . $e->getMessage());
            Log::error('❌ Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $donNghi = DonXinNghi::with(['loaiNghiPhep', 'nguoiDung.hoSo'])
            ->where('nguoi_dung_id', $user->id)
            ->findOrFail($id);

        return view('employee.don-nghi.show', compact('donNghi'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $donNghi = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $loaiNghiPheps = LoaiNghiPhep::where('trang_thai', 1)->get();
        $soDu = $this->getSoDuNghiPhep($user->id);

        return view('employee.don-nghi.edit', compact('donNghi', 'loaiNghiPheps', 'soDu'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $donNghi = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $request->validate([
            'loai_nghi_id' => 'required|exists:loai_nghi_phep,id',
            'ngay_bat_dau' => 'required|date|after_or_equal:today',
            'ngay_ket_thuc' => 'required|date|after_or_equal:ngay_bat_dau',
            'so_ngay_nghi' => 'required|numeric|min:0.5',
            'ly_do' => 'required|string|min:10',
            'ghi_chu' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $donNghi->update([
                'loai_nghi_phep_id' => $request->loai_nghi_id,
                'ngay_bat_dau' => $request->ngay_bat_dau,
                'ngay_ket_thuc' => $request->ngay_ket_thuc,
                'so_ngay_nghi' => $request->so_ngay_nghi,
                'ly_do' => $request->ly_do,
                'ghi_chu' => $request->ghi_chu,
            ]);

            DB::commit();

            return redirect()->route('employee.don-nghi.index')
                ->with('success', '✅ Đã cập nhật đơn xin nghỉ phép!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Don nghi update error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function huy($id)
    {
        $user = Auth::user();
        $donNghi = DonXinNghi::where('nguoi_dung_id', $user->id)
            ->where('trang_thai', 'cho_duyet')
            ->findOrFail($id);

        $donNghi->update(['trang_thai' => 'huy_bo']);

        return redirect()->route('employee.don-nghi.index')
            ->with('success', '🚫 Đã hủy đơn xin nghỉ phép!');
    }
}
