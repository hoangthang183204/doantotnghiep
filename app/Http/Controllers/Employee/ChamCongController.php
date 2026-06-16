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
     * Trang chấm công
     */
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();
        
        $chamCongHomNay = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', $today)
            ->first();
        
        $lichSu = ChamCong::where('nguoi_dung_id', $user->id)
            ->whereDate('ngay_cham_cong', '>=', Carbon::now()->subDays(7))
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
     * Check-in - Lấy thời gian từ client
     */
    public function checkIn(Request $request)
    {
        try {
            $user = auth()->user();
            $today = Carbon::today();
            
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
                    'message' => 'Vị trí chấm công không hợp lệ! Bạn phải ở trong công ty.'
                ], 403);
            }

            // ===== LẤY THỜI GIAN TỪ CLIENT =====
            $gioVao = $request->input('gio_vao');
            
            if ($gioVao) {
                $now = Carbon::parse($gioVao);
            } else {
                $now = Carbon::now();
            }
            
            $gioVaoStr = $now->format('H:i:s');
            
            // Kiểm tra đi muộn
            $gioLamViec = GioLamViec::first();
            $phutDiMuon = 0;
            $trangThai = 'dung_gio';
            
            if ($gioLamViec) {
                $gioBatDau = Carbon::parse($gioLamViec->gio_bat_dau);
                $phutDiMuon = $now->diffInMinutes($gioBatDau, false);
                
                if ($phutDiMuon > ($gioLamViec->so_phut_cho_phep_di_tre ?? 15)) {
                    $trangThai = 'di_muon';
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
                    'phut_di_muon' => max(0, $phutDiMuon),
                    'trang_thai' => $trangThai,
                    'dia_chi_ip' => $ip,
                    'ten_wifi' => $wifi,
                    'dia_chi_mac' => $mac,
                    'ten_thiet_bi' => $request->header('User-Agent'),
                    'phuong_thuc_cham_cong' => $method,
                    'trang_thai_duyet' => 0,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Check-in thành công lúc ' . $gioVaoStr,
                'trang_thai' => $trangThai,
                'phut_di_muon' => max(0, $phutDiMuon),
                'phuong_thuc' => $method
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
     * Check-out - Lấy thời gian từ client
     */
    public function checkOut(Request $request)
    {
        try {
            $user = auth()->user();
            $today = Carbon::today();
            
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
                    'message' => 'Vị trí chấm công không hợp lệ! Bạn phải ở trong công ty.'
                ], 403);
            }

            // ===== LẤY THỜI GIAN TỪ CLIENT =====
            $gioRa = $request->input('gio_ra');
            
            if ($gioRa) {
                $now = Carbon::parse($gioRa);
            } else {
                $now = Carbon::now();
            }
            
            $gioRaStr = $now->format('H:i:s');
            
            // Tính số giờ làm
            $gioLamViec = GioLamViec::first();
            $gioVao = Carbon::parse($chamCong->gio_vao);
            
            $soGioLam = $gioVao->diffInHours($now);
            $soGioLam = max(0, $soGioLam);
            
            $soCong = round($soGioLam / 8, 2);
            
            // Kiểm tra về sớm
            $phutVeSom = 0;
            if ($gioLamViec) {
                $gioKetThuc = Carbon::parse($gioLamViec->gio_ket_thuc);
                $phutVeSom = $gioKetThuc->diffInMinutes($now, false);
                
                if ($phutVeSom > ($gioLamViec->so_phut_cho_phep_ve_som ?? 15)) {
                    if ($chamCong->trang_thai != 'di_muon') {
                        $chamCong->trang_thai = 've_som';
                    }
                }
            }

            // Tính tăng ca
            $gioTangCa = 0;
            if ($gioLamViec && $gioLamViec->gio_bat_dau_tang_ca) {
                $gioBatDauTangCa = Carbon::parse($gioLamViec->gio_bat_dau_tang_ca);
                if ($now->gt($gioBatDauTangCa)) {
                    $gioTangCa = round($gioBatDauTangCa->diffInHours($now), 1);
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
                'gio_tang_ca' => $gioTangCa
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
        
        $thangHienTai = Carbon::now()->month;
        $namHienTai = Carbon::now()->year;
        
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