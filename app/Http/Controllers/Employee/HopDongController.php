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
            // ✅ THÊM: Lấy hợp đồng đang chờ ký hoặc hiệu lực
            ->whereIn('hop_dong_lao_dong.trang_thai_hop_dong', ['chua_hieu_luc', 'hieu_luc', 'het_han'])
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
            ->orderBy('hop_dong_lao_dong.created_at', 'desc')
            ->first();

        // Nếu không có hợp đồng nào, lấy tất cả hợp đồng của nhân viên
        if (!$hopDong) {
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
                ->orderBy('hop_dong_lao_dong.created_at', 'desc')
                ->first();
        }

        // Lấy phụ cấp
        $dsPhuCap = collect();

        if ($hopDong) {
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

            if ($dsPhuCap->isEmpty() && !empty($hopDong->phu_cap_id)) {
                $dsPhuCap = DB::table('phu_cap')
                    ->where('id', $hopDong->phu_cap_id)
                    ->where('trang_thai', 1)
                    ->select('ten', 'mo_ta', 'so_tien_mac_dinh')
                    ->get();
            }
        }

        // ✅ Lấy danh sách hợp đồng của nhân viên (để hiển thị lịch sử)
        $lichSuHopDong = DB::table('hop_dong_lao_dong')
            ->where('nguoi_dung_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('employee.hop-dong.index', compact('hopDong', 'dsPhuCap', 'lichSuHopDong'));
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
            return redirect()->back()->with('error', '❌ Không tìm thấy thông tin hợp đồng hợp lệ.');
        }

        // ⚠️ Kiểm tra hợp đồng đã ký chưa
        if ($hopDongHienTai->trang_thai_ky == 'da_ky') {
            return redirect()->back()->with('error', '⚠️ Hợp đồng này đã được ký rồi.');
        }

        // ⚠️ Kiểm tra hợp đồng đã bị hủy hoặc từ chối chưa
        if ($hopDongHienTai->trang_thai_hop_dong == 'huy_bo' || $hopDongHienTai->trang_thai_ky == 'tu_choi_ky') {
            return redirect()->back()->with('error', '❌ Hợp đồng đã bị hủy hoặc từ chối ký.');
        }

        if ($request->hasFile('file_hop_dong_da_ky')) {
            // Xóa file cũ nếu có
            if (!empty($hopDongHienTai->file_hop_dong_da_ky)) {
                Storage::disk('public')->delete($hopDongHienTai->file_hop_dong_da_ky);
            }

            $filePath = $request->file('file_hop_dong_da_ky')->store('hop_dong_da_ky', 'public');

            $updated = DB::table('hop_dong_lao_dong')
                ->where('id', $id)
                ->where('nguoi_dung_id', $userId)
                ->update([
                    'file_hop_dong_da_ky' => $filePath,
                    'trang_thai_ky' => 'da_ky',
                    'trang_thai_hop_dong' => 'hieu_luc',
                    'thoi_gian_ky' => now(),
                    'nguoi_ky_id' => $userId, // ✅ Lưu người ký
                    'updated_at' => now()
                ]);

            if ($updated) {
                // ⭐ Gửi thông báo cho HR/Admin
                try {
                    $hrUsers = DB::table('nguoi_dung')
                        ->join('nguoi_dung_vai_tro', 'nguoi_dung.id', '=', 'nguoi_dung_vai_tro.nguoi_dung_id')
                        ->join('vai_tro', 'nguoi_dung_vai_tro.vai_tro_id', '=', 'vai_tro.id')
                        ->whereIn('vai_tro.name', ['admin', 'hr'])
                        ->select('nguoi_dung.*')
                        ->get();

                    // TODO: Gửi notification cho HR/Admin
                    // if (class_exists(\App\Notifications\HopDongSignedNotification::class)) {
                    //     foreach ($hrUsers as $hr) {
                    //         $user = \App\Models\NguoiDung::find($hr->id);
                    //         if ($user) {
                    //             $user->notify(new \App\Notifications\HopDongSignedNotification($hopDongHienTai));
                    //         }
                    //     }
                    // }
                } catch (\Exception $e) {
                    // Bỏ qua lỗi notification
                }

                return redirect()->back()->with('success', '✅ Gửi file bản scan hợp đồng đã ký thành công! Hợp đồng đã có hiệu lực.');
            }
        }

        return redirect()->back()->with('error', '❌ Có lỗi xảy ra trong quá trình tải file lên.');
    }

    /**
     * ✅ THÊM: Xem chi tiết hợp đồng
     */
    public function chiTietHopDong($id)
    {
        $userId = Auth::id();

        $hopDong = DB::table('hop_dong_lao_dong')
            ->join('nguoi_dung', 'hop_dong_lao_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->leftJoin('ho_so_nguoi_dung', 'nguoi_dung.id', '=', 'ho_so_nguoi_dung.nguoi_dung_id')
            ->leftJoin('chuc_vu', 'hop_dong_lao_dong.chuc_vu_id', '=', 'chuc_vu.id')
            ->leftJoin('phong_ban', 'chuc_vu.phong_ban_id', '=', 'phong_ban.id')
            ->where('hop_dong_lao_dong.id', $id)
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

        if (!$hopDong) {
            return redirect()->route('employee.hopdong.index')->with('error', 'Không tìm thấy hợp đồng.');
        }

        return view('employee.hop-dong.chi-tiet', compact('hopDong'));
    }

    /**
     * Từ chối ký hợp đồng
     */
    public function tuChoiKy(Request $request, $id)
    {
        $request->validate([
            'ly_do_tu_choi' => 'required|string|min:10|max:1000',
        ]);

        $userId = Auth::id();

        $hopDong = DB::table('hop_dong_lao_dong')
            ->where('id', $id)
            ->where('nguoi_dung_id', $userId)
            ->first();

        if (!$hopDong) {
            return redirect()->back()->with('error', 'Không tìm thấy hợp đồng.');
        }

        if ($hopDong->trang_thai_ky == 'da_ky') {
            return redirect()->back()->with('error', 'Hợp đồng này đã được ký rồi.');
        }

        DB::table('hop_dong_lao_dong')
            ->where('id', $id)
            ->update([
                'trang_thai_ky' => 'tu_choi_ky',
                'trang_thai_hop_dong' => 'huy_bo',
                'ghi_chu' => 'Từ chối ký: ' . $request->ly_do_tu_choi,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', '✅ Đã từ chối ký hợp đồng.');
    }
}
