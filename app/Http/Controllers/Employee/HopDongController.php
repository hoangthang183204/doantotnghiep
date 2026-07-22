<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Mail\HopDongDaKyHrMail;
use App\Models\HopDongLaoDong;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HopDongController extends Controller
{
    /**
     * Lấy hợp đồng của tôi
     */
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
            ->leftJoin('nguoi_dung as nguoi_duyet', 'hop_dong_lao_dong.nguoi_duyet_id', '=', 'nguoi_duyet.id')
            ->leftJoin('ho_so_nguoi_dung as ho_so_nguoi_duyet', 'nguoi_duyet.id', '=', 'ho_so_nguoi_duyet.nguoi_dung_id')
            ->where('hop_dong_lao_dong.nguoi_dung_id', $userId)
            // 🔥 QUAN TRỌNG: CHỈ LẤY HỢP ĐỒNG ĐÃ GỬI
            ->whereNotNull('hop_dong_lao_dong.thoi_gian_gui')  // 🔥 THÊM DÒNG NÀY
            ->where('hop_dong_lao_dong.trang_thai_duyet', 'da_duyet')
            ->whereIn('hop_dong_lao_dong.trang_thai_ky', ['cho_ky', 'da_ky'])
            ->whereIn('hop_dong_lao_dong.trang_thai_hop_dong', ['chua_hieu_luc', 'hieu_luc', 'het_han'])
            ->select(
                'hop_dong_lao_dong.*',
                'nguoi_dung.ten_dang_nhap',
                'ho_so_nguoi_dung.ma_nhan_vien as nhan_vien_ma_nv',
                'chuc_vu.ten as ten_chuc_vu',
                'phong_ban.ten_phong_ban as ten_phong_ban',
                DB::raw("CONCAT(ho_so_nguoi_dung.ho, ' ', ho_so_nguoi_dung.ten) as nhan_vien_ho_ten"),
                DB::raw("CONCAT(ho_so_nguoi_ky.ho, ' ', ho_so_nguoi_ky.ten) as nguoi_ky_ho_ten"),
                DB::raw("CONCAT(ho_so_nguoi_duyet.ho, ' ', ho_so_nguoi_duyet.ten) as nguoi_duyet_ho_ten"),
                'nguoi_ky.ten_dang_nhap as nguoi_ky_username',
                'nguoi_duyet.ten_dang_nhap as nguoi_duyet_username'
            )
            ->orderBy('hop_dong_lao_dong.created_at', 'desc')
            ->first();

        // Nếu không có hợp đồng nào đã gửi
        if (!$hopDong) {
            $hopDong = DB::table('hop_dong_lao_dong')
                ->join('nguoi_dung', 'hop_dong_lao_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
                ->leftJoin('ho_so_nguoi_dung', 'nguoi_dung.id', '=', 'ho_so_nguoi_dung.nguoi_dung_id')
                ->leftJoin('chuc_vu', 'hop_dong_lao_dong.chuc_vu_id', '=', 'chuc_vu.id')
                ->leftJoin('phong_ban', 'chuc_vu.phong_ban_id', '=', 'phong_ban.id')
                ->leftJoin('nguoi_dung as nguoi_ky', 'hop_dong_lao_dong.nguoi_ky_id', '=', 'nguoi_ky.id')
                ->leftJoin('ho_so_nguoi_dung as ho_so_nguoi_ky', 'nguoi_ky.id', '=', 'ho_so_nguoi_ky.nguoi_dung_id')
                ->leftJoin('nguoi_dung as nguoi_duyet', 'hop_dong_lao_dong.nguoi_duyet_id', '=', 'nguoi_duyet.id')
                ->leftJoin('ho_so_nguoi_dung as ho_so_nguoi_duyet', 'nguoi_duyet.id', '=', 'ho_so_nguoi_duyet.nguoi_dung_id')
                ->where('hop_dong_lao_dong.nguoi_dung_id', $userId)
                ->whereNotNull('hop_dong_lao_dong.thoi_gian_gui')
                ->where('hop_dong_lao_dong.trang_thai_duyet', 'da_duyet')
                ->whereIn('hop_dong_lao_dong.trang_thai_ky', ['cho_ky', 'da_ky'])
                ->whereIn('hop_dong_lao_dong.trang_thai_hop_dong', ['chua_hieu_luc', 'hieu_luc', 'het_han'])
                ->select(
                    'hop_dong_lao_dong.*',
                    'nguoi_dung.ten_dang_nhap',
                    'ho_so_nguoi_dung.ma_nhan_vien as nhan_vien_ma_nv',
                    'chuc_vu.ten as ten_chuc_vu',
                    'phong_ban.ten_phong_ban as ten_phong_ban',
                    DB::raw("CONCAT(ho_so_nguoi_dung.ho, ' ', ho_so_nguoi_dung.ten) as nhan_vien_ho_ten"),
                    DB::raw("CONCAT(ho_so_nguoi_ky.ho, ' ', ho_so_nguoi_ky.ten) as nguoi_ky_ho_ten"),
                    DB::raw("CONCAT(ho_so_nguoi_duyet.ho, ' ', ho_so_nguoi_duyet.ten) as nguoi_duyet_ho_ten"),
                    'nguoi_ky.ten_dang_nhap as nguoi_ky_username',
                    'nguoi_duyet.ten_dang_nhap as nguoi_duyet_username'
                )
                ->orderBy('hop_dong_lao_dong.created_at', 'desc')
                ->first();
        }

        // Nếu vẫn không có, trả về null
        if (!$hopDong) {
            return view('employee.hop-dong.index', [
                'hopDong' => null,
                'dsPhuCap' => collect(),
                'lichSuHopDong' => collect()
            ]);
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

        // Lấy danh sách hợp đồng của nhân viên (lịch sử)
        $lichSuHopDong = DB::table('hop_dong_lao_dong')
            ->where('nguoi_dung_id', $userId)
            ->where('trang_thai_duyet', 'da_duyet')
            ->whereIn('trang_thai_ky', ['cho_ky', 'da_ky'])
            ->whereNotNull('thoi_gian_gui')  // 🔥 THÊM DÒNG NÀY
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

        // Kiểm tra hợp đồng đã ký chưa
        if ($hopDongHienTai->trang_thai_ky == 'da_ky') {
            return redirect()->back()->with('error', '⚠️ Hợp đồng này đã được ký rồi.');
        }

        // Kiểm tra hợp đồng đã bị hủy hoặc từ chối chưa
        if ($hopDongHienTai->trang_thai_hop_dong == 'huy_bo' || $hopDongHienTai->trang_thai_ky == 'tu_choi_ky') {
            return redirect()->back()->with('error', '❌ Hợp đồng đã bị hủy hoặc từ chối ký.');
        }

        // 🔥 KIỂM TRA: Hợp đồng đã được duyệt chưa
        if ($hopDongHienTai->trang_thai_duyet != 'da_duyet') {
            return redirect()->back()->with('error', '❌ Hợp đồng chưa được Giám đốc duyệt. Vui lòng đợi HR gửi hợp đồng.');
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
                    'nguoi_ky_id' => $userId,
                    'updated_at' => now()
                ]);

            if ($updated) {
                // 🔥 Lấy thông tin hợp đồng đã cập nhật
                $hopDongModel = HopDongLaoDong::with(['nguoiDung', 'hoSoNguoiDung'])->find($id);

                // 🔥 GỬI EMAIL XÁC NHẬN CHO NHÂN VIÊN
                if ($hopDongModel && $hopDongModel->nguoiDung && $hopDongModel->nguoiDung->email) {
                    try {
                        Mail::to($hopDongModel->nguoiDung->email)->send(new HopDongDaKyHrMail($hopDongModel));
                        Log::info('Đã gửi email xác nhận ký hợp đồng cho nhân viên: ' . $hopDongModel->nguoiDung->email);
                    } catch (\Exception $e) {
                        Log::error('Gửi email xác nhận ký hợp đồng cho nhân viên thất bại: ' . $e->getMessage());
                    }
                }

                // 🔥 GỬI EMAIL THÔNG BÁO CHO HR/ADMIN
                try {
                    $hrUsers = NguoiDung::whereHas('vaiTros', function ($q) {
                        $q->whereIn('name', ['admin', 'hr']);
                    })->get();

                    foreach ($hrUsers as $hr) {
                        if ($hr->email) {
                            Mail::to($hr->email)->send(new HopDongDaKyHrMail($hopDongModel));
                        }
                    }
                    Log::info('Đã gửi email thông báo HR về hợp đồng đã ký');
                } catch (\Exception $e) {
                    Log::error('Gửi email thông báo HR về hợp đồng đã ký thất bại: ' . $e->getMessage());
                }

                // 🔥 GỬI THÔNG BÁO TRONG HỆ THỐNG CHO HR/ADMIN
                try {
                    if (class_exists('\App\Notifications\HopDongDaKyNotification')) {
                        $hrUsers = NguoiDung::whereHas('vaiTros', function ($q) {
                            $q->whereIn('name', ['admin', 'hr']);
                        })->get();
                        foreach ($hrUsers as $hr) {
                            $hr->notify(new \App\Notifications\HopDongDaKyNotification($hopDongModel));
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Gửi thông báo hợp đồng đã ký thất bại: ' . $e->getMessage());
                }

                return redirect()->back()->with('success', '✅ Gửi file bản scan hợp đồng đã ký thành công! Hợp đồng đã có hiệu lực.');
            }
        }

        return redirect()->back()->with('error', '❌ Có lỗi xảy ra trong quá trình tải file lên.');
    }

    /**
     * Xem chi tiết hợp đồng
     */
    public function chiTietHopDong($id)
    {
        $userId = Auth::id();

        $hopDong = DB::table('hop_dong_lao_dong')
            ->join('nguoi_dung', 'hop_dong_lao_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->leftJoin('ho_so_nguoi_dung', 'nguoi_dung.id', '=', 'ho_so_nguoi_dung.nguoi_dung_id')
            ->leftJoin('chuc_vu', 'hop_dong_lao_dong.chuc_vu_id', '=', 'chuc_vu.id')
            ->leftJoin('phong_ban', 'chuc_vu.phong_ban_id', '=', 'phong_ban.id')
            ->leftJoin('nguoi_dung as nguoi_duyet', 'hop_dong_lao_dong.nguoi_duyet_id', '=', 'nguoi_duyet.id')
            ->where('hop_dong_lao_dong.id', $id)
            ->where('hop_dong_lao_dong.nguoi_dung_id', $userId)
            ->select(
                'hop_dong_lao_dong.*',
                'nguoi_dung.ten_dang_nhap',
                'ho_so_nguoi_dung.ma_nhan_vien as nhan_vien_ma_nv',
                'chuc_vu.ten as ten_chuc_vu',
                'phong_ban.ten_phong_ban as ten_phong_ban',
                DB::raw("CONCAT(ho_so_nguoi_dung.ho, ' ', ho_so_nguoi_dung.ten) as nhan_vien_ho_ten"),
                'nguoi_duyet.ten_dang_nhap as nguoi_duyet_username'
            )
            ->first();

        if (!$hopDong) {
            return redirect()->route('employee.hop-dong.index')->with('error', 'Không tìm thấy hợp đồng.');
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

        // 🔥 CẬP NHẬT ĐẦY ĐỦ THÔNG TIN
        DB::table('hop_dong_lao_dong')
            ->where('id', $id)
            ->update([
                'trang_thai_ky' => 'tu_choi_ky',
                'trang_thai_hop_dong' => 'huy_bo',
                'ghi_chu' => 'Từ chối ký: ' . $request->ly_do_tu_choi,
                'nguoi_huy_id' => $userId,        // 🔥 LƯU NGƯỜI HỦY (CHÍNH LÀ NHÂN VIÊN)
                'thoi_gian_huy' => now(),         // 🔥 LƯU THỜI GIAN HỦY
                'updated_at' => now()
            ]);

        // Gửi thông báo cho HR/Admin
        try {
            if (class_exists('\App\Notifications\HopDongTuChoiKyNotification')) {
                $hrUsers = NguoiDung::whereHas('vaiTros', function ($q) {
                    $q->whereIn('name', ['admin', 'hr']);
                })->get();

                $hopDongModel = HopDongLaoDong::with(['nguoiDung', 'hoSoNguoiDung'])->find($id);
                foreach ($hrUsers as $hr) {
                    $hr->notify(new \App\Notifications\HopDongTuChoiKyNotification($hopDongModel));
                }
            }
        } catch (\Exception $e) {
            Log::error('Gửi thông báo từ chối ký thất bại: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', '✅ Đã từ chối ký hợp đồng.');
    }
}
