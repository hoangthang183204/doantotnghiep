<?php
// app/Http/Controllers/Employee/ChamCongController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\CauHinhChamCong;
use App\Models\GioLamViec;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChamCongController extends Controller
{
    /**
     * Trang chấm công nhân viên
     */
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today('Asia/Ho_Chi_Minh');

        $chamCongHomNay = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();

        $lichSu = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', '>=', Carbon::now('Asia/Ho_Chi_Minh')->subDays(7))
            ->orderBy('ngay_cham_cong', 'desc')
            ->get();

        $gioLamViec = GioLamViec::first();
        $ipChoPhep = CauHinhChamCong::where('trang_thai', 1)->get();
        $dsIP = CauHinhChamCong::getIPsAllowed();
        $dsWiFi = CauHinhChamCong::getWiFisAllowed();

        $currentIP = request()->ip();
        $currentWiFi = request()->header('X-WiFi-SSID');
        $isValidLocation = CauHinhChamCong::isValidLocation($currentIP, $currentWiFi);

        return view('employee.cham-cong.index', compact(
            'chamCongHomNay',
            'lichSu',
            'gioLamViec',
            'ipChoPhep',
            'dsIP',
            'dsWiFi',
            'currentIP',
            'currentWiFi',
            'isValidLocation'
        ));
    }

    /**
     * Check-in - SỬA: DÙNG THỜI GIAN TỪ CLIENT
     */
    // app/Http/Controllers/Employee/ChamCongController.php

    // app/Http/Controllers/Employee/ChamCongController.php

    public function checkIn(Request $request)
    {
        try {
            $user = auth()->user();
            $today = Carbon::today('Asia/Ho_Chi_Minh');

            // Kiểm tra đã check-in chưa
            $existing = ChamCong::where('nguoi_dung_id', $user->id)
                ->whereDate('ngay_cham_cong', $today)
                ->first();

            if ($existing && $existing->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã check-in hôm nay rồi!'
                ], 400);
            }

            // Kiểm tra vị trí
            $ip = $request->ip();
            $wifi = $request->header('X-WiFi-SSID');
            $mac = $request->header('X-MAC-Address');

            $ipAllowed = CauHinhChamCong::isIPAllowed($ip);
            $wifiAllowed = CauHinhChamCong::isWiFiAllowed($wifi);
            $macAllowed = CauHinhChamCong::isMACAllowed($mac);

            if (!$ipAllowed && !$wifiAllowed && !$macAllowed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vị trí chấm công không hợp lệ!'
                ], 403);
            }

            // Lấy thời gian hiện tại
            $now = Carbon::now('Asia/Ho_Chi_Minh');
            $gioVaoStr = $now->format('H:i:s');

            // ===== LOGIC XÁC ĐỊNH TRẠNG THÁI =====
            $gioLamViec = GioLamViec::first();

            // Mặc định là đúng giờ
            $trangThai = 'dung_gio';
            $phutDiMuon = 0;

            if ($gioLamViec) {
                // Giờ chuẩn 08:30
                $gioChuan = '08:30:00';
                $gioChuanCarbon = Carbon::parse($gioChuan, 'Asia/Ho_Chi_Minh');

                // So sánh thời gian check-in với 08:30
                if ($now->lt($gioChuanCarbon)) {
                    // Check-in TRƯỚC 08:30 -> Đến sớm
                    $trangThai = 'den_som';
                    $phutDiMuon = 0;
                } else {
                    // Check-in SAU 08:30 -> Tính số phút trễ
                    $phutDiMuon = $gioChuanCarbon->diffInMinutes($now);

                    // Nếu trễ quá 15 phút -> Đi muộn
                    if ($phutDiMuon > ($gioLamViec->so_phut_cho_phep_di_tre ?? 15)) {
                        $trangThai = 'di_muon';
                    } else {
                        // Trễ trong khoảng cho phép (0-15 phút) -> Đúng giờ
                        $trangThai = 'dung_gio';
                        $phutDiMuon = 0;
                    }
                }
            }

            // Log để debug
            \Log::info('Check-in result', [
                'gio_vao' => $gioVaoStr,
                'trang_thai' => $trangThai,
                'phut_di_muon' => $phutDiMuon,
            ]);

            $method = 'manual';
            if ($ipAllowed) $method = 'ip';
            if ($wifiAllowed) $method = 'wifi';
            if ($macAllowed) $method = 'mac';

            DB::beginTransaction();

            $chamCong = ChamCong::updateOrCreate(
                [
                    'nguoi_dung_id' => $user->id,
                    'ngay_cham_cong' => $today
                ],
                [
                    'gio_vao' => $gioVaoStr,
                    'phut_di_muon' => $phutDiMuon,
                    'trang_thai' => $trangThai,
                    'dia_chi_ip' => $ip,
                    'ten_wifi' => $wifi,
                    'dia_chi_mac' => $mac,
                    'ten_thiet_bi' => $request->header('User-Agent'),
                    'phuong_thuc_cham_cong' => $method,
                    'trang_thai_duyet' => 0,
                    'so_gio_lam' => 0,
                    'so_cong' => 0,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công lúc ' . $gioVaoStr,
                'trang_thai' => $trangThai,
                'phut_di_muon' => $phutDiMuon,
                'phuong_thuc' => $method,
                'gio_vao' => $gioVaoStr
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Check-in error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check-out - SỬA: DÙNG THỜI GIAN TỪ CLIENT
     */
    public function checkOut(Request $request)
    {
        try {
            $user = auth()->user();
            $today = Carbon::today('Asia/Ho_Chi_Minh');

            $chamCong = ChamCong::where('nguoi_dung_id', $user->id)
                ->whereDate('ngay_cham_cong', $today)
                ->first();

            if (!$chamCong || !$chamCong->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa check-in hôm nay!'
                ], 400);
            }

            if ($chamCong->gio_ra) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã check-out hôm nay rồi!'
                ], 400);
            }

            // Kiểm tra vị trí
            $ip = $request->ip();
            $wifi = $request->header('X-WiFi-SSID');
            $mac = $request->header('X-MAC-Address');

            $ipAllowed = CauHinhChamCong::isIPAllowed($ip);
            $wifiAllowed = CauHinhChamCong::isWiFiAllowed($wifi);
            $macAllowed = CauHinhChamCong::isMACAllowed($mac);

            if (!$ipAllowed && !$wifiAllowed && !$macAllowed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vị trí chấm công không hợp lệ!'
                ], 403);
            }

            // ===== SỬA: LẤY THỜI GIAN TỪ CLIENT =====
            $clientTime = $request->input('client_time');

            if ($clientTime) {
                try {
                    $now = Carbon::parse($clientTime)->setTimezone('Asia/Ho_Chi_Minh');
                } catch (\Exception $e) {
                    $now = Carbon::now('Asia/Ho_Chi_Minh');
                }
            } else {
                $now = Carbon::now('Asia/Ho_Chi_Minh');
            }

            $gioRaStr = $now->format('H:i:s');

            $gioLamViec = GioLamViec::first();

            // Giờ chuẩn bắt đầu làm việc (8h30)
            $gioBatDauLam = Carbon::parse($gioLamViec->gio_bat_dau ?? '08:30:00', 'Asia/Ho_Chi_Minh');

            // Kiểm tra về sớm
            $phutVeSom = 0;
            if ($gioLamViec) {
                $gioKetThuc = Carbon::parse($gioLamViec->gio_ket_thuc, 'Asia/Ho_Chi_Minh');
                $phutVeSom = $gioKetThuc->diffInMinutes($now, false);

                if ($phutVeSom > ($gioLamViec->so_phut_cho_phep_ve_som ?? 15)) {
                    if ($chamCong->trang_thai != 'di_muon') {
                        $chamCong->trang_thai = 've_som';
                    }
                }
            }

            // ===== TÍNH SỐ GIỜ LÀM =====
            $soPhutLam = $gioBatDauLam->diffInMinutes($now, false);

            $phutDiMuon = $chamCong->phut_di_muon ?? 0;
            $soPhutLamThucTe = $soPhutLam - $phutDiMuon;
            $soPhutLamThucTe = max(0, $soPhutLamThucTe);

            $soGioLam = round($soPhutLamThucTe / 60, 2);
            $soCong = round($soGioLam / 8, 2);

            // ===== TÍNH GIỜ TĂNG CA =====
            $gioTangCa = 0;
            if ($gioLamViec && $gioLamViec->gio_ket_thuc) {
                $gioKetThuc = Carbon::parse($gioLamViec->gio_ket_thuc, 'Asia/Ho_Chi_Minh');
                if ($now->gt($gioKetThuc)) {
                    $gioTangCa = round($gioKetThuc->diffInHours($now), 1);
                }
            }

            DB::beginTransaction();

            $chamCong->update([
                'gio_ra' => $gioRaStr,
                'so_gio_lam' => $soGioLam,
                'so_cong' => $soCong,
                'phut_ve_som' => max(0, $phutVeSom),
                'gio_tang_ca' => $gioTangCa,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-out thành công lúc ' . $gioRaStr,
                'so_gio_lam' => $soGioLam,
                'so_cong' => $soCong,
                'gio_tang_ca' => $gioTangCa,
                'gio_ra' => $gioRaStr,
                'phut_di_muon' => $phutDiMuon,
                'so_phut_lam_thuc_te' => $soPhutLamThucTe
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Check-out error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lịch sử chấm công
     */
    public function history(Request $request)
    {
        $user = auth()->user();

        $query = ChamCong::where('nguoi_dung_id', $user->id);

        if ($request->filled('thang')) {
            $query->whereMonth('ngay_cham_cong', $request->thang);
        }

        if ($request->filled('nam')) {
            $query->whereYear('ngay_cham_cong', $request->nam);
        }

        $lichSu = $query->orderBy('ngay_cham_cong', 'desc')->paginate(20);

        $thangHienTai = Carbon::now('Asia/Ho_Chi_Minh')->month;
        $namHienTai = Carbon::now('Asia/Ho_Chi_Minh')->year;

        $thongKe = [
            'tong_ngay_cong' => ChamCong::where('nguoi_dung_id', $user->id)
                ->whereMonth('ngay_cham_cong', $thangHienTai)
                ->whereYear('ngay_cham_cong', $namHienTai)
                ->where('trang_thai', 'dung_gio')
                ->count(),
            'tong_di_muon' => ChamCong::where('nguoi_dung_id', $user->id)
                ->whereMonth('ngay_cham_cong', $thangHienTai)
                ->whereYear('ngay_cham_cong', $namHienTai)
                ->where('trang_thai', 'di_muon')
                ->count(),
            'tong_ve_som' => ChamCong::where('nguoi_dung_id', $user->id)
                ->whereMonth('ngay_cham_cong', $thangHienTai)
                ->whereYear('ngay_cham_cong', $namHienTai)
                ->where('trang_thai', 've_som')
                ->count(),
            'tong_gio_lam' => ChamCong::where('nguoi_dung_id', $user->id)
                ->whereMonth('ngay_cham_cong', $thangHienTai)
                ->whereYear('ngay_cham_cong', $namHienTai)
                ->sum('so_gio_lam'),
            'tong_tang_ca' => ChamCong::where('nguoi_dung_id', $user->id)
                ->whereMonth('ngay_cham_cong', $thangHienTai)
                ->whereYear('ngay_cham_cong', $namHienTai)
                ->sum('gio_tang_ca'),
        ];

        return view('employee.cham-cong.history', compact('lichSu', 'thongKe'));
    }
}
