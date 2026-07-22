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

use App\Exports\NhanVienExport;
use App\Imports\NhanVienImport;
use App\Models\DangKyTangCa;
use App\Models\DonXinNghi;
use App\Models\DonXinVeSom;
use App\Models\LichSuTaiKy;
use App\Models\SoDuPhep;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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

        if ($request->filled('email')) {
            $email = trim($request->email);
            $query->whereHas('nguoi_dung', function ($q) use ($email) {
                $q->where('email', 'like', "%{$email}%");
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
    public function show($id, Request $request)
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
            'ky_nang',
            'chung_chi',
            'du_an',
            'nguoiPhuThuoc',
            'dao_tao',
            'khen_thuong_ky_luat',
        ])->findOrFail($id);

        $hopDongHieuLuc = $hoSo->hop_dong->filter(function ($item) {
            return $item->trang_thai_hop_dong == 'hieu_luc';
        })->first();

        $luongGanNhat = $hoSo->lich_su_luong->first();

        // ⭐ LẤY LỊCH SỬ DUYỆT HỢP ĐỒNG
        $lichSuTaiKyHopDong = collect();
        if ($hopDongHieuLuc) {
            $lichSuTaiKyHopDong = LichSuTaiKy::where('hop_dong_cu_id', $hopDongHieuLuc->id)
                ->orWhere('hop_dong_moi_id', $hopDongHieuLuc->id)
                ->with(['hopDongCu', 'hopDongMoi', 'nguoiThucHien'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // ⭐ LẤY LỊCH SỬ NGHỈ PHÉP (PHÂN TRANG - 5 đơn/trang)
        $lichSuNghiPhep = DonXinNghi::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
            ->with(['loaiNghiPhep', 'nguoiDuyet'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'nghi_phep_page')
            ->appends($request->query());

        // ⭐ LẤY LỊCH SỬ TĂNG CA (PHÂN TRANG - 5 đơn/trang)
        $lichSuTangCa = DangKyTangCa::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
            ->with(['nguoiDuyet'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'tang_ca_page')
            ->appends($request->query());

        // ⭐ LẤY LỊCH SỬ ĐƠN XIN VỀ SỚM (PHÂN TRANG - 5 đơn/trang)
        $lichSuVeSom = DonXinVeSom::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
            ->with(['nguoiDuyet'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 've_som_page')
            ->appends($request->query());

        // ⭐ THỐNG KÊ ĐƠN TỪ
        $thongKeDonTu = [
            'tong_don_nghi' => DonXinNghi::where('nguoi_dung_id', $hoSo->nguoi_dung_id)->count(),
            'don_nghi_cho_duyet' => DonXinNghi::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('trang_thai', 'cho_duyet')
                ->count(),
            'don_nghi_da_duyet' => DonXinNghi::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('trang_thai', 'da_duyet')
                ->count(),
            'don_nghi_tu_choi' => DonXinNghi::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('trang_thai', 'tu_choi')
                ->count(),

            'tong_tang_ca' => DangKyTangCa::where('nguoi_dung_id', $hoSo->nguoi_dung_id)->count(),
            'tang_ca_cho_duyet' => DangKyTangCa::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('trang_thai', 'cho_duyet')
                ->count(),
            'tang_ca_da_duyet' => DangKyTangCa::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('trang_thai', 'da_duyet')
                ->count(),

            'tong_ve_som' => DonXinVeSom::where('nguoi_dung_id', $hoSo->nguoi_dung_id)->count(),
            've_som_cho_duyet' => DonXinVeSom::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('trang_thai', 'cho_duyet')
                ->count(),
            've_som_da_duyet' => DonXinVeSom::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
                ->where('trang_thai', 'da_duyet')
                ->count(),
        ];

        // ⭐ LẤY SỐ DƯ PHÉP
        $soDuPhep = SoDuPhep::where('nguoi_dung_id', $hoSo->nguoi_dung_id)
            ->where('nam', date('Y'))
            ->first();

        return view('admin.ho-so.show', compact(
            'hoSo',
            'hopDongHieuLuc',
            'luongGanNhat',
            'lichSuTaiKyHopDong',
            'lichSuNghiPhep',
            'lichSuTangCa',
            'lichSuVeSom',
            'thongKeDonTu',
            'soDuPhep'
        ));
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
                    // Ngày bắt đầu tính giảm trừ = tháng phát sinh nghĩa vụ nuôi dưỡng.
                    // Nếu HR không nhập thì lấy ngày sinh (đúng với trường hợp con),
                    // cuối cùng mới fallback về hôm nay.
                    $nptBatDau = $request->npt_ngay_bat_dau[$key]
                        ?? $request->npt_ngay_sinh[$key]
                        ?? now()->toDateString();

                    NguoiPhuThuoc::create([
                        'ho_so_id' => $hoSo->id,
                        'ho_ten' => $hoTen,
                        'ngay_sinh' => $request->npt_ngay_sinh[$key] ?? null,
                        'quan_he' => $request->npt_quan_he[$key] ?? 'con',
                        'ma_so_thue' => $request->npt_ma_so_thue[$key] ?? null,
                        'ngay_bat_dau' => $nptBatDau ?: now()->toDateString(),
                        'ngay_ket_thuc' => ($request->npt_ngay_ket_thuc[$key] ?? null) ?: null,
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


    public function export(Request $request)
    {
        $filters = [
            'phong_ban_id' => $request->phong_ban_id,
            'trang_thai' => $request->trang_thai,
        ];

        $fileName = 'danh_sach_nhan_vien_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new NhanVienExport($filters), $fileName);
    }

    /**
     * Import danh sách nhân viên từ Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new NhanVienImport();
            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $errors = $import->getErrors();

            // Log chi tiết lỗi
            Log::info('Import results:', [
                'success' => $successCount,
                'errors' => $errorCount,
                'error_details' => $errors
            ]);

            // ⭐ HIỂN THỊ KẾT QUẢ CHI TIẾT
            if ($successCount > 0 && $errorCount == 0) {
                return redirect()->back()->with('success', "✅ Import thành công {$successCount} nhân viên!");
            } elseif ($successCount > 0 && $errorCount > 0) {
                $message = "⚠️ Import thành công {$successCount} nhân viên, thất bại {$errorCount} bản ghi.";
                if (!empty($errors)) {
                    $message .= '<br><br>❌ Chi tiết lỗi:<br>';
                    $message .= '<ul style="list-style:disc; padding-left:20px; max-height:200px; overflow-y:auto;">';
                    foreach (array_slice($errors, 0, 20) as $error) {
                        $message .= "<li style=\"color:#dc2626;\">{$error}</li>";
                    }
                    if (count($errors) > 20) {
                        $message .= '<li>... (+' . (count($errors) - 20) . ' lỗi khác)</li>';
                    }
                    $message .= '</ul>';
                }
                return redirect()->back()->with('warning', $message);
            } else {
                $message = "❌ Import thất bại! Không có bản ghi nào được thêm.";
                if (!empty($errors)) {
                    $message .= '<br><br>❌ Chi tiết lỗi:<br>';
                    $message .= '<ul style="list-style:disc; padding-left:20px; max-height:200px; overflow-y:auto;">';
                    foreach (array_slice($errors, 0, 20) as $error) {
                        $message .= "<li style=\"color:#dc2626;\">{$error}</li>";
                    }
                    $message .= '</ul>';
                }
                return redirect()->back()->with('error', $message);
            }
        } catch (\Exception $e) {
            Log::error('Import exception: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', '❌ Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    /**
     * Tải file mẫu import
     */
    public function downloadTemplate()
    {
        $fileName = 'mau_import_nhan_vien_day_du.csv';

        $headers = [
            'Mã nhân viên',
            'Họ',
            'Tên',
            'Email (*)',
            'Email công ty',
            'Số điện thoại',
            'Ngày sinh',
            'Giới tính (Nam/Nữ/Khác)',
            'Tình trạng hôn nhân (Độc thân/Đã kết hôn/Ly hôn/Góa)',
            'Phòng ban',
            'Chức vụ',
            'Địa chỉ hiện tại',
            'Địa chỉ thường trú',
            'CMND/CCCD',
            'Số hộ chiếu',
            'Liên hệ khẩn cấp',
            'SĐT khẩn cấp',
            'Quan hệ khẩn cấp',
            'Chủ tài khoản',
            'Số tài khoản',
            'Tên ngân hàng',
            'Chi nhánh ngân hàng',
            'Số BHXH',
            'Mã số thuế',
            'Nơi đăng ký KCB',
            'Trạng thái (Đang làm việc/Đã nghỉ việc)',
            'Kỹ năng (Tên|cấp_độ; Tên|cấp_độ)',
            'Chứng chỉ (Tên|tổ_chức|năm|ngày_hết_hạn; ...)',
            'Dự án (Tên|vai_trò|ngày_bắt_đầu|ngày_kết_thúc|mô_tả|trạng_thái; ...)',
            'Người phụ thuộc (Họ_tên|ngày_sinh|quan_hệ|mã_số_thuế; ...)',
            'Đào tạo (Tên_khóa_học|tổ_chức|ngày_bắt_đầu|ngày_kết_thúc|kết_quả|có_chứng_chỉ|chi_phí|ghi_chú; ...)',
            'Khen thưởng/Kỷ luật (loại|tên|ngày|nội_dung|hình_thức|số_tiền|quyết_định_số; ...)',
        ];

        // Dữ liệu mẫu
        $sampleData = [
            '',                           // Mã nhân viên (để trống tự sinh)
            'Nguyễn',                     // Họ
            'Văn A',                      // Tên
            'nguyenvana@example.com',     // Email (*)
            'a.nguyen@company.com',       // Email công ty
            '0901234567',                 // Số điện thoại
            '15/01/1990',                 // Ngày sinh
            'Nam',                        // Giới tính
            'Độc thân',                   // Tình trạng hôn nhân
            'Phòng Công Nghệ',            // Phòng ban
            'Lập Trình Viên',             // Chức vụ
            'Hà Nội',                     // Địa chỉ hiện tại
            'Hà Nội',                     // Địa chỉ thường trú
            '001201000001',               // CMND/CCCD
            '',                           // Số hộ chiếu
            'Nguyễn Văn B',               // Liên hệ khẩn cấp
            '0987654321',                 // SĐT khẩn cấp
            'Cha',                        // Quan hệ khẩn cấp
            'Nguyễn Văn A',               // Chủ tài khoản
            '123456789',                  // Số tài khoản
            'Vietcombank',                // Tên ngân hàng
            'Hà Nội',                     // Chi nhánh ngân hàng
            '010123456789',               // Số BHXH
            '1234567890-1',               // Mã số thuế
            'Bệnh viện Bạch Mai',         // Nơi đăng ký KCB
            'Đang làm việc',              // Trạng thái
            'Laravel|Thành thạo; ReactJS|Trung cấp; Python|Chuyên gia',  // Kỹ năng
            'AWS Certified Developer|Amazon|2025|31/12/2028; IELTS 7.0|British Council|2024|',  // Chứng chỉ
            'HRM System|Lead Developer|01/03/2024|30/11/2024|Xây dựng hệ thống HRM|Hoàn thành; E-commerce|Full-stack|01/08/2023|31/01/2024|Website bán hàng|Hoàn thành',  // Dự án
            'Nguyễn Văn C|20/08/2020|con|1234567890-2',  // Người phụ thuộc
            'AWS Cloud Practitioner|Amazon|01/06/2025|30/06/2025|Đạt 92%|1|5000000|Khóa học cloud; Scrum Master|Scrum Alliance|01/01/2024||Đạt|1|8000000|',  // Đào tạo
            'khen_thuong|Nhân viên xuất sắc|31/12/2025|Hoàn thành vượt mức KPI|Tiền mặt|5000000|QD-2025-001',  // Khen thưởng/Kỷ luật
        ];

        $callback = function () use ($headers, $sampleData) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            // Ghi header
            fputcsv($file, $headers);

            // Ghi dữ liệu mẫu
            fputcsv($file, $sampleData);

            // Ghi thêm 1 dòng trống để hướng dẫn
            fputcsv($file, array_fill(0, count($headers), ''));

            // Ghi hướng dẫn
            $guidelines = [
                '📌 HƯỚNG DẪN NHẬP LIỆU:',
                '- (*) Email là bắt buộc, không được trùng trong hệ thống',
                '- Mã nhân viên để trống hệ thống sẽ tự sinh (NVxxxx)',
                '- Trạng thái: Đang làm việc hoặc Đã nghỉ việc',
                '- Ngày tháng: định dạng d/m/Y (ví dụ: 15/01/1990)',
                '- Kỹ năng: Tên|cấp_độ (cấp_độ: Cơ bản, Trung cấp, Thành thạo, Chuyên gia)',
                '- Chứng chỉ: Tên|tổ_chức|năm|ngày_hết_hạn (ngày hết hạn để trống nếu vĩnh viễn)',
                '- Dự án: Tên|vai_trò|ngày_bắt_đầu|ngày_kết_thúc|mô_tả|trạng_thái (trạng_thái: Đang thực hiện, Hoàn thành, Tạm dừng)',
                '- Người phụ thuộc: Họ_tên|ngày_sinh|quan_hệ|mã_số_thuế (quan_hệ: con, vo, chong, cha, me, khac)',
                '- Đào tạo: Tên_khóa_học|tổ_chức|ngày_bắt_đầu|ngày_kết_thúc|kết_quả|có_chứng_chỉ(0/1)|chi_phí|ghi_chú',
                '- Khen thưởng/Kỷ luật: loại|tên|ngày|nội_dung|hình_thức|số_tiền|quyết_định_số (loại: khen_thuong, ky_luat)',
                '- Nhiều giá trị trong cùng 1 ô: phân cách bằng dấu chấm phẩy (;)',
            ];

            foreach ($guidelines as $line) {
                fputcsv($file, [$line]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
