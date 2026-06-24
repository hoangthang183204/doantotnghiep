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

        // ✅ THÊM: Xác định trạng thái WiFi
        $wifiStatus = 'unknown'; // unknown, valid, invalid
        $wifiMessage = 'Không xác định';

        if ($currentWiFi) {
            if (in_array($currentWiFi, $dsWiFi)) {
                $wifiStatus = 'valid';
                $wifiMessage = 'Hợp lệ';
            } else {
                $wifiStatus = 'invalid';
                $wifiMessage = 'Không hợp lệ';
            }
        } else {
            // ✅ Thử lấy WiFi từ chấm công hôm nay (nếu có)
            if ($chamCongHomNay && $chamCongHomNay->ten_wifi) {
                $currentWiFi = $chamCongHomNay->ten_wifi;
                // Kiểm tra lại với danh sách WiFi được phép
                if (in_array($currentWiFi, $dsWiFi)) {
                    $wifiStatus = 'valid';
                    $wifiMessage = 'Hợp lệ';
                } else {
                    $wifiStatus = 'invalid';
                    $wifiMessage = 'Không hợp lệ';
                }
            } else {
                $wifiMessage = 'Chưa kết nối';
            }
        }

        // ✅ THÊM: Xác định trạng thái IP
        $ipStatus = 'unknown';
        $ipMessage = 'Không xác định';

        if ($currentIP) {
            if (in_array($currentIP, $dsIP)) {
                $ipStatus = 'valid';
                $ipMessage = 'Hợp lệ';
            } else {
                $ipStatus = 'invalid';
                $ipMessage = 'Không hợp lệ';
            }
        }

        // ✅ THÊM: Lấy phương thức chấm công
        $phuongThucText = 'Chưa chấm công';
        if ($chamCongHomNay) {
            $phuongThucMap = [
                'ip' => '📡 IP',
                'wifi' => '📶 WiFi',
                'mac' => '💻 MAC',
                'manual' => '✍️ Nhập tay',
            ];
            $phuongThucText = $phuongThucMap[$chamCongHomNay->phuong_thuc_cham_cong] ?? $chamCongHomNay->phuong_thuc_cham_cong;
        }

        return view('employee.cham-cong.index', compact(
            'chamCongHomNay',
            'lichSu',
            'gioLamViec',
            'ipChoPhep',
            'dsIP',
            'dsWiFi',
            'currentIP',
            'currentWiFi',
            'isValidLocation',
            'wifiStatus',
            'wifiMessage',
            'ipStatus',
            'ipMessage',
            'phuongThucText'
        ));
    }

    /**
     * Check-in - DÙNG THỜI GIAN TỪ CLIENT
     */
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

            // Lấy thời gian từ client hoặc server
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

            $gioVaoStr = $now->format('H:i:s');

            // ===== LOGIC XÁC ĐỊNH TRẠNG THÁI =====
            $gioLamViec = GioLamViec::first();
            $trangThai = 'dung_gio';
            $phutDiMuon = 0;

            if ($gioLamViec) {
                $gioChuan = '08:30:00';
                $gioChuanCarbon = Carbon::parse($gioChuan, 'Asia/Ho_Chi_Minh');

                if ($now->lt($gioChuanCarbon)) {
                    $trangThai = 'den_som';
                    $phutDiMuon = 0;
                } else {
                    $phutDiMuon = $gioChuanCarbon->diffInMinutes($now);
                    if ($phutDiMuon > ($gioLamViec->so_phut_cho_phep_di_tre ?? 15)) {
                        $trangThai = 'di_muon';
                    } else {
                        $trangThai = 'dung_gio';
                        $phutDiMuon = 0;
                    }
                }
            }

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
                    // ⭐ BỎ TRẠNG THÁI DUYỆT
                    // 'trang_thai_duyet' => 0,
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
     * Check-out - DÙNG THỜI GIAN TỪ CLIENT
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

            // Lấy thời gian từ client
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
            $gioKetThuc = Carbon::parse($gioLamViec->gio_ket_thuc ?? '17:30:00', 'Asia/Ho_Chi_Minh');

            // ===== TÍNH TOÁN =====
            $phutVeSom = 0;
            $trangThai = $chamCong->trang_thai ?? 'dung_gio';

            // Nếu check-out TRƯỚC giờ kết thúc (17:30)
            if ($now->lt($gioKetThuc)) {
                $phutVeSom = $gioKetThuc->diffInMinutes($now);
                $soPhutChoPhep = $gioLamViec->so_phut_cho_phep_ve_som ?? 15;

                if ($phutVeSom > $soPhutChoPhep) {
                    $trangThai = 've_som';
                    $phutVeSom = $phutVeSom - $soPhutChoPhep;
                } else {
                    $phutVeSom = 0;
                }
            } else {
                // Check-out SAU giờ kết thúc
                $phutVeSom = 0;
                if ($now->gt($gioKetThuc)) {
                    $trangThai = 'tang_ca';
                }
            }

            // Tính số giờ làm
            $gioVao = Carbon::parse($chamCong->gio_vao, 'Asia/Ho_Chi_Minh');
            $soPhutLam = $gioVao->diffInMinutes($now);
            $soGioLam = round($soPhutLam / 60, 2);
            $soCong = round($soGioLam / 8, 2);

            // Tính giờ tăng ca
            $gioTangCa = 0;
            if ($now->gt($gioKetThuc)) {
                $gioTangCa = round($gioKetThuc->diffInHours($now), 1);
            }

            // Nếu không phải tăng ca, giữ trạng thái đi muộn nếu có
            if ($trangThai != 'tang_ca' && $chamCong->trang_thai == 'di_muon') {
                $trangThai = 'di_muon';
            }

            DB::beginTransaction();

            $chamCong->update([
                'gio_ra' => $gioRaStr,
                'so_gio_lam' => $soGioLam,
                'so_cong' => $soCong,
                'phut_ve_som' => max(0, $phutVeSom),
                'gio_tang_ca' => $gioTangCa,
                'trang_thai' => $trangThai,
                // ⭐ BỎ CẬP NHẬT TRẠNG THÁI DUYỆT
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-out thành công lúc ' . $gioRaStr,
                'data' => [
                    'gio_ra' => $gioRaStr,
                    'so_gio_lam' => $soGioLam,
                    'so_cong' => $soCong,
                    'phut_ve_som' => $phutVeSom,
                    'gio_tang_ca' => $gioTangCa,
                    'trang_thai' => $trangThai,
                ]
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
                ->whereIn('trang_thai', ['dung_gio', 'den_som'])
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
