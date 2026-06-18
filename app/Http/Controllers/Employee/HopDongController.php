<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Thêm Facade Storage để xử lý xóa/lưu file nếu cần

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

    // 5. Xử lý nhận file scan hợp đồng đã ký tay và cập nhật vào bảng hop_dong_lao_dong
    public function updateTrangThaiKy(Request $request, $id)
    {
        // Validate dữ liệu đầu vào: Action phải đúng và bắt buộc phải có file đính kèm
        $request->validate([
            'action' => 'required|in:gui_file_scan',
            'file_scan_ky' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120', // Tối đa 5MB
        ], [
            'file_scan_ky.required' => 'Vui lòng chọn file ảnh hoặc bản scan hợp đồng của bạn.',
            'file_scan_ky.mimes' => 'Định dạng file không hợp lệ (Chỉ chấp nhận png, jpg, jpeg, pdf, doc, docx).',
            'file_scan_ky.max' => 'Kích thước file quá lớn, vui lòng chọn file dưới 5MB.'
        ]);

        $userId = Auth::id();

        // Lấy thông tin hợp đồng hiện tại để kiểm tra file cũ
        $hopDongHienTai = DB::table('hop_dong_lao_dong')
            ->where('id', $id)
            ->where('nguoi_dung_id', $userId)
            ->first();

        if (!$hopDongHienTai) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin hợp đồng hợp lệ.');
        }

        // Xử lý upload file mới
        if ($request->hasFile('file_scan_ky')) {
            
            // Nếu trước đó nhân viên đã gửi file rồi, tiến hành xóa file cũ trên server để tránh rác host
            if (!empty($hopDongHienTai->file_scan_ky)) {
                Storage::disk('public')->delete($hopDongHienTai->file_scan_ky);
            }

            // Lưu file mới vào thư mục: public/hop_dong_scan
            $filePath = $request->file('file_scan_ky')->store('hop_dong_scan', 'public');

            // Cập nhật đường dẫn file và trạng thái ký vào database
            $updated = DB::table('hop_dong_lao_dong')
                ->where('id', $id)
                ->where('nguoi_dung_id', $userId)
                ->update([
                    'file_scan_ky' => $filePath, 
                    'trang_thai_ky' => 'da_ky', // Chuyển trạng thái sang "Đã ký" (hoặc "cho_duyet" tùy quy trình của bạn)
                    'thoi_gian_ky' => now(),
                    'updated_at' => now()
                ]);

            if ($updated) {
                return redirect()->back()->with('success', 'Gửi file bản scan hợp đồng đã ký lên hệ thống thành công!');
            }
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình tải file lên.');
    }
}