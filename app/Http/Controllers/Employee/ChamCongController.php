<?php
// app/Http/Controllers/Employee/ChamCongController.php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ChamCong;
use App\Models\CaLamViec;
use App\Models\CauHinhChamCong;
use App\Models\DonXinVeSom;
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

        // Lấy bản ghi chấm công hôm nay
        $chamCongHomNay = ChamCong::layChamCongHomNay($user->id);

        // Xác định ca hiện tại
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $gioHienTai = $now->format('H:i:s');

        // Lấy tất cả ca làm việc
        $caLamViec = CaLamViec::where('trang_thai', 1)->get();
        $caHienTai = null;

        foreach ($caLamViec as $ca) {
            $gioBatDau = Carbon::parse($ca->gio_bat_dau);
            $gioKetThuc = Carbon::parse($ca->gio_ket_thuc);

            if ($now->between($gioBatDau, $gioKetThuc)) {
                $caHienTai = $ca;
                break;
            }

            if ($ca->ma == 'SANG' && $now->between(
                Carbon::parse($ca->gio_bat_dau),
                Carbon::parse('13:00:00')
            )) {
                $caHienTai = $ca;
                break;
            }
        }

        if (!$caHienTai) {
            $caHienTai = CaLamViec::where('is_default', 1)->first();
        }

        // Kiểm tra trạng thái
        $daCheckIn = $chamCongHomNay && $chamCongHomNay->gio_vao;
        $daCheckOut = $chamCongHomNay && $chamCongHomNay->gio_ra;
        $caDaCham = $chamCongHomNay ? $chamCongHomNay->caLamViec : null;

        // Lịch sử 7 ngày
        $lichSu = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', '>=', Carbon::now('Asia/Ho_Chi_Minh')->subDays(7))
            ->with('caLamViec')
            ->orderBy('ngay_cham_cong', 'desc')
            ->get();

        // ===== THÔNG TIN VỊ TRÍ =====
        $gioLamViec = GioLamViec::first();

        // ⭐ Lấy danh sách IP và WiFi từ database
        $dsIP = CauHinhChamCong::where('loai', 'ip')
            ->where('trang_thai', 1)
            ->pluck('gia_tri')
            ->toArray();

        $dsWiFi = CauHinhChamCong::where('loai', 'wifi')
            ->where('trang_thai', 1)
            ->pluck('gia_tri')
            ->toArray();

        // ⭐ Lấy IP và WiFi từ request
        $currentIP = request()->ip();
        $currentWiFi = request()->header('X-WiFi-SSID');

        // ⭐ Nếu không có WiFi từ header, thử lấy từ chấm công hôm nay
        if (!$currentWiFi && $chamCongHomNay && $chamCongHomNay->ten_wifi) {
            $currentWiFi = $chamCongHomNay->ten_wifi;
        }

        // ⭐ KIỂM TRA IP
        $ipStatus = 'unknown';
        $ipMessage = 'Chưa xác định';

        if ($currentIP) {
            if (in_array($currentIP, $dsIP)) {
                $ipStatus = 'valid';
                $ipMessage = '✅ Hợp lệ';
            } else {
                $ipStatus = 'invalid';
                $ipMessage = '❌ Không hợp lệ';
            }
        }

        // ⭐ KIỂM TRA WIFI
        $wifiStatus = 'unknown';
        $wifiMessage = 'Chưa xác định';

        if ($currentWiFi) {
            if (in_array($currentWiFi, $dsWiFi)) {
                $wifiStatus = 'valid';
                $wifiMessage = '✅ Hợp lệ';
            } else {
                $wifiStatus = 'invalid';
                $wifiMessage = '❌ Không hợp lệ';
            }
        } else {
            $wifiMessage = '📡 Chưa kết nối WiFi';
        }

        // ⭐ Vị trí hợp lệ khi IP hoặc WiFi hợp lệ
        $isValidLocation = ($ipStatus == 'valid' || $wifiStatus == 'valid');

        // ⭐ Phương thức chấm công
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

        // ⭐ TÍNH TỔNG CÔNG VÀ TỔNG GIỜ LÀM TRONG NGÀY
        $tongCong = $chamCongHomNay ? $chamCongHomNay->so_cong : 0;
        $tongGioLam = $chamCongHomNay ? $chamCongHomNay->so_gio_lam : 0;

        return view('employee.cham-cong.index', compact(
            'chamCongHomNay',
            'daCheckIn',
            'daCheckOut',
            'caHienTai',
            'caDaCham',
            'lichSu',
            'gioLamViec',
            'dsIP',
            'dsWiFi',
            'currentIP',
            'currentWiFi',
            'isValidLocation',
            'wifiStatus',
            'wifiMessage',
            'ipStatus',
            'ipMessage',
            'phuongThucText',
            'tongCong',
            'tongGioLam'
        ));
    }

    /**
     * Xử lý check-in
     */
    public function checkIn(Request $request)
    {
        try {
            $user = auth()->user();
            $today = Carbon::today('Asia/Ho_Chi_Minh');

            // ===== KIỂM TRA THỜI GIAN CHO PHÉP =====
            if (!$this->isTimeAllowedForAttendance('checkin')) {
                return response()->json([
                    'success' => false,
                    'message' => '⏰ Không trong giờ chấm công! (6:00-8:30 sáng, 12:00-13:30 chiều)'
                ], 400);
            }

            // Kiểm tra đã check-in chưa
            if (ChamCong::daCheckInHomNay($user->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã check-in hôm nay rồi!'
                ], 400);
            }

            // ⭐ Kiểm tra vị trí (IP hoặc WiFi hợp lệ)
            $ip = $request->ip();
            $wifi = $request->header('X-WiFi-SSID');
            $mac = $request->header('X-MAC-Address');

            $ipAllowed = CauHinhChamCong::isIPAllowed($ip);
            $wifiAllowed = CauHinhChamCong::isWiFiAllowed($wifi);
            $macAllowed = CauHinhChamCong::isMACAllowed($mac);

            if (!$ipAllowed && !$wifiAllowed && !$macAllowed) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Vị trí không hợp lệ! Vui lòng kết nối WiFi công ty hoặc sử dụng IP được phép.'
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

            $gioVaoStr = $now->format('H:i:s');

            // ===== SỬA LẠI LOGIC XÁC ĐỊNH CA =====
            // Xác định ca làm việc dựa vào giờ check-in
            $ca = null;

            // Ca Sáng: 06:00 - 08:30
            if ($gioVaoStr >= '06:00:00' && $gioVaoStr <= '08:30:00') {
                $ca = CaLamViec::getSang();
            }
            // Ca Chiều: 12:00 - 13:30
            elseif ($gioVaoStr >= '12:00:00' && $gioVaoStr <= '13:30:00') {
                $ca = CaLamViec::getChieu();
            }
            // Nếu check-in trong khoảng 12:30 - 13:00 (vẫn là ca sáng)
            elseif ($gioVaoStr >= '12:30:00' && $gioVaoStr < '13:00:00') {
                $ca = CaLamViec::getSang();
            }
            // Nếu check-in trong khoảng 08:30 - 12:00 (vào muộn ca sáng)
            elseif ($gioVaoStr >= '08:30:00' && $gioVaoStr < '12:00:00') {
                $ca = CaLamViec::getSang();
            }
            // Nếu check-in trong khoảng 13:30 - 17:00 (vào muộn ca chiều)
            elseif ($gioVaoStr >= '13:30:00' && $gioVaoStr < '17:00:00') {
                $ca = CaLamViec::getChieu();
            }
            // BỎ PHẦN CHECK-IN SAU 17:30 -> TĂNG CA
            // Không cho check-in nếu không xác định được ca
            else {
                return response()->json([
                    'success' => false,
                    'message' => '⏰ Không xác định được ca làm việc! Vui lòng check-in đúng giờ: Sáng 6:00-8:30, Chiều 12:00-13:30'
                ], 400);
            }

            if (!$ca) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không xác định được ca làm việc!'
                ], 400);
            }

            // Tính trạng thái (giữ nguyên phần này)
            $phutDiMuon = 0;
            $trangThai = ChamCong::TRANG_THAI_DUNG_GIO;

            $gioBatDau = Carbon::parse($ca->gio_bat_dau);
            if ($now->lt($gioBatDau)) {
                $trangThai = ChamCong::TRANG_THAI_DEN_SOM;
            } else {
                $phutDiMuon = $gioBatDau->diffInMinutes($now);
                if ($phutDiMuon > ($ca->so_phut_cho_phep_di_tre ?? 15)) {
                    $trangThai = ChamCong::TRANG_THAI_DI_MUON;
                } else {
                    $phutDiMuon = 0;
                }
            }

            // Xác định phương thức (giữ nguyên)
            $method = 'manual';
            if ($ipAllowed) $method = 'ip';
            if ($wifiAllowed) $method = 'wifi';
            if ($macAllowed) $method = 'mac';

            DB::beginTransaction();

            $chamCong = ChamCong::create([
                'nguoi_dung_id' => $user->id,
                'ngay_cham_cong' => $today,
                'ca_lam_viec_id' => $ca->id,
                'gio_vao' => $gioVaoStr,
                'phut_di_muon' => $phutDiMuon,
                'trang_thai' => $trangThai,
                'dia_chi_ip' => $ip,
                'ten_wifi' => $wifi,
                'dia_chi_mac' => $mac,
                'ten_thiet_bi' => $request->header('User-Agent'),
                'phuong_thuc_cham_cong' => $method,
                'so_gio_lam' => 0,
                'so_cong' => 0,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "✅ Check-in thành công lúc {$gioVaoStr} (ca {$ca->ten})",
                'data' => [
                    'ca' => $ca->ten,
                    'gio_vao' => $gioVaoStr,
                    'trang_thai' => $trangThai,
                    'phut_di_muon' => $phutDiMuon,
                ]
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
     * Xử lý check-out
     */
    public function checkOut(Request $request)
    {
        try {
            $user = auth()->user();
            $today = Carbon::today('Asia/Ho_Chi_Minh');

            // Kiểm tra đã check-in chưa
            $chamCong = ChamCong::layChamCongHomNay($user->id);
            if (!$chamCong || !$chamCong->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa check-in hôm nay!'
                ], 400);
            }

            // Kiểm tra đã check-out chưa
            if ($chamCong->gio_ra) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã check-out hôm nay rồi!'
                ], 400);
            }

            // ⭐ Kiểm tra vị trí (IP hoặc WiFi hợp lệ)
            $ip = $request->ip();
            $wifi = $request->header('X-WiFi-SSID');
            $mac = $request->header('X-MAC-Address');

            $ipAllowed = CauHinhChamCong::isIPAllowed($ip);
            $wifiAllowed = CauHinhChamCong::isWiFiAllowed($wifi);
            $macAllowed = CauHinhChamCong::isMACAllowed($mac);

            if (!$ipAllowed && !$wifiAllowed && !$macAllowed) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Vị trí không hợp lệ! Vui lòng ở trong công ty để check-out.'
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

            // Lấy ca làm việc từ bản ghi check-in
            $ca = $chamCong->caLamViec;
            if (!$ca) {
                $ca = ChamCong::xacDinhCaLamViec($chamCong->gio_vao);
            }

            // Nếu vẫn không có ca, lấy ca mặc định
            if (!$ca) {
                $ca = CaLamViec::where('is_default', 1)->first();
            }

            if (!$ca) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không xác định được ca làm việc!'
                ], 400);
            }

            $gioKetThuc = Carbon::parse($ca->gio_ket_thuc);

            // ===== KIỂM TRA VỀ SỚM =====
            $isVeSom = $now->lt($gioKetThuc);
            $soPhutVeSom = 0;
            $lyDoVeSom = null;
            $daCoDonDuyet = false;

            // ⭐ NẾU VỀ SỚM, KIỂM TRA ĐƠN XIN VỀ SỚM
            if ($isVeSom) {
                $soPhutVeSom = $now->diffInMinutes($gioKetThuc);
                $soPhutChoPhep = $ca->so_phut_cho_phep_ve_som ?? 15;

                // Kiểm tra đơn xin về sớm
                $donVeSom = DonXinVeSom::where('nguoi_dung_id', $user->id)
                    ->where('ngay', $today)
                    ->where('cham_cong_id', $chamCong->id)
                    ->first();

                // Nếu đã có đơn và được duyệt
                if ($donVeSom && $donVeSom->trang_thai == 'da_duyet') {
                    $daCoDonDuyet = true;
                    $lyDoVeSom = $donVeSom->ly_do;
                    // ⭐ KHÔNG TRỪ LƯƠNG -> để phutVeSom = 0
                    $soPhutVeSom = 0;
                }
                // Nếu có đơn nhưng chưa duyệt
                elseif ($donVeSom && $donVeSom->trang_thai == 'cho_duyet') {
                    return response()->json([
                        'success' => false,
                        'message' => '⏳ Đơn xin về sớm đang chờ HR duyệt. Vui lòng đợi!',
                        'trang_thai_don' => 'cho_duyet'
                    ], 400);
                }
                // Nếu có đơn bị từ chối
                elseif ($donVeSom && $donVeSom->trang_thai == 'tu_choi') {
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Đơn xin về sớm đã bị từ chối! Lý do: ' . ($donVeSom->ly_do_tu_choi ?? 'Không có lý do'),
                        'trang_thai_don' => 'tu_choi'
                    ], 400);
                }
                // Nếu chưa có đơn và về sớm quá số phút cho phép
                elseif ($soPhutVeSom > $soPhutChoPhep) {
                    return response()->json([
                        'success' => false,
                        'message' => '⚠️ Bạn đang về sớm! Vui lòng tạo đơn xin về sớm.',
                        'yeu_cau_tao_don' => true,
                        'so_phut_ve_som' => $soPhutVeSom
                    ], 400);
                }
                // Về sớm trong phạm vi cho phép
                else {
                    $soPhutVeSom = 0;
                }
            }

            // ===== TÍNH TOÁN =====
            $trangThai = $chamCong->trang_thai;

            // Tính số giờ làm (dựa trên giờ vào và giờ ra thực tế)
            $gioVao = Carbon::parse($chamCong->gio_vao);
            $soPhutLam = $gioVao->diffInMinutes($now);
            $soGioLam = round($soPhutLam / 60, 2);

            // ⭐ TÍNH SỐ CÔNG (KHÔNG TRỪ NẾU CÓ ĐƠN DUYỆT)
            $soCong = round($soGioLam / 8, 2);
            if ($soCong > 1) $soCong = 1;

            // Nếu có đơn về sớm được duyệt, giữ nguyên số công theo giờ làm thực tế
            // Không cần giảm công vì đã được phép về sớm

            // Tính tăng ca
            $gioTangCa = 0;
            if ($now->gt($gioKetThuc)) {
                $gioTangCa = round($gioKetThuc->diffInHours($now), 1);
            }

            // Xác định trạng thái
            if ($isVeSom) {
                if ($daCoDonDuyet) {
                    $trangThai = ChamCong::TRANG_THAI_VE_SOM; // Vẫn hiển thị về sớm nhưng không trừ công
                } else {
                    // Về sớm trong phạm vi cho phép
                    $trangThai = ChamCong::TRANG_THAI_VE_SOM;
                }
            } elseif ($now->gt($gioKetThuc)) {
                $trangThai = ChamCong::TRANG_THAI_TANG_CA;
            }

            // Giữ trạng thái đi muộn nếu có
            if ($trangThai != 'tang_ca' && $chamCong->trang_thai == 'di_muon') {
                $trangThai = 'di_muon';
            }

            DB::beginTransaction();

            // Cập nhật bản ghi check-in
            $chamCong->update([
                'gio_ra' => $gioRaStr,
                'so_gio_lam' => $soGioLam,
                'so_cong' => $soCong,
                'phut_ve_som' => $soPhutVeSom,
                'gio_tang_ca' => $gioTangCa,
                'trang_thai' => $trangThai,
                'ly_do_ve_som' => $lyDoVeSom,
                'da_xac_nhan_ve_som' => $isVeSom && !empty($lyDoVeSom),
                'ghi_chu' => $lyDoVeSom,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "✅ Check-out thành công lúc {$gioRaStr}" .
                    ($isVeSom && !$daCoDonDuyet ? " (về sớm {$soPhutVeSom} phút)" : "") .
                    ($isVeSom && $daCoDonDuyet ? " (đã có đơn về sớm được duyệt)" : ""),
                'data' => [
                    'gio_ra' => $gioRaStr,
                    'so_gio_lam' => $soGioLam,
                    'so_cong' => $soCong,
                    'phut_ve_som' => $soPhutVeSom,
                    'gio_tang_ca' => $gioTangCa,
                    'trang_thai' => $trangThai,
                    'is_ve_som' => $isVeSom,
                    'da_co_don_duyet' => $daCoDonDuyet,
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
     * Tạo đơn xin về sớm
     */
    public function taoDonVeSom(Request $request)
    {
        try {
            $user = auth()->user();
            $today = Carbon::today('Asia/Ho_Chi_Minh');

            $request->validate([
                'ly_do' => 'required|string|min:5',
                'gio_ra_du_kien' => 'required',
            ]);

            // Kiểm tra đã check-in chưa
            $chamCong = ChamCong::layChamCongHomNay($user->id);
            if (!$chamCong || !$chamCong->gio_vao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa check-in hôm nay!'
                ], 400);
            }

            // Kiểm tra đã có đơn chưa
            $donExist = DonXinVeSom::where('nguoi_dung_id', $user->id)
                ->where('ngay', $today)
                ->where('cham_cong_id', $chamCong->id)
                ->whereIn('trang_thai', ['cho_duyet', 'da_duyet'])
                ->exists();

            if ($donExist) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã có đơn xin về sớm đang chờ duyệt hoặc đã được duyệt!'
                ], 400);
            }

            // Lấy ca làm việc từ bản ghi chấm công
            $ca = $chamCong->caLamViec;

            // Nếu không có ca, xác định lại dựa vào giờ check-in
            if (!$ca) {
                $gioVao = $chamCong->gio_vao;
                $ca = ChamCong::xacDinhCaLamViec($gioVao);
            }

            // Nếu vẫn không có ca, lấy ca mặc định
            if (!$ca) {
                $ca = CaLamViec::where('is_default', 1)->first();
            }

            // Nếu vẫn không có ca, báo lỗi
            if (!$ca) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không xác định được ca làm việc! Vui lòng liên hệ HR.'
                ], 400);
            }

            // Tính số phút về sớm
            $gioKetThuc = Carbon::parse($ca->gio_ket_thuc);
            $gioRaDuKien = Carbon::parse($request->gio_ra_du_kien);

            // Kiểm tra giờ ra dự kiến hợp lệ
            if ($gioRaDuKien->gt($gioKetThuc)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giờ ra dự kiến phải trước giờ kết thúc ca (' . $gioKetThuc->format('H:i') . ')!'
                ], 400);
            }

            $soPhutVeSom = $gioRaDuKien->diffInMinutes($gioKetThuc);

            DB::beginTransaction();

            $don = DonXinVeSom::create([
                'nguoi_dung_id' => $user->id,
                'cham_cong_id' => $chamCong->id,
                'ngay' => $today,
                'gio_ra_du_kien' => $request->gio_ra_du_kien,
                'so_phut_ve_som' => $soPhutVeSom,
                'ly_do' => $request->ly_do,
                'trang_thai' => 'cho_duyet',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Đã gửi đơn xin về sớm lên HR duyệt!',
                'data' => [
                    'don_id' => $don->id,
                    'so_phut_ve_som' => $soPhutVeSom,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Tao don ve som error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kiểm tra trạng thái đơn xin về sớm
     */
    public function kiemTraDonVeSom(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today('Asia/Ho_Chi_Minh');

        $don = DonXinVeSom::where('nguoi_dung_id', $user->id)
            ->where('ngay', $today)
            ->orderBy('id', 'desc')
            ->first();

        if (!$don) {
            return response()->json([
                'has_don' => false,
                'message' => 'Chưa có đơn xin về sớm'
            ]);
        }

        return response()->json([
            'has_don' => true,
            'don_id' => $don->id,
            'trang_thai' => $don->trang_thai,
            'trang_thai_text' => $don->trang_thai_text,
            'so_phut_ve_som' => $don->so_phut_ve_som,
            'ly_do' => $don->ly_do,
            'ly_do_tu_choi' => $don->ly_do_tu_choi,
            'thoi_gian_duyet' => $don->thoi_gian_duyet,
        ]);
    }

    /**
     * Kiểm tra trạng thái (AJAX)
     */
    public function trangThai(Request $request)
    {
        $user = auth()->user();
        $chamCong = ChamCong::layChamCongHomNay($user->id);

        return response()->json([
            'da_check_in' => $chamCong && $chamCong->gio_vao,
            'da_check_out' => $chamCong && $chamCong->gio_ra,
            'ca' => $chamCong ? $chamCong->ten_ca : null,
            'gio_vao' => $chamCong ? $chamCong->gio_vao_format : null,
            'gio_ra' => $chamCong ? $chamCong->gio_ra_format : null,
            'so_gio_lam' => $chamCong ? $chamCong->so_gio_lam : 0,
            'so_cong' => $chamCong ? $chamCong->so_cong : 0,
            'trang_thai' => $chamCong ? $chamCong->trang_thai : null,
            'ly_do_ve_som' => $chamCong ? $chamCong->ly_do_ve_som : null,
        ]);
    }

    private function isTimeAllowedForAttendance($type = 'checkin')
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $gioHienTai = $now->format('H:i:s');

        // Khung giờ cho phép check-in
        $checkinTimes = [
            ['start' => '06:00:00', 'end' => '08:30:00'],
            ['start' => '12:00:00', 'end' => '13:30:00'],
        ];

        // Khung giờ cho phép check-out
        $checkoutTimes = [
            ['start' => '11:00:00', 'end' => '12:30:00'],
            ['start' => '16:30:00', 'end' => '18:30:00'],
        ];

        $times = ($type == 'checkout') ? $checkoutTimes : $checkinTimes;

        foreach ($times as $timeRange) {
            if ($gioHienTai >= $timeRange['start'] && $gioHienTai <= $timeRange['end']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Lịch sử chấm công
     */
    public function history(Request $request)
    {
        $user = auth()->user();

        $thangLoc = $request->filled('thang') ? (int)$request->thang : Carbon::now('Asia/Ho_Chi_Minh')->month;
        $namLoc = $request->filled('nam') ? (int)$request->nam : Carbon::now('Asia/Ho_Chi_Minh')->year;

        $query = ChamCong::where('nguoi_dung_id', $user->id)
            ->with('caLamViec');

        if ($request->filled('thang')) {
            $query->whereMonth('ngay_cham_cong', $request->thang);
        }
        if ($request->filled('nam')) {
            $query->whereYear('ngay_cham_cong', $request->nam);
        }

        $lichSu = $query->orderBy('ngay_cham_cong', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->appends($request->query());

        // Lấy tất cả bản ghi trong tháng để thống kê
        $allRecords = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereMonth('ngay_cham_cong', $thangLoc)
            ->whereYear('ngay_cham_cong', $namLoc)
            ->whereNotNull('gio_vao') // Chỉ tính những ngày có check-in
            ->get();

        // Thống kê chi tiết
        $thongKe = [
            'tong_ngay' => $allRecords->count(),

            // Đúng giờ (bao gồm đến sớm)
            'dung_gio' => $allRecords->whereIn('trang_thai', [ChamCong::TRANG_THAI_DUNG_GIO, ChamCong::TRANG_THAI_DEN_SOM])->count(),

            // Đi muộn
            'di_muon' => $allRecords->where('trang_thai', ChamCong::TRANG_THAI_DI_MUON)->count(),

            // Về sớm
            've_som' => $allRecords->where('trang_thai', ChamCong::TRANG_THAI_VE_SOM)->count(),

            // FULL CÔNG (>= 1 công)
            'full_cong' => $allRecords->filter(function ($item) {
                return ($item->so_cong ?? 0) >= 1;
            })->count(),

            // NỬA CÔNG (0.5 - 0.99)
            'nua_cong' => $allRecords->filter(function ($item) {
                $soCong = $item->so_cong ?? 0;
                return $soCong >= 0.5 && $soCong < 1;
            })->count(),

            // ÍT CÔNG (0 < công < 0.5)
            'it_cong' => $allRecords->filter(function ($item) {
                $soCong = $item->so_cong ?? 0;
                return $soCong > 0 && $soCong < 0.5;
            })->count(),

            // 0 CÔNG (đã check-in nhưng không có công)
            'khong_cong' => $allRecords->filter(function ($item) {
                return ($item->so_cong ?? 0) == 0;
            })->count(),

            'tong_gio_lam' => $allRecords->sum('so_gio_lam') ?? 0,
            'tong_tang_ca' => $allRecords->sum('gio_tang_ca') ?? 0,
        ];

        $thangNamList = ChamCong::where('nguoi_dung_id', $user->id)
            ->selectRaw('DISTINCT YEAR(ngay_cham_cong) as nam, MONTH(ngay_cham_cong) as thang')
            ->orderBy('nam', 'desc')
            ->orderBy('thang', 'desc')
            ->get();

        return view('employee.cham-cong.history', compact(
            'lichSu',
            'thongKe',
            'thangNamList',
            'thangLoc',
            'namLoc'
        ));
    }
}
