<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChucVu;
use App\Models\ChungChiNhanVien;
use App\Models\DaoTaoNhanVien;
use App\Models\DuAnNhanVien;
use App\Models\HopDongLaoDong;
use App\Models\HoSo;
use App\Models\KhenThuongKyLuatNhanVien;
use App\Models\KyNangNhanVien;
use App\Models\NguoiDung;
use App\Models\NguoiPhuThuoc;
use App\Models\PhongBan;
use App\Models\TaiLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HoSoController extends Controller
{
    /**
     * Danh sách hồ sơ nhân viên
     */
    public function index(Request $request)
    {
        $query = HoSo::with('nguoi_dung.chuc_vu', 'nguoi_dung.phong_ban');

        // Tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('ho', 'like', "%{$keyword}%")
                    ->orWhere('ten', 'like', "%{$keyword}%")
                    ->orWhere('ma_nhan_vien', 'like', "%{$keyword}%")
                    ->orWhere('so_dien_thoai', 'like', "%{$keyword}%")
                    ->orWhere('cmnd_cccd', 'like', "%{$keyword}%")
                    ->orWhereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$keyword}%"]);
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->whereHas('nguoi_dung', function ($q) use ($request) {
                $q->where('trang_thai', $request->trang_thai);
            });
        }

        // Lọc theo phòng ban
        if ($request->filled('phong_ban_id')) {
            $query->whereHas('nguoi_dung', function ($q) use ($request) {
                $q->where('phong_ban_id', $request->phong_ban_id);
            });
        }

        // Lọc theo chức vụ
        if ($request->filled('chuc_vu_id')) {
            $query->whereHas('nguoi_dung', function ($q) use ($request) {
                $q->where('chuc_vu_id', $request->chuc_vu_id);
            });
        }

        $hoSos = $query->latest('id')->paginate(10);
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();

        return view('admin.ho-so.index', compact('hoSos', 'phongBans', 'chucVus'));
    }

    /**
     * Chi tiết hồ sơ - LẤY DỮ LIỆU THẬT TỪ DATABASE
     */
    public function show($id)
    {
        $hoSo = HoSo::with([
            'nguoi_dung.chuc_vu',
            'nguoi_dung.phong_ban',
            'cv',
            'hop_dong' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'lich_su_luong' => function ($query) {
                $query->orderBy('luong_nam', 'desc')
                    ->orderBy('luong_thang', 'desc')
                    ->limit(10);
            },
            // ⭐ THÊM 3 QUAN HỆ MỚI
            'ky_nang',
            'chung_chi',
            'du_an',
        ])->findOrFail($id);

        $hopDongHieuLuc = $hoSo->hop_dong->filter(function ($item) {
            return $item->trang_thai_hop_dong == 'hieu_luc';
        })->first();

        $luongGanNhat = $hoSo->lich_su_luong->first();

        return view('admin.ho-so.show', compact('hoSo', 'hopDongHieuLuc', 'luongGanNhat'));
    }

    /**
     * Xem file CV
     */
    public function viewCv($id)
    {
        $cv = TaiLieu::findOrFail($id);

        $path = storage_path('app/public/' . $cv->duong_dan_file);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => $cv->loai_mime ?? 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    /**
     * Xem file hợp đồng
     */
    public function viewContract($id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);

        if (empty($hopDong->file_hop_dong_da_ky)) {
            abort(404, 'Chưa có file hợp đồng');
        }

        $path = storage_path('app/public/' . $hopDong->file_hop_dong_da_ky);

        if (!file_exists($path)) {
            abort(404, 'File hợp đồng không tồn tại trên server');
        }

        $mimeType = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    /**
     * Form sửa hồ sơ
     */
    /**
     * Form sửa hồ sơ
     */
    public function edit($id)
    {
        $hoSo = HoSo::with([
            'nguoi_dung',
            'cv',
            'hop_dong',
            'ky_nang',
            'chung_chi',
            'du_an',
            'nguoiPhuThuoc',
            'dao_tao',
            'khen_thuong_ky_luat',
        ])->findOrFail($id);

        // ⭐ LẤY DANH SÁCH NGƯỜI DÙNG ĐỂ CHỌN NGƯỜI KÝ
        $nguoiKys = NguoiDung::where('trang_thai', 1)
            ->where('trang_thai_cong_viec', 'dang_lam')
            ->get();

        return view('admin.ho-so.edit', compact('hoSo', 'nguoiKys'));
    }


    public function update(Request $request, $id)
    {
        $hoSo = HoSo::findOrFail($id);

        // ========== VALIDATION ==========
        $validated = $request->validate([
            // Thông tin cá nhân
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'so_dien_thoai' => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:nam,nu,khac',
            'dia_chi_hien_tai' => 'nullable|string',
            'dia_chi_thuong_tru' => 'nullable|string',
            'cmnd_cccd' => 'nullable|string|unique:ho_so_nguoi_dung,cmnd_cccd,' . $id,
            'so_ho_chieu' => 'nullable|string',
            'tinh_trang_hon_nhan' => 'nullable|in:doc_than,da_ket_hon,ly_hon,goa',

            // Liên hệ khẩn cấp
            'lien_he_khan_cap' => 'nullable|string|max:255',
            'sdt_khan_cap' => 'nullable|string|max:20',
            'quan_he_khan_cap' => 'nullable|string|max:255',

            // Ảnh
            'anh_dai_dien' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'anh_cccd_truoc' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'anh_cccd_sau' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // CV
            'file_cv' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',

            // Ngân hàng
            'chu_tai_khoan' => 'nullable|string|max:255',
            'so_tai_khoan' => 'nullable|string|max:50',
            'ten_ngan_hang' => 'nullable|string|max:255',
            'chi_nhanh_ngan_hang' => 'nullable|string|max:255',

            // Bảo hiểm & Thuế
            'so_bhxh' => 'nullable|string|max:50',
            'ma_so_thue' => 'nullable|string|max:50',
            'noi_dang_ky_kcb' => 'nullable|string|max:255',

            'file_hop_dong' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // ========== XỬ LÝ UPLOAD ẢNH ==========

        // Ảnh đại diện
        if ($request->hasFile('anh_dai_dien')) {
            if ($hoSo->anh_dai_dien && Storage::disk('public')->exists($hoSo->anh_dai_dien)) {
                Storage::disk('public')->delete($hoSo->anh_dai_dien);
            }
            $validated['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
        }

        // Ảnh CCCD mặt trước
        if ($request->hasFile('anh_cccd_truoc')) {
            if ($hoSo->anh_cccd_truoc && Storage::disk('public')->exists($hoSo->anh_cccd_truoc)) {
                Storage::disk('public')->delete($hoSo->anh_cccd_truoc);
            }
            $validated['anh_cccd_truoc'] = $request->file('anh_cccd_truoc')->store('cccd', 'public');
        }

        // Ảnh CCCD mặt sau
        if ($request->hasFile('anh_cccd_sau')) {
            if ($hoSo->anh_cccd_sau && Storage::disk('public')->exists($hoSo->anh_cccd_sau)) {
                Storage::disk('public')->delete($hoSo->anh_cccd_sau);
            }
            $validated['anh_cccd_sau'] = $request->file('anh_cccd_sau')->store('cccd', 'public');
        }

        // ========== XỬ LÝ UPLOAD CV ==========
        if ($request->hasFile('file_cv')) {
            $file = $request->file('file_cv');
            $path = $file->store('cv', 'public');

            // Kiểm tra CV cũ trong bảng tai_lieu
            $cvCu = TaiLieu::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('loai_tai_lieu', 'cv')
                ->first();

            // Xóa file cũ nếu có
            if ($cvCu && Storage::disk('public')->exists($cvCu->duong_dan_file)) {
                Storage::disk('public')->delete($cvCu->duong_dan_file);
                $cvCu->delete();
            }

            // Tạo record mới trong bảng tai_lieu
            TaiLieu::create([
                'nguoi_dung_id' => $hoSo->nguoi_dung_id,
                'loai_tai_lieu' => 'cv',
                'tieu_de' => 'CV_' . $hoSo->ho . '_' . $hoSo->ten . '_' . date('Y'),
                'mo_ta' => 'CV của ' . $hoSo->ho . ' ' . $hoSo->ten,
                'ten_file_goc' => $file->getClientOriginalName(),
                'duong_dan_file' => $path,
                'kich_thuoc_file' => $file->getSize(),
                'loai_mime' => $file->getMimeType(),
                'bao_mat' => 0,
                'nguoi_tai_len_id' => auth()->id(),
                'thoi_gian_tai_len' => now(),
                'trang_thai' => 'hop_le',
            ]);
        }

        if ($request->hasFile('file_hop_dong')) {
            $file = $request->file('file_hop_dong');
            $path = $file->store('hop_dong', 'public');

            // Lấy hợp đồng hiệu lực để cập nhật
            $hopDong = HopDongLaoDong::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('trang_thai_hop_dong', 'hieu_luc')
                ->first();

            if ($hopDong) {
                // Xóa file cũ nếu có
                if ($hopDong->file_hop_dong_da_ky && Storage::disk('public')->exists($hopDong->file_hop_dong_da_ky)) {
                    Storage::disk('public')->delete($hopDong->file_hop_dong_da_ky);
                }

                $hopDong->update([
                    'file_hop_dong_da_ky' => $path,
                ]);
            } else {
                // Nếu chưa có hợp đồng, tạo mới (tùy chọn)
                // Hoặc bỏ qua
            }
        }

        // ========== CẬP NHẬT THÔNG TIN CƠ BẢN ==========
        $hoSo->update($validated);

        // ========== XỬ LÝ KỸ NĂNG ==========
        if ($request->has('ky_nang_ten')) {
            // Xóa kỹ năng cũ
            $hoSo->ky_nang()->delete();

            foreach ($request->ky_nang_ten as $key => $ten) {
                if (!empty($ten)) {
                    KyNangNhanVien::create([
                        'ho_so_id' => $hoSo->id,
                        'ten_ky_nang' => $ten,
                        'cap_do' => $request->ky_nang_cap_do[$key] ?? 'Trung cấp',
                    ]);
                }
            }
        }

        // ========== XỬ LÝ CHỨNG CHỈ ==========
        if ($request->has('chung_chi_ten')) {
            // Xóa chứng chỉ cũ
            $hoSo->chung_chi()->delete();

            foreach ($request->chung_chi_ten as $key => $ten) {
                if (!empty($ten)) {
                    ChungChiNhanVien::create([
                        'ho_so_id' => $hoSo->id,
                        'ten_chung_chi' => $ten,
                        'to_chuc_cap' => $request->chung_chi_to_chuc[$key] ?? '',
                        'nam_cap' => $request->chung_chi_nam[$key] ?? date('Y'),
                        'ngay_het_han' => !empty($request->chung_chi_het_han[$key]) ? $request->chung_chi_het_han[$key] : null,
                    ]);
                }
            }
        }

        // ========== XỬ LÝ DỰ ÁN ==========
        if ($request->has('du_an_ten')) {
            // Xóa dự án cũ
            $hoSo->du_an()->delete();

            foreach ($request->du_an_ten as $key => $ten) {
                if (!empty($ten)) {
                    DuAnNhanVien::create([
                        'ho_so_id' => $hoSo->id,
                        'ten_du_an' => $ten,
                        'vai_tro' => $request->du_an_vai_tro[$key] ?? '',
                        'ngay_bat_dau' => !empty($request->du_an_bat_dau[$key]) ? $request->du_an_bat_dau[$key] : null,
                        'ngay_ket_thuc' => !empty($request->du_an_ket_thuc[$key]) ? $request->du_an_ket_thuc[$key] : null,
                        'mo_ta' => $request->du_an_mo_ta[$key] ?? '',
                        'trang_thai' => $request->du_an_trang_thai[$key] ?? 'Hoàn thành',
                    ]);
                }
            }
        }

        // Trong hàm update của HoSoController.php, thêm sau phần xử lý Dự án:

        // ========== XỬ LÝ NGƯỜI PHỤ THUỘC ==========
        if ($request->has('npt_ho_ten')) {
            // Xóa người phụ thuộc cũ
            $hoSo->nguoiPhuThuoc()->delete();

            foreach ($request->npt_ho_ten as $key => $hoTen) {
                if (!empty($hoTen)) {
                    NguoiPhuThuoc::create([
                        'ho_so_id' => $hoSo->id,
                        'ho_ten' => $hoTen,
                        'ngay_sinh' => $request->npt_ngay_sinh[$key] ?? null,
                        'quan_he' => $request->npt_quan_he[$key] ?? 'con',
                        'ma_so_thue' => $request->npt_ma_so_thue[$key] ?? null,
                        'ngay_bat_dau' => $request->npt_ngay_sinh[$key] ?? now(),
                        'ngay_ket_thuc' => null,
                        'ghi_chu' => null,
                    ]);
                }
            }
        }

        // ========== XỬ LÝ ĐÀO TẠO ==========
        if ($request->has('dt_ten_khoa_hoc')) {
            // Xóa đào tạo cũ
            $hoSo->dao_tao()->delete();

            foreach ($request->dt_ten_khoa_hoc as $key => $tenKhoaHoc) {
                if (!empty($tenKhoaHoc)) {
                    DaoTaoNhanVien::create([
                        'ho_so_id' => $hoSo->id,
                        'ten_khoa_hoc' => $tenKhoaHoc,
                        'to_chuc' => $request->dt_to_chuc[$key] ?? null,
                        'ngay_bat_dau' => $request->dt_ngay_bat_dau[$key] ?? null,
                        'ngay_ket_thuc' => $request->dt_ngay_ket_thuc[$key] ?? null,
                        'ket_qua' => $request->dt_ket_qua[$key] ?? null,
                        'co_chung_chi' => $request->dt_co_chung_chi[$key] ?? false,
                        'chi_phi' => $request->dt_chi_phi[$key] ?? null,
                        'ghi_chu' => $request->dt_ghi_chu[$key] ?? null,
                    ]);
                }
            }
        }

        // ========== XỬ LÝ KHEN THƯỞNG & KỶ LUẬT ==========
        if ($request->has('ktkl_ten')) {
            // Xóa khen thưởng cũ
            $hoSo->khen_thuong_ky_luat()->delete();

            foreach ($request->ktkl_ten as $key => $ten) {
                if (!empty($ten)) {
                    KhenThuongKyLuatNhanVien::create([
                        'ho_so_id' => $hoSo->id,
                        'loai' => $request->ktkl_loai[$key] ?? 'khen_thuong',
                        'ten' => $ten,
                        'ngay' => $request->ktkl_ngay[$key] ?? now(),
                        'noi_dung' => $request->ktkl_noi_dung[$key] ?? null,
                        'hinh_thuc' => $request->ktkl_hinh_thuc[$key] ?? null,
                        'so_tien' => $request->ktkl_so_tien[$key] ?? null,
                        'quyet_dinh_so' => $request->ktkl_quyet_dinh_so[$key] ?? null,
                        'nguoi_ky_id' => $request->ktkl_nguoi_ky_id[$key] ?? null,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.ho-so.show', $hoSo->id)
            ->with('success', '✅ Cập nhật hồ sơ thành công!');
    }

    /**
     * 🔴 Đánh dấu nghỉ việc
     * Cập nhật bảng nguoi_dung (trang_thai = 0, trang_thai_cong_viec = da_nghi)
     */
    public function resign($id)
    {
        $hoSo = HoSo::findOrFail($id);
        $nguoiDung = NguoiDung::findOrFail($hoSo->nguoi_dung_id);

        $nguoiDung->update([
            'trang_thai' => 0,
            'trang_thai_cong_viec' => 'da_nghi',
        ]);

        return redirect()
            ->route('admin.ho-so.index')
            ->with('success', 'Đã đánh dấu nhân viên nghỉ việc');
    }

    /**
     * 🟢 Kích hoạt lại (dùng khi nhân viên quay lại làm việc)
     */
    public function activate($id)
    {
        $hoSo = HoSo::findOrFail($id);
        $nguoiDung = NguoiDung::findOrFail($hoSo->nguoi_dung_id);

        $nguoiDung->update([
            'trang_thai' => 1,
            'trang_thai_cong_viec' => 'dang_lam',
        ]);

        return redirect()
            ->route('admin.ho-so.index')
            ->with('success', 'Đã kích hoạt lại nhân viên');
    }

    /**
     * Xuất danh sách hồ sơ ra Excel
     */
    public function exportExcel()
    {
        // TODO: Implement export to Excel
        return redirect()->back()->with('info', 'Tính năng đang phát triển');
    }
}
