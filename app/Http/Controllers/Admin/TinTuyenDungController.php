<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TinTuyenDung;
use App\Models\PhongBan;
use App\Models\ChucVu;
use App\Models\UngVien;
use App\Models\VaiTro;
use App\Models\LichSuEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TinTuyenDungMail;

class TinTuyenDungController extends Controller
{
    public function index(Request $request)
    {
        $query = TinTuyenDung::with(['phongBan', 'chucVu', 'vaiTro', 'nguoiDang', 'ungViens']);

        // Tìm kiếm theo tiêu đề hoặc mô tả
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('tieu_de', 'like', "%{$keyword}%")
                    ->orWhere('mo_ta_cong_viec', 'like', "%{$keyword}%")
                    ->orWhere('ma', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        // Lọc theo phòng ban
        if ($request->filled('phong_ban_id')) {
            $query->where('phong_ban_id', $request->phong_ban_id);
        }

        // Lọc theo chức vụ
        if ($request->filled('chuc_vu_id')) {
            $query->where('chuc_vu_id', $request->chuc_vu_id);
        }

        $tinTuyenDungs = $query->latest()->paginate(10);
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();

        // THỐNG KÊ TỔNG QUAN
        $tongQuan = [
            'tong_tin' => TinTuyenDung::count(),
            'nhap' => TinTuyenDung::where('trang_thai', 'nhap')->count(),
            'dang_tuyen' => TinTuyenDung::where('trang_thai', 'dang_tuyen')->count(),
            'tam_dung' => TinTuyenDung::where('trang_thai', 'tam_dung')->count(),
            'ket_thuc' => TinTuyenDung::where('trang_thai', 'ket_thuc')->count(),
            'tong_ung_vien' => UngVien::count(),
            // THÊM THỐNG KÊ ỨNG VIÊN THEO TRẠNG THÁI
            'ung_vien_moi_nop' => UngVien::where('trang_thai', 'moi_nop')->count(),
            'ung_vien_cho_duyet' => UngVien::where('trang_thai', 'cho_duyet')->count(),
            'ung_vien_da_duyet' => UngVien::where('trang_thai', 'da_duyet')->count(),
            'ung_vien_dat' => UngVien::where('trang_thai', 'dat')->count(),
            'ung_vien_khong_dat' => UngVien::where('trang_thai', 'khong_dat')->count(),
        ];

        return view('admin.tin-tuyen-dung.index', compact('tinTuyenDungs', 'phongBans', 'chucVus', 'tongQuan'));
    }
    /**
     * Hiển thị form tạo tin tuyển dụng mới
     */
    public function create()
    {
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all(); // Lấy tất cả chức vụ
        $vaiTros = VaiTro::all();

        // Debug: Kiểm tra dữ liệu
        \Log::info('PhongBan count: ' . $phongBans->count());
        \Log::info('ChucVu count: ' . $chucVus->count());

        if ($chucVus->isEmpty()) {
            \Log::warning('ChucVu table is empty! Please run seeder.');
        }

        return view('admin.tin-tuyen-dung.create', compact('phongBans', 'chucVus', 'vaiTros'));
    }

    /**
     * Lưu tin tuyển dụng mới
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Store TinTuyenDung - Data:', $request->all());

            $validated = $request->validate([
                'tieu_de' => 'required|string|max:255',
                'phong_ban_id' => 'required|exists:phong_ban,id',
                'chuc_vu_id' => 'required|exists:chuc_vu,id',
                'vai_tro_id' => 'nullable|exists:vai_tro,id',
                'so_vi_tri' => 'required|integer|min:1',
                'mo_ta_cong_viec' => 'required|string',
                'yeu_cau' => 'nullable|string',
                'phuc_loi' => 'nullable|string',
                'ky_nang_yeu_cau' => 'nullable|string',
                'han_nop_ho_so' => 'required|date',
                'trang_thai' => 'required|in:nhap,dang_tuyen,tam_dung,ket_thuc',
                'loai_hop_dong' => 'required|in:thu_viec,xac_dinh_thoi_han,khong_xac_dinh_thoi_han',
                'cap_do_kinh_nghiem' => 'required|in:intern,fresher,junior,middle,senior',
                'kinh_nghiem_toi_thieu' => 'nullable|integer|min:0',
                'kinh_nghiem_toi_da' => 'nullable|integer|min:0',
                'luong_toi_thieu' => 'nullable|numeric|min:0',
                'luong_toi_da' => 'nullable|numeric|min:0',
                'trinh_do_hoc_van' => 'nullable|string|max:255',
                'lam_viec_tu_xa' => 'nullable|boolean',
                'tuyen_gap' => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            // Tạo mã tự động
            $ma = 'TD' . date('Ymd') . rand(100, 999);

            while (TinTuyenDung::where('ma', $ma)->exists()) {
                $ma = 'TD' . date('Ymd') . rand(100, 999);
            }

            // Chuyển đổi text sang JSON
            $yeuCauArray = $request->filled('yeu_cau') ? array_filter(explode("\n", $request->yeu_cau)) : [];
            $phucLoiArray = $request->filled('phuc_loi') ? array_filter(explode("\n", $request->phuc_loi)) : [];
            $kyNangArray = $request->filled('ky_nang_yeu_cau') ? array_filter(explode("\n", $request->ky_nang_yeu_cau)) : [];

            $data = [
                'tieu_de' => $validated['tieu_de'],
                'ma' => $ma,
                'phong_ban_id' => $validated['phong_ban_id'],
                'chuc_vu_id' => $validated['chuc_vu_id'],
                'vai_tro_id' => $validated['vai_tro_id'] ?? null,
                'so_vi_tri' => $validated['so_vi_tri'],
                'mo_ta_cong_viec' => $validated['mo_ta_cong_viec'],
                'yeu_cau' => !empty($yeuCauArray) ? json_encode(array_values($yeuCauArray)) : null,
                'phuc_loi' => !empty($phucLoiArray) ? json_encode(array_values($phucLoiArray)) : null,
                'ky_nang_yeu_cau' => !empty($kyNangArray) ? json_encode(array_values($kyNangArray)) : null,
                'han_nop_ho_so' => $validated['han_nop_ho_so'],
                'trang_thai' => $validated['trang_thai'],
                'loai_hop_dong' => $validated['loai_hop_dong'],
                'cap_do_kinh_nghiem' => $validated['cap_do_kinh_nghiem'],
                'kinh_nghiem_toi_thieu' => $validated['kinh_nghiem_toi_thieu'] ?? 0,
                'kinh_nghiem_toi_da' => $validated['kinh_nghiem_toi_da'] ?? 0,
                'luong_toi_thieu' => $validated['luong_toi_thieu'] ?? null,
                'luong_toi_da' => $validated['luong_toi_da'] ?? null,
                'trinh_do_hoc_van' => $validated['trinh_do_hoc_van'] ?? null,
                'lam_viec_tu_xa' => $request->boolean('lam_viec_tu_xa', false),
                'tuyen_gap' => $request->boolean('tuyen_gap', false),
                'nguoi_dang_id' => Auth::id(),
            ];

            if ($validated['trang_thai'] == 'dang_tuyen') {
                $data['thoi_gian_dang'] = now();
            }

            $tinTuyenDung = TinTuyenDung::create($data);

            DB::commit();

            return redirect()->route('admin.tin-tuyen-dung.index')
                ->with('success', 'Tạo tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" thành công!<br>Mã tin: <strong>' . $tinTuyenDung->ma . '</strong>');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Store TinTuyenDung Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ... Các method khác giữ nguyên
    /**
     * Hiển thị chi tiết tin tuyển dụng
     */
    /**
     * Hiển thị chi tiết tin tuyển dụng
     */
    /**
     * Hiển thị chi tiết tin tuyển dụng
     */
    public function show($id)
    {
        $tinTuyenDung = TinTuyenDung::with([
            'phongBan',
            'chucVu',
            'vaiTro',
            'nguoiDang',
            'ungViens'
        ])->findOrFail($id);

        // Thống kê số lượng ứng viên theo trạng thái - THÊM TRẠNG THÁI MỚI NỘP
        $thongKe = [
            'tong' => $tinTuyenDung->ungViens->count(),
            'moi_nop' => $tinTuyenDung->ungViens->where('trang_thai', 'moi_nop')->count(),
            'cho_duyet' => $tinTuyenDung->ungViens->where('trang_thai', 'cho_duyet')->count(),
            'da_duyet' => $tinTuyenDung->ungViens->where('trang_thai', 'da_duyet')->count(),
            'dat' => $tinTuyenDung->ungViens->where('trang_thai', 'dat')->count(),
            'khong_dat' => $tinTuyenDung->ungViens->where('trang_thai', 'khong_dat')->count(),
            'da_huy' => $tinTuyenDung->ungViens->where('trang_thai', 'da_huy')->count(),
            'tam_dung' => $tinTuyenDung->ungViens->where('trang_thai', 'tam_dung')->count(),
        ];

        // Decode JSON
        $yeuCau = $tinTuyenDung->yeu_cau ? json_decode($tinTuyenDung->yeu_cau, true) : [];
        $phucLoi = $tinTuyenDung->phuc_loi ? json_decode($tinTuyenDung->phuc_loi, true) : [];
        $kyNang = $tinTuyenDung->ky_nang_yeu_cau ? json_decode($tinTuyenDung->ky_nang_yeu_cau, true) : [];

        return view('admin.tin-tuyen-dung.show', compact('tinTuyenDung', 'thongKe', 'yeuCau', 'phucLoi', 'kyNang'));
    }

    /**
     * Hiển thị form chỉnh sửa tin tuyển dụng
     */
    public function edit($id)
    {
        $tinTuyenDung = TinTuyenDung::with(['phongBan', 'chucVu', 'vaiTro'])->findOrFail($id);
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();
        $vaiTros = VaiTro::all();

        // Decode JSON để hiển thị trong form
        $yeuCau = $tinTuyenDung->yeu_cau ? implode("\n", json_decode($tinTuyenDung->yeu_cau, true)) : '';
        $phucLoi = $tinTuyenDung->phuc_loi ? implode("\n", json_decode($tinTuyenDung->phuc_loi, true)) : '';
        $kyNang = $tinTuyenDung->ky_nang_yeu_cau ? implode("\n", json_decode($tinTuyenDung->ky_nang_yeu_cau, true)) : '';

        return view('admin.tin-tuyen-dung.edit', compact('tinTuyenDung', 'phongBans', 'chucVus', 'vaiTros', 'yeuCau', 'phucLoi', 'kyNang'));
    }

    /**
     * Cập nhật tin tuyển dụng
     */
    public function update(Request $request, $id)
    {
        $tinTuyenDung = TinTuyenDung::findOrFail($id);

        try {
            $validated = $request->validate([
                'tieu_de' => 'required|string|max:255',
                'phong_ban_id' => 'required|exists:phong_ban,id',
                'chuc_vu_id' => 'required|exists:chuc_vu,id',
                'vai_tro_id' => 'nullable|exists:vai_tro,id',
                'so_vi_tri' => 'required|integer|min:1',
                'mo_ta_cong_viec' => 'required|string',
                'yeu_cau' => 'nullable|string',
                'phuc_loi' => 'nullable|string',
                'ky_nang_yeu_cau' => 'nullable|string',
                'han_nop_ho_so' => 'required|date',
                'trang_thai' => 'required|in:nhap,dang_tuyen,tam_dung,ket_thuc',
                'loai_hop_dong' => 'required|in:thu_viec,xac_dinh_thoi_han,khong_xac_dinh_thoi_han',
                'cap_do_kinh_nghiem' => 'required|in:intern,fresher,junior,middle,senior',
                'kinh_nghiem_toi_thieu' => 'nullable|integer|min:0',
                'kinh_nghiem_toi_da' => 'nullable|integer|min:0',
                'luong_toi_thieu' => 'nullable|numeric|min:0',
                'luong_toi_da' => 'nullable|numeric|min:0',
                'trinh_do_hoc_van' => 'nullable|string|max:255',
                'lam_viec_tu_xa' => 'nullable|boolean',
                'tuyen_gap' => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            // Chuyển đổi text sang JSON nếu có
            $yeuCauArray = $request->filled('yeu_cau') ? array_filter(explode("\n", $request->yeu_cau)) : [];
            $phucLoiArray = $request->filled('phuc_loi') ? array_filter(explode("\n", $request->phuc_loi)) : [];
            $kyNangArray = $request->filled('ky_nang_yeu_cau') ? array_filter(explode("\n", $request->ky_nang_yeu_cau)) : [];

            $data = [
                'tieu_de' => $validated['tieu_de'],
                'phong_ban_id' => $validated['phong_ban_id'],
                'chuc_vu_id' => $validated['chuc_vu_id'],
                'vai_tro_id' => $validated['vai_tro_id'] ?? null,
                'so_vi_tri' => $validated['so_vi_tri'],
                'mo_ta_cong_viec' => $validated['mo_ta_cong_viec'],
                'yeu_cau' => !empty($yeuCauArray) ? json_encode(array_values($yeuCauArray)) : null,
                'phuc_loi' => !empty($phucLoiArray) ? json_encode(array_values($phucLoiArray)) : null,
                'ky_nang_yeu_cau' => !empty($kyNangArray) ? json_encode(array_values($kyNangArray)) : null,
                'han_nop_ho_so' => $validated['han_nop_ho_so'],
                'trang_thai' => $validated['trang_thai'],
                'loai_hop_dong' => $validated['loai_hop_dong'],
                'cap_do_kinh_nghiem' => $validated['cap_do_kinh_nghiem'],
                'kinh_nghiem_toi_thieu' => $validated['kinh_nghiem_toi_thieu'] ?? 0,
                'kinh_nghiem_toi_da' => $validated['kinh_nghiem_toi_da'] ?? 0,
                'luong_toi_thieu' => $validated['luong_toi_thieu'] ?? null,
                'luong_toi_da' => $validated['luong_toi_da'] ?? null,
                'trinh_do_hoc_van' => $validated['trinh_do_hoc_van'] ?? null,
                'lam_viec_tu_xa' => $request->boolean('lam_viec_tu_xa', false),
                'tuyen_gap' => $request->boolean('tuyen_gap', false),
            ];

            // Nếu trạng thái chuyển từ nháp sang đăng tuyển thì set thời gian đăng
            if ($validated['trang_thai'] == 'dang_tuyen' && $tinTuyenDung->trang_thai != 'dang_tuyen') {
                $data['thoi_gian_dang'] = now();
            }

            $tinTuyenDung->update($data);

            DB::commit();

            return redirect()->route('admin.tin-tuyen-dung.index')
                ->with('success', 'Cập nhật tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Update TinTuyenDung Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Xóa tin tuyển dụng
     */
    public function destroy($id)
    {
        try {
            $tinTuyenDung = TinTuyenDung::findOrFail($id);

            // Kiểm tra có ứng viên nào đang ứng tuyển không
            if ($tinTuyenDung->ungViens()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" vì đã có ứng viên ứng tuyển.');
            }

            $tinTuyenDung->delete();

            return redirect()->route('admin.tin-tuyen-dung.index')
                ->with('success', 'Xóa tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" thành công!');
        } catch (\Exception $e) {
            \Log::error('Delete TinTuyenDung Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Đăng tin tuyển dụng (Chuyển từ nháp sang đăng tuyển)
     */
    public function publish($id)
    {
        try {
            $tinTuyenDung = TinTuyenDung::findOrFail($id);

            if ($tinTuyenDung->trang_thai === 'dang_tuyen') {
                return redirect()->back()->with('warning', 'Tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" đã được đăng.');
            }

            if ($tinTuyenDung->trang_thai === 'ket_thuc') {
                return redirect()->back()->with('error', 'Tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" đã kết thúc, không thể đăng lại.');
            }

            $tinTuyenDung->update([
                'trang_thai' => 'dang_tuyen',
                'thoi_gian_dang' => now(),
            ]);

            return redirect()->back()->with('success', 'Đăng tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" thành công!');
        } catch (\Exception $e) {
            \Log::error('Publish TinTuyenDung Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tạm dừng tin tuyển dụng
     */
    public function stop($id)
    {
        try {
            $tinTuyenDung = TinTuyenDung::findOrFail($id);

            if ($tinTuyenDung->trang_thai === 'tam_dung') {
                return redirect()->back()->with('warning', 'Tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" đã được tạm dừng.');
            }

            if ($tinTuyenDung->trang_thai === 'ket_thuc') {
                return redirect()->back()->with('error', 'Tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" đã kết thúc, không thể tạm dừng.');
            }

            $tinTuyenDung->update([
                'trang_thai' => 'tam_dung',
            ]);

            return redirect()->back()->with('success', 'Tạm dừng tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" thành công!');
        } catch (\Exception $e) {
            \Log::error('Stop TinTuyenDung Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Kết thúc tin tuyển dụng
     */
    public function end($id)
    {
        try {
            $tinTuyenDung = TinTuyenDung::findOrFail($id);

            if ($tinTuyenDung->trang_thai === 'ket_thuc') {
                return redirect()->back()->with('warning', 'Tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" đã kết thúc.');
            }

            $tinTuyenDung->update([
                'trang_thai' => 'ket_thuc',
            ]);

            return redirect()->back()->with('success', 'Kết thúc tin tuyển dụng "<strong>' . $tinTuyenDung->tieu_de . '</strong>" thành công!');
        } catch (\Exception $e) {
            \Log::error('End TinTuyenDung Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function sendEmail(Request $request, $id)
    {
        try {
            $request->validate([
                'tieu_de' => 'required|string|max:255',
                'noi_dung' => 'required|string',
                'loai_ung_vien' => 'nullable|array',
                'loai_email' => 'nullable|in:thong_bao,phong_van,ket_qua',
            ]);

            $tinTuyenDung = TinTuyenDung::with(['phongBan'])->findOrFail($id);

            // Lấy danh sách ứng viên cần gửi email
            $query = $tinTuyenDung->ungViens();

            if ($request->filled('loai_ung_vien')) {
                $query->whereIn('trang_thai', $request->loai_ung_vien);
            }

            $ungViens = $query->get();

            if ($ungViens->isEmpty()) {
                return redirect()->back()->with('warning', 'Không có ứng viên nào để gửi email.');
            }

            $dem = 0;
            $errors = [];
            $loaiEmail = $request->loai_email ?? 'thong_bao';

            foreach ($ungViens as $ungVien) {
                if ($ungVien->email) {
                    try {
                        // Lưu lịch sử email - ĐÃ SỬA
                        $lichSuEmail = LichSuEmail::create([
                            'ung_vien_id' => $ungVien->id,
                            'tin_tuyen_dung_id' => $tinTuyenDung->id,
                            'nguoi_gui_id' => Auth::id(),
                            'tieu_de' => $request->tieu_de,
                            'noi_dung' => $request->noi_dung,
                            'trang_thai' => 'da_gui',
                            'thoi_gian_gui' => now(),
                            'email_nguoi_nhan' => $ungVien->email, // Lưu email người nhận
                            'loai_email' => $loaiEmail,
                        ]);

                        // Gửi email sử dụng Mailable
                        Mail::to($ungVien->email)->send(new TinTuyenDungMail(
                            $ungVien,
                            $tinTuyenDung,
                            $request->tieu_de,
                            $request->noi_dung
                        ));
                        $dem++;
                    } catch (\Exception $e) {
                        $errors[] = $ungVien->email . ': ' . $e->getMessage();
                    }
                }
            }

            $message = 'Đã gửi email thông báo cho <strong>' . $dem . '</strong> ứng viên.';
            if (!empty($errors)) {
                $message .= '<br>Lỗi: ' . implode('; ', $errors);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('SendEmail TinTuyenDung Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi email: ' . $e->getMessage());
        }
    }


    /**
     * Lấy thống kê số lượng ứng viên theo tin tuyển dụng
     */
    public function statistics(Request $request)
    {
        $stats = TinTuyenDung::with(['phongBan', 'chucVu'])
            ->withCount(['ungViens' => function ($query) {
                $query->where('trang_thai', 'cho_duyet');
            }])
            ->withCount(['ungViens as da_duyet_count' => function ($query) {
                $query->where('trang_thai', 'da_duyet');
            }])
            ->withCount(['ungViens as dat_count' => function ($query) {
                $query->where('trang_thai', 'dat');
            }])
            ->withCount(['ungViens as khong_dat_count' => function ($query) {
                $query->where('trang_thai', 'khong_dat');
            }])
            ->orderBy('ung_viens_count', 'desc')
            ->get();

        // Tổng quan
        $tongQuan = [
            'tong_tin' => TinTuyenDung::count(),
            'nhap' => TinTuyenDung::where('trang_thai', 'nhap')->count(),
            'dang_tuyen' => TinTuyenDung::where('trang_thai', 'dang_tuyen')->count(),
            'tam_dung' => TinTuyenDung::where('trang_thai', 'tam_dung')->count(),
            'ket_thuc' => TinTuyenDung::where('trang_thai', 'ket_thuc')->count(),
            'tong_ung_vien' => UngVien::count(),
        ];

        return view('admin.tin-tuyen-dung.statistics', compact('stats', 'tongQuan'));
    }
}
