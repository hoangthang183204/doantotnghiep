<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HopDongController extends Controller
{
    public function getHopDongCuaToi()
    {
        $userId = Auth::id();

        $hopDong = DB::table('hop_dong_lao_dong')
            ->join('nguoi_dung', 'hop_dong_lao_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->leftJoin('ho_so_nguoi_dung', 'nguoi_dung.id', '=', 'ho_so_nguoi_dung.nguoi_dung_id')
            ->leftJoin('chuc_vu', 'hop_dong_lao_dong.chuc_vu_id', '=', 'chuc_vu.id')
            ->leftJoin('phong_ban', 'chuc_vu.phong_ban_id', '=', 'phong_ban.id')
            ->leftJoin('nguoi_dung as nguoi_ky', 'hop_dong_lao_dong.nguoi_ky_id', '=', 'nguoi_ky.id')
            ->leftJoin('ho_so_nguoi_dung as ho_so_nguoi_ky', 'nguoi_ky.id', '=', 'ho_so_nguoi_ky.nguoi_dung_id')
            ->where('hop_dong_lao_dong.nguoi_dung_id', $userId)
            ->select(
                'hop_dong_lao_dong.*',
                'nguoi_dung.ten_dang_nhap',
                'ho_so_nguoi_dung.ma_nhan_vien as nhan_vien_ma_nv',
                'chuc_vu.ten as ten_chuc_vu',
                'phong_ban.ten_phong_ban as ten_phong_ban',
                DB::raw("CONCAT(ho_so_nguoi_dung.ho, ' ', ho_so_nguoi_dung.ten) as nhan_vien_ho_ten"),
                DB::raw("CONCAT(ho_so_nguoi_ky.ho, ' ', ho_so_nguoi_ky.ten) as nguoi_ky_ho_ten"),
                'nguoi_ky.ten_dang_nhap as nguoi_ky_username'
            )
            ->first();

        // ⭐ LẤY PHỤ CẤP
        $dsPhuCap = collect(); // Khởi tạo collection rỗng

        if ($hopDong) {
            // Cách 1: Nếu phu_cap lưu dạng JSON (nhiều phụ cấp)
            if (!empty($hopDong->phu_cap)) {
                $phuCapIds = json_decode($hopDong->phu_cap, true);
                if (is_array($phuCapIds) && count($phuCapIds) > 0) {
                    $dsPhuCap = DB::table('phu_cap')
                        ->whereIn('id', $phuCapIds)
                        ->where('trang_thai', 1)
                        ->select('ten', 'mo_ta', 'so_tien_mac_dinh')
                        ->get();
                }
            }

            // Cách 2: Nếu chỉ có phu_cap_id (1 phụ cấp) và dsPhuCap rỗng
            if ($dsPhuCap->isEmpty() && !empty($hopDong->phu_cap_id)) {
                $dsPhuCap = DB::table('phu_cap')
                    ->where('id', $hopDong->phu_cap_id)
                    ->where('trang_thai', 1)
                    ->select('ten', 'mo_ta', 'so_tien_mac_dinh')
                    ->get();
            }

            // Cách 3: Fallback - Nếu vẫn rỗng, lấy từ phu_cap (cột cũ)
            if ($dsPhuCap->isEmpty() && !empty($hopDong->phu_cap_old)) {
                $dsPhuCap = collect([
                    (object)[
                        'ten' => 'Phụ cấp khác',
                        'mo_ta' => 'Phụ cấp theo hợp đồng',
                        'so_tien_mac_dinh' => $hopDong->phu_cap_old
                    ]
                ]);
            }
        }

        return view('employee.hop-dong.index', compact('hopDong', 'dsPhuCap'));
    }

    /**
     * Xử lý gửi file scan hợp đồng đã ký
     */
    public function updateTrangThaiKy(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:gui_file_scan',
            'file_hop_dong_da_ky' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
        ], [
            'file_hop_dong_da_ky.required' => 'Vui lòng chọn file ảnh hoặc bản scan hợp đồng của bạn.',
            'file_hop_dong_da_ky.mimes' => 'Định dạng file không hợp lệ (Chỉ chấp nhận png, jpg, jpeg, pdf, doc, docx).',
            'file_hop_dong_da_ky.max' => 'Kích thước file quá lớn, vui lòng chọn file dưới 5MB.'
        ]);

        $userId = Auth::id();

        $hopDongHienTai = DB::table('hop_dong_lao_dong')
            ->where('id', $id)
            ->where('nguoi_dung_id', $userId)
            ->first();

        if (!$hopDongHienTai) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin hợp đồng hợp lệ.');
        }

        if ($request->hasFile('file_hop_dong_da_ky')) {
            if (!empty($hopDongHienTai->file_hop_dong_da_ky)) {
                Storage::disk('public')->delete($hopDongHienTai->file_hop_dong_da_ky);
            }

            $filePath = $request->file('file_hop_dong_da_ky')->store('hop_dong', 'public');

            $updated = DB::table('hop_dong_lao_dong')
                ->where('id', $id)
                ->where('nguoi_dung_id', $userId)
                ->update([
                    'file_hop_dong_da_ky' => $filePath,
                    'trang_thai_ky' => 'da_ky',
                    'trang_thai_hop_dong' => 'hieu_luc',
                    'thoi_gian_ky' => now(),
                    'updated_at' => now()
                ]);

            if ($updated) {
                return redirect()->back()->with('success', '✅ Gửi file bản scan hợp đồng đã ký thành công! Hợp đồng đã có hiệu lực.');
            }
        }

        return redirect()->back()->with('error', '❌ Có lỗi xảy ra trong quá trình tải file lên.');
    }
}
