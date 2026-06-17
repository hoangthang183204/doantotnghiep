<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HopDongController extends Controller
{
    public function getHopDongCuaToi()
    {
        // 1. Lấy ID tài khoản người dùng đang đăng nhập
        $userId = Auth::id(); 

        // 2. Thực hiện Query kết nối các bảng lấy thông tin hợp đồng
        $hopDong = DB::table('hop_dong_lao_dong')
            ->join('nguoi_dung', 'hop_dong_lao_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->leftJoin('ho_so_nguoi_dung', 'nguoi_dung.id', '=', 'ho_so_nguoi_dung.nguoi_dung_id')
            ->leftJoin('chuc_vu', 'hop_dong_lao_dong.chuc_vu_id', '=', 'chuc_vu.id')
            ->leftJoin('phong_ban', 'chuc_vu.phong_ban_id', '=', 'phong_ban.id')
            ->where('hop_dong_lao_dong.nguoi_dung_id', $userId)
            ->select(
                'hop_dong_lao_dong.*',
                'nguoi_dung.ten_dang_nhap',
                'ho_so_nguoi_dung.ma_nhan_vien as nhan_vien_ma_nv', 
                'chuc_vu.ten as ten_chuc_vu',                                     
                'phong_ban.ten_phong_ban as ten_phong_ban',         
                DB::raw("CONCAT(ho_so_nguoi_dung.ho, ' ', ho_so_nguoi_dung.ten) as nhan_vien_ho_ten") 
            )
            ->first(); 

        // 3. NỐI BẢNG PHỤ CẤP: Giải mã cột phu_cap (nếu lưu chuỗi JSON) để lấy danh sách chi tiết
        $dsPhuCap = [];
        if ($hopDong && !empty($hopDong->phu_cap)) {
            // Thử giải mã chuỗi JSON từ cột phu_cap (Ví dụ: ["AN_TRUA", "XANG_XE"])
            $maPhuCapList = json_decode($hopDong->phu_cap, true);

            if (is_array($maPhuCapList)) {
                $dsPhuCap = DB::table('phu_cap')
                    ->whereIn('ma', $maPhuCapList)
                    ->select('ten', 'mo_ta', 'so_tien_mac_dinh')
                    ->get();
            } else {
                // TRƯỜNG HỢP DỰ PHÒNG: Nếu cột phu_cap vẫn đang là 1 con số ngẫu nhiên (từ Seeder cũ)
                // Hệ thống tự bóc tách các khoản cố định để giao diện không bị trống dữ liệu
                $tatCaPhuCap = DB::table('phu_cap')->where('trang_thai', 1)->get();
                $soTienConLai = (float)$hopDong->phu_cap;

                foreach ($tatCaPhuCap as $pc) {
                    if ($pc->so_tien_mac_dinh > 0 && $soTienConLai >= $pc->so_tien_mac_dinh) {
                        $dsPhuCap[] = (object)[
                            'ten' => $pc->ten,
                            'mo_ta' => $pc->mo_ta,
                            'so_tien_mac_dinh' => $pc->so_tien_mac_dinh
                        ];
                        $soTienConLai -= $pc->so_tien_mac_dinh;
                    }
                }
            }
        }

        // 4. Trả dữ liệu ra giao diện hiển thị
        return view('employee.hop-dong.index', compact('hopDong', 'dsPhuCap'));
    }

    // 5. Xử lý hành động Ký hoặc Từ chối cập nhật trực tiếp vào bảng hop_dong_lao_dong
    public function updateTrangThaiKy(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:da_ky,tu_choi'
        ]);

        $userId = Auth::id();
        
        $updated = DB::table('hop_dong_lao_dong')
            ->where('id', $id)
            ->where('nguoi_dung_id', $userId)
            ->update([
                'trang_thai_ky' => $request->action, 
                'thoi_gian_ky' => $request->action === 'da_ky' ? now() : null,
                'updated_at' => now()
            ]);

        if ($updated) {
            $msg = $request->action === 'da_ky' ? 'Bạn đã ký kết hợp đồng điện tử thành công!' : 'Bạn đã từ chối ký hợp đồng.';
            return redirect()->back()->with('success', $msg);
        }

        return redirect()->back()->with('error', 'Cập nhật trạng thái hợp đồng thất bại.');
    }
}