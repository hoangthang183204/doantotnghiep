<?php
// app/Http/Controllers/Admin/HopDongLaoDongController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDongLaoDong;
use App\Models\NguoiDung;
use App\Models\ChucVu;
use App\Models\HoSoNguoiDung;
use App\Models\Luong;
use App\Models\PhuCap;
use App\Models\PhuCapNhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Exports\HopDongExport;
use Maatwebsite\Excel\Facades\Excel;

class HopDongLaoDongController extends Controller
{
    /**
     * Danh sách hợp đồng (chính)
     */
    public function index(Request $request)
    {
        $query = HopDongLaoDong::with(['hoSoNguoiDung', 'nguoiKy', 'chucVu']);
    
        // Tìm kiếm theo từ khóa
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('so_hop_dong', 'like', "%{$search}%")
                    ->orWhereHas('hoSoNguoiDung', function ($q) use ($search) {
                        $q->where('ma_nhan_vien', 'like', "%{$search}%")
                            ->orWhere('ho', 'like', "%{$search}%")
                            ->orWhere('ten', 'like', "%{$search}%");
                    });
            });
        }
    
        // Lọc theo loại hợp đồng
        if ($request->loai_hop_dong) {
            $query->where('loai_hop_dong', $request->loai_hop_dong);
        }
    
        // Lọc theo trạng thái hợp đồng
        if ($request->trang_thai_hop_dong) {
            $query->where('trang_thai_hop_dong', $request->trang_thai_hop_dong);
        }
    
        // Lọc theo trạng thái ký
        if ($request->trang_thai_ky) {
            $query->where('trang_thai_ky', $request->trang_thai_ky);
        }
    
        // 💡 THÊM MỚI: Bộ lọc theo trạng thái nộp file scan ký tay
        if ($request->has('file_scan')) {
            if ($request->file_scan === 'da_nop') {
                $query->whereNotNull('file_scan_ky');
            } elseif ($request->file_scan === 'chua_nop') {
                $query->whereNull('file_scan_ky');
            }
        }
    
        // Loại trừ hợp đồng đã hủy bỏ, hợp đồng hết hạn đã được tái ký thành công, và hợp đồng từ chối ký
        $query->where(function ($q) {
            $q->where('trang_thai_hop_dong', '!=', 'huy_bo')
                ->where('trang_thai_ky', '!=', 'tu_choi_ky')
                ->where(function ($subQ) {
                    $subQ->where('trang_thai_hop_dong', '!=', 'het_han')
                        ->orWhere(function ($innerQ) {
                            $innerQ->where('trang_thai_hop_dong', 'het_han')
                                ->where(function ($finalQ) {
                                    $finalQ->whereNull('trang_thai_tai_ky')
                                        ->orWhere('trang_thai_tai_ky', 'cho_tai_ky');
                                });
                        });
                });
        });
    
        $hopDongs = $query->latest()->paginate(20);
    
        // Cập nhật trạng thái hết hạn tự động
        foreach ($hopDongs as $hopDong) {
            if ($hopDong->ngay_ket_thuc && Carbon::parse($hopDong->ngay_ket_thuc)->lt(now()) && $hopDong->trang_thai_hop_dong !== 'het_han') {
                $hopDong->trang_thai_hop_dong = 'het_han';
                if (!$hopDong->trang_thai_tai_ky || $hopDong->trang_thai_tai_ky === 'cho_tai_ky') {
                    $hopDong->trang_thai_tai_ky = 'cho_tai_ky';
                }
                $hopDong->save();
            }
        }
    
        // Thống kê
        $now = now();
        $in30days = now()->addDays(30);
    
        $hieuLuc = HopDongLaoDong::where('trang_thai_hop_dong', 'hieu_luc')->count();
        $chuaCoHopDong = HoSoNguoiDung::whereDoesntHave('hopDongLaoDong')->count();
        $sapHetHan = HopDongLaoDong::where('trang_thai_hop_dong', 'hieu_luc')
            ->where('ngay_ket_thuc', '>', $now)
            ->where('ngay_ket_thuc', '<=', $in30days)
            ->count();
        $hetHanChuaTaiKy = HopDongLaoDong::where('trang_thai_tai_ky', 'cho_tai_ky')->count();
    
        return view('admin.hop-dong-lao-dong.index', compact('hopDongs', 'hieuLuc', 'chuaCoHopDong', 'sapHetHan', 'hetHanChuaTaiKy'));
    }

    /**
     * Danh sách hợp đồng của tôi (cho nhân viên)
     */
    public function cuaToi()
    {
        $user = Auth::user();
        $hopDongs = HopDongLaoDong::with(['hoSoNguoiDung', 'nguoiDung.phongBan', 'nguoiKy.hoSo', 'chucVu'])
            ->where('nguoi_dung_id', $user->id)
            ->whereIn('trang_thai_hop_dong', ['hieu_luc', 'chua_hieu_luc', 'het_han'])
            ->orderBy('created_at', 'desc')
            ->get();

        $hopDong = $hopDongs->where('trang_thai_hop_dong', 'hieu_luc')->first()
            ?? $hopDongs->where('trang_thai_hop_dong', 'chua_hieu_luc')->first()
            ?? $hopDongs->where('trang_thai_hop_dong', 'het_han')->first();

        if (!$hopDong) {
            return view('admin.hop-dong-lao-dong.cua-toi', compact('hopDong'))->with('message', 'Bạn chưa có hợp đồng nào được HR gửi.');
        }

        return view('admin.hop-dong-lao-dong.cua-toi', compact('hopDong'));
    }

    /**
     * Lưu trữ hợp đồng
     */
    public function luuTru(Request $request)
    {
        $query = HopDongLaoDong::with(['hoSoNguoiDung', 'nguoiKy', 'chucVu']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('so_hop_dong', 'like', "%{$search}%")
                    ->orWhereHas('hoSoNguoiDung', function ($q) use ($search) {
                        $q->where('ma_nhan_vien', 'like', "%{$search}%")
                            ->orWhere('ho', 'like', "%{$search}%")
                            ->orWhere('ten', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->loai_hop_dong) {
            $query->where('loai_hop_dong', $request->loai_hop_dong);
        }

        if ($request->trang_thai_ky) {
            $query->where('trang_thai_ky', $request->trang_thai_ky);
        }

        // Chỉ lấy hợp đồng đã hủy bỏ, hợp đồng hết hạn đã được tái ký, và hợp đồng từ chối ký
        $query->where(function ($q) {
            $q->where('trang_thai_hop_dong', 'huy_bo')
                ->orWhere('trang_thai_ky', 'tu_choi_ky')
                ->orWhere(function ($subQ) {
                    $subQ->where('trang_thai_hop_dong', 'het_han')
                        ->where('trang_thai_tai_ky', 'da_tai_ky');
                });
        });

        $hopDongsArchive = $query->latest()->paginate(20);

        return view('admin.hop-dong-lao-dong.luu-tru', compact('hopDongsArchive'));
    }

    /**
     * Form tạo mới hợp đồng
     */
    public function create(Request $request)
    {
        $selectedNhanVienId = $request->input('nguoi_dung_id');

        $allNhanViens = NguoiDung::whereHas('hoSo')
            ->where('trang_thai', 1)
            ->whereDoesntHave('vaiTros', function ($query) {
                $query->where('name', 'admin');
            })
            ->with(['hoSo', 'hopDongLaoDong', 'phuCapNhanViens.phuCap'])
            ->get();

        $nhanViens = $allNhanViens->filter(function ($nhanVien) {
            if ($nhanVien->hopDongLaoDong->isEmpty()) return true;

            foreach ($nhanVien->hopDongLaoDong as $hopDong) {
                if (($hopDong->trang_thai_ky == 'cho_ky' && $hopDong->trang_thai_hop_dong == 'tao_moi') ||
                    ($hopDong->trang_thai_ky == 'da_ky' && $hopDong->trang_thai_hop_dong == 'hieu_luc') ||
                    ($hopDong->trang_thai_ky == 'cho_ky' && $hopDong->trang_thai_hop_dong == 'chua_hieu_luc')
                ) {
                    return false;
                }
            }
            return true;
        });

        if ($selectedNhanVienId && !$nhanViens->contains('id', $selectedNhanVienId)) {
            $nhanVienTaiKy = NguoiDung::with('hoSo')->find($selectedNhanVienId);
            if ($nhanVienTaiKy) $nhanViens->push($nhanVienTaiKy);
        }

        $chucVus = ChucVu::all();
        $soHopDongTuDong = $this->generateSoHopDong();

        // Lấy danh sách phụ cấp để hiển thị trong form
        $phuCaps = PhuCap::where('trang_thai', 1)->get();

        return view('admin.hop-dong-lao-dong.create', compact('nhanViens', 'chucVus', 'selectedNhanVienId', 'soHopDongTuDong', 'phuCaps'));
    }

    /**
     * Lưu hợp đồng mới
     */
    public function store(Request $request)
    {
        if (!auth()->check()) return redirect()->route('login');

        $user = auth()->user();
        $hasPermission = $user->vaiTros()->whereIn('name', ['admin', 'hr'])->exists();
        if (!$hasPermission) return redirect()->back()->with('error', 'Bạn không có quyền tạo hợp đồng.');

        $request->validate([
            'nguoi_dung_id' => 'required|exists:nguoi_dung,id',
            'chuc_vu_id' => 'required|exists:chuc_vu,id',
            'so_hop_dong' => 'required|string|unique:hop_dong_lao_dong,so_hop_dong',
            'loai_hop_dong' => 'required|string',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after:ngay_bat_dau',
            'luong_co_ban' => 'required|numeric|min:0',
            'phu_cap' => 'nullable|numeric|min:0',
            'dia_diem_lam_viec' => 'required|string',
            'dieu_khoan' => 'required|string',
            'ghi_chu' => 'nullable|string',
            'file_hop_dong' => 'required|array|min:1',
            'file_hop_dong.*' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'file_dinh_kem' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'phu_cap_ids' => 'nullable|array',
            'phu_cap_ids.*' => 'exists:phu_cap,id',
        ]);

        $data = $request->all();
        $data['trang_thai_hop_dong'] = 'tao_moi';
        $data['trang_thai_ky'] = 'cho_ky';
        $data['created_by'] = Auth::id();

        // Xử lý file hợp đồng
        if ($request->hasFile('file_hop_dong')) {
            $filePaths = [];
            foreach ($request->file('file_hop_dong') as $file) {
                $filePaths[] = $file->store('hop_dong', 'public');
            }
            $data['duong_dan_file'] = implode(';', $filePaths);
        }

        // Xử lý file đính kèm
        if ($request->hasFile('file_dinh_kem')) {
            $data['file_dinh_kem'] = $request->file('file_dinh_kem')->store('file_dinh_kem', 'public');
        }

        // Tạo hợp đồng
        $hopDong = HopDongLaoDong::create($data);

        // ===== LƯU PHỤ CẤP VÀO BẢNG PHU_CAP_NHAN_VIEN =====
        if ($request->has('phu_cap_ids') && is_array($request->phu_cap_ids)) {
            foreach ($request->phu_cap_ids as $phuCapId) {
                $phuCap = PhuCap::find($phuCapId);
                if ($phuCap) {
                    PhuCapNhanVien::create([
                        'nguoi_dung_id' => $request->nguoi_dung_id,
                        'phu_cap_id' => $phuCapId,
                        'so_tien' => $phuCap->so_tien_mac_dinh,
                        'ngay_hieu_luc' => $request->ngay_bat_dau,
                        'ngay_ket_thuc' => $request->ngay_ket_thuc,
                        'trang_thai' => 'hieu_luc',
                        'ghi_chu' => 'Phụ cấp từ hợp đồng ' . $request->so_hop_dong,
                    ]);
                }
            }
        }

        return redirect()->route('admin.hop-dong.index')->with('success', 'Hợp đồng đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết hợp đồng
     */
    public function show($id)
    {
        $hopDong = HopDongLaoDong::with(['hoSoNguoiDung', 'nguoiDung.phongBan', 'nguoiKy.hoSo', 'chucVu', 'nguoiHuy.hoSo'])->findOrFail($id);
        return view('admin.hop-dong-lao-dong.show', compact('hopDong'));
    }

    /**
     * Form chỉnh sửa hợp đồng
     */
    public function edit($id)
    {
        $hopDong = HopDongLaoDong::with(['hoSoNguoiDung', 'chucVu', 'nguoiDung.phuCapNhanViens.phuCap'])->findOrFail($id);

        if (($hopDong->trang_thai_ky === 'cho_ky' && $hopDong->trang_thai_hop_dong === 'chua_hieu_luc') ||
            $hopDong->trang_thai_ky === 'tu_choi_ky'
        ) {
            return redirect()->route('admin.hop-dong.index')->with('error', 'Không thể sửa đổi hợp đồng này.');
        }

        $allNhanViens = NguoiDung::whereHas('hoSo')->where('trang_thai', 1)
            ->whereDoesntHave('vaiTros', function ($q) {
                $q->where('name', 'admin');
            })
            ->with(['hoSo', 'hopDongLaoDong'])->get();

        $nhanViens = $allNhanViens->filter(function ($nhanVien) use ($hopDong) {
            if ($nhanVien->id == $hopDong->nguoi_dung_id) return true;
            if ($nhanVien->hopDongLaoDong->isEmpty()) return true;
            foreach ($nhanVien->hopDongLaoDong as $hopDongItem) {
                if (($hopDongItem->trang_thai_ky == 'cho_ky' && $hopDongItem->trang_thai_hop_dong == 'tao_moi') ||
                    ($hopDongItem->trang_thai_ky == 'da_ky' && $hopDongItem->trang_thai_hop_dong == 'hieu_luc') ||
                    ($hopDongItem->trang_thai_ky == 'cho_ky' && $hopDongItem->trang_thai_hop_dong == 'chua_hieu_luc')
                ) {
                    return false;
                }
            }
            return true;
        });

        $chucVus = ChucVu::all();

        // Lấy danh sách phụ cấp để hiển thị trong form
        $phuCaps = PhuCap::where('trang_thai', 1)->get();

        // Lấy danh sách phụ cấp đã chọn của nhân viên
        $selectedPhuCapIds = $hopDong->nguoiDung->phuCapNhanViens->pluck('phu_cap_id')->toArray();

        return view('admin.hop-dong-lao-dong.edit', compact('hopDong', 'nhanViens', 'chucVus', 'phuCaps', 'selectedPhuCapIds'));
    }

    /**
     * Cập nhật hợp đồng
     */
    public function update(Request $request, $id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);

        if (($hopDong->trang_thai_ky === 'cho_ky' && $hopDong->trang_thai_hop_dong === 'chua_hieu_luc') ||
            $hopDong->trang_thai_ky === 'tu_choi_ky'
        ) {
            return redirect()->route('admin.hop-dong.index')->with('error', 'Không thể sửa đổi hợp đồng này.');
        }

        $validationRules = [
            'chuc_vu_id' => 'required|exists:chuc_vu,id',
            'loai_hop_dong' => 'required|string',
            'ngay_bat_dau' => 'required|date',
            'luong_co_ban' => 'required|numeric|min:0',
            'phu_cap' => 'nullable|numeric|min:0',
            'dia_diem_lam_viec' => 'required|string',
            'ghi_chu' => 'nullable|string',
            'trang_thai_ky' => 'required|in:cho_ky,da_ky',
            'file_hop_dong' => 'nullable|array',
            'file_hop_dong.*' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'file_dinh_kem' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'phu_cap_ids' => 'nullable|array',
            'phu_cap_ids.*' => 'exists:phu_cap,id',
        ];

        if ($request->loai_hop_dong !== 'khong_xac_dinh_thoi_han') {
            $validationRules['ngay_ket_thuc'] = 'required|date|after:ngay_bat_dau';
        }

        $request->validate($validationRules);

        $data = $request->except(['file_hop_dong', 'file_dinh_kem', 'phu_cap_ids']);

        // Xử lý file hợp đồng
        if ($request->hasFile('file_hop_dong')) {
            if ($hopDong->duong_dan_file) {
                foreach (explode(';', $hopDong->duong_dan_file) as $oldFile) {
                    if (trim($oldFile)) Storage::disk('public')->delete(trim($oldFile));
                }
            }
            $filePaths = [];
            foreach ($request->file('file_hop_dong') as $file) {
                if ($file) {
                    $filePaths[] = $file->store('hop_dong', 'public');
                }
            }
            if (!empty($filePaths)) {
                $data['duong_dan_file'] = implode(';', $filePaths);
            }
        }

        // Xử lý file đính kèm
        if ($request->hasFile('file_dinh_kem')) {
            if ($hopDong->file_dinh_kem) Storage::disk('public')->delete($hopDong->file_dinh_kem);
            $data['file_dinh_kem'] = $request->file('file_dinh_kem')->store('file_dinh_kem', 'public');
        }

        // Cập nhật hợp đồng
        $hopDong->update($data);

        // ===== CẬP NHẬT PHỤ CẤP =====
        // Xóa phụ cấp cũ
        PhuCapNhanVien::where('nguoi_dung_id', $hopDong->nguoi_dung_id)
            ->where('ghi_chu', 'LIKE', '%từ hợp đồng ' . $hopDong->so_hop_dong . '%')
            ->delete();

        // Thêm phụ cấp mới
        if ($request->has('phu_cap_ids') && is_array($request->phu_cap_ids)) {
            foreach ($request->phu_cap_ids as $phuCapId) {
                $phuCap = PhuCap::find($phuCapId);
                if ($phuCap) {
                    PhuCapNhanVien::create([
                        'nguoi_dung_id' => $hopDong->nguoi_dung_id,
                        'phu_cap_id' => $phuCapId,
                        'so_tien' => $phuCap->so_tien_mac_dinh,
                        'ngay_hieu_luc' => $request->ngay_bat_dau,
                        'ngay_ket_thuc' => $request->ngay_ket_thuc,
                        'trang_thai' => 'hieu_luc',
                        'ghi_chu' => 'Phụ cấp từ hợp đồng ' . $hopDong->so_hop_dong,
                    ]);
                }
            }
        }

        return redirect()->route('admin.hop-dong.index')->with('success', 'Cập nhật hợp đồng thành công');
    }

    /**
     * Xóa hợp đồng
     */
    public function destroy($id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);
        if ($hopDong->duong_dan_file) {
            foreach (explode(';', $hopDong->duong_dan_file) as $file) {
                if (trim($file)) Storage::disk('public')->delete(trim($file));
            }
        }
        $hopDong->delete();
        return redirect()->route('admin.hop-dong.index')->with('success', 'Xóa hợp đồng thành công');
    }

    /**
     * Gửi hợp đồng cho nhân viên (phê duyệt)
     */
    public function pheDuyetHopDong($id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);
        $user = Auth::user();
        $userRoles = optional($user->vaiTros)->pluck('name')->toArray();

        if (!in_array('admin', $userRoles) && !in_array('hr', $userRoles)) {
            return redirect()->back()->with('error', 'Bạn không có quyền gửi hợp đồng cho nhân viên');
        }

        if ($hopDong->trang_thai_hop_dong !== 'tao_moi') {
            return redirect()->back()->with('error', 'Hợp đồng không ở trạng thái tạo mới');
        }

        $hopDong->update(['trang_thai_hop_dong' => 'chua_hieu_luc', 'trang_thai_ky' => 'cho_ky']);

        // Gửi thông báo nếu có notification class
        try {
            $hopDong->nguoiDung->notify(new \App\Notifications\HopDongApprovedNotification($hopDong));
        } catch (\Exception $e) {
            // Bỏ qua nếu notification chưa được tạo
        }

        return redirect()->route('admin.hop-dong.show', $hopDong->id)->with('success', 'Gửi hợp đồng cho nhân viên thành công!');
    }

    /**
     * Hiển thị form ký hợp đồng (cho nhân viên)
     */
    public function kyHopDong($id)
    {
        $hopDong = HopDongLaoDong::with(['hoSoNguoiDung', 'nguoiKy', 'chucVu', 'nguoiGuiHopDong.hoSo'])->findOrFail($id);

        if ($hopDong->nguoi_dung_id !== Auth::id()) {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Bạn không có quyền ký hợp đồng này.');
        }

        if (!in_array($hopDong->trang_thai_hop_dong, ['hieu_luc', 'chua_hieu_luc', 'het_han'])) {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Hợp đồng này chưa được HR phê duyệt.');
        }

        if ($hopDong->trang_thai_ky === 'da_ky') {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Hợp đồng này đã được ký.');
        }

        return view('admin.hop-dong-lao-dong.ky-hop-dong', compact('hopDong'));
    }

    /**
     * Xử lý ký hợp đồng (nhân viên upload file)
     */
    public function xuLyKyHopDong(Request $request, $id)
    {
        $request->validate([
            'file_hop_dong_da_ky' => 'required|array',
            'file_hop_dong_da_ky.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $hopDong = HopDongLaoDong::findOrFail($id);

        if ($hopDong->nguoi_dung_id !== Auth::id()) {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Bạn không có quyền ký hợp đồng này.');
        }

        if (!in_array($hopDong->trang_thai_hop_dong, ['hieu_luc', 'chua_hieu_luc', 'het_han'])) {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Hợp đồng này chưa được HR phê duyệt.');
        }

        if ($hopDong->trang_thai_ky === 'da_ky') {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Hợp đồng này đã được ký.');
        }

        try {
            $uploadedFiles = [];
            if ($request->hasFile('file_hop_dong_da_ky')) {
                foreach ($request->file('file_hop_dong_da_ky') as $index => $file) {
                    $fileName = 'hop_dong_da_ky_' . $hopDong->so_hop_dong . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                    $uploadedFiles[] = $file->storeAs('hop_dong_da_ky', $fileName, 'public');
                }
            }

            $updateData = [
                'file_hop_dong_da_ky' => implode(';', $uploadedFiles),
                'trang_thai_ky' => 'da_ky',
                'nguoi_ky_id' => Auth::id(),
                'thoi_gian_ky' => now(),
            ];

            if (in_array($hopDong->trang_thai_hop_dong, ['tao_moi', 'chua_hieu_luc'])) {
                $updateData['trang_thai_hop_dong'] = 'hieu_luc';
            }

            $hopDong->update($updateData);

            // Tạo bản ghi lương
            $existingLuong = Luong::where('hop_dong_lao_dong_id', $hopDong->id)->first();
            if (!$existingLuong) {
                Luong::create([
                    'nguoi_dung_id' => $hopDong->nguoi_dung_id,
                    'hop_dong_lao_dong_id' => $hopDong->id,
                    'luong_co_ban' => $hopDong->luong_co_ban,
                    'phu_cap' => $hopDong->phu_cap ?? 0,
                ]);
            }

            // Gửi thông báo
            try {
                $hrUsers = NguoiDung::whereHas('vaiTros', fn($q) => $q->where('name', 'hr'))->get();
                $adminUsers = NguoiDung::whereHas('vaiTros', fn($q) => $q->where('name', 'admin'))->get();
                foreach ($hrUsers as $hr) $hr->notify(new \App\Notifications\HopDongSignedNotification($hopDong));
                foreach ($adminUsers as $admin) $admin->notify(new \App\Notifications\HopDongSignedNotification($hopDong));
            } catch (\Exception $e) {
                // Bỏ qua nếu notification chưa được tạo
            }

            return redirect()->route('admin.hop-dong.cua-toi')->with('success', 'Ký hợp đồng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Có lỗi xảy ra khi ký hợp đồng.');
        }
    }

    /**
     * Từ chối ký hợp đồng (cho nhân viên)
     */
    public function tuChoiKy(Request $request, $id)
    {
        $request->validate(['ly_do_tu_choi' => 'required|string|min:10|max:1000']);

        $hopDong = HopDongLaoDong::findOrFail($id);

        if ($hopDong->nguoi_dung_id !== Auth::id()) {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Bạn không có quyền từ chối ký hợp đồng này.');
        }

        if (!in_array($hopDong->trang_thai_hop_dong, ['hieu_luc', 'chua_hieu_luc', 'het_han'])) {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Hợp đồng này chưa được HR phê duyệt.');
        }

        if ($hopDong->trang_thai_ky === 'da_ky') {
            return redirect()->route('admin.hop-dong.cua-toi')->with('error', 'Hợp đồng này đã được ký.');
        }

        $hopDong->update(['trang_thai_ky' => 'tu_choi_ky', 'ghi_chu' => 'Từ chối ký: ' . $request->ly_do_tu_choi]);

        return redirect()->route('admin.hop-dong.cua-toi')->with('success', 'Đã từ chối ký hợp đồng thành công.');
    }

    /**
     * Hủy hợp đồng
     */
    public function huyHopDong(Request $request, $id)
    {
        $request->validate(['ly_do_huy' => 'required|string|max:1000']);

        $hopDong = HopDongLaoDong::findOrFail($id);
        $user = Auth::user();
        $userRoles = optional($user->vaiTros)->pluck('name')->toArray();

        if (!in_array('admin', $userRoles) && !in_array('hr', $userRoles)) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy hợp đồng');
        }

        $hopDong->update([
            'trang_thai_hop_dong' => 'huy_bo',
            'ly_do_huy' => $request->ly_do_huy,
            'nguoi_huy_id' => Auth::id(),
            'thoi_gian_huy' => now(),
        ]);

        return redirect()->route('admin.hop-dong.show', $hopDong->id)->with('success', 'Hủy hợp đồng thành công.');
    }

    /**
     * Ẩn hợp đồng khỏi danh sách chính
     */
    public function anKhoiDanhSach(Request $request)
    {
        $request->validate(['hop_dong_id' => 'required|exists:hop_dong_lao_dong,id']);
        $hopDong = HopDongLaoDong::findOrFail($request->hop_dong_id);
        $user = Auth::user();

        if (!in_array($user->vaiTro->name ?? '', ['admin', 'hr'])) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        if ($hopDong->trang_thai_hop_dong !== 'het_han') {
            return redirect()->back()->with('error', 'Chỉ có thể ẩn hợp đồng đã hết hạn.');
        }

        $hopDong->update([
            'trang_thai_tai_ky' => 'da_tai_ky',
            'ghi_chu' => 'Đã ẩn khỏi danh sách chính bởi ' . $user->email,
        ]);

        return redirect()->back()->with('success', 'Đã ẩn hợp đồng khỏi danh sách chính thành công.');
    }

    /**
     * Xuất Excel danh sách hợp đồng
     */
    public function export(Request $request)
    {
        $query = HopDongLaoDong::with(['hoSoNguoiDung', 'chucVu', 'nguoiHuy.hoSo']);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('so_hop_dong', 'like', "%{$search}%")
                    ->orWhereHas('hoSoNguoiDung', fn($q) => $q->where('ma_nhan_vien', 'like', "%{$search}%")->orWhere('ho', 'like', "%{$search}%")->orWhere('ten', 'like', "%{$search}%"));
            });
        }

        if ($request->loai_hop_dong) $query->where('loai_hop_dong', $request->loai_hop_dong);
        if ($request->trang_thai_hop_dong) $query->where('trang_thai_hop_dong', $request->trang_thai_hop_dong);
        if ($request->trang_thai_ky) $query->where('trang_thai_ky', $request->trang_thai_ky);

        $hopDongs = $query->latest()->get();
        return Excel::download(new HopDongExport($hopDongs), 'danh_sach_hop_dong_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Thống kê hợp đồng
     */
    public function thongKe(Request $request)
    {
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');
        $query = HopDongLaoDong::query();

        if ($tuNgay && $denNgay) {
            $query->whereBetween('created_at', [$tuNgay . ' 00:00:00', $denNgay . ' 23:59:59']);
        }

        $tongHopDong = (clone $query)->count();
        $hopDongHieuLuc = (clone $query)->where('trang_thai_hop_dong', 'hieu_luc')->count();
        $hopDongChuaHieuLuc = (clone $query)->where('trang_thai_hop_dong', 'chua_hieu_luc')->count();
        $hopDongHetHan = (clone $query)->where('trang_thai_hop_dong', 'het_han')->count();
        $hopDongHuyBo = (clone $query)->where('trang_thai_hop_dong', 'huy_bo')->count();
        $hopDongTaoMoi = (clone $query)->where('trang_thai_hop_dong', 'tao_moi')->count();

        $thongKeLoaiHopDong = (clone $query)->selectRaw('loai_hop_dong, COUNT(*) as so_luong')->groupBy('loai_hop_dong')->get()->keyBy('loai_hop_dong');
        $thongKeTrangThaiKy = (clone $query)->selectRaw('trang_thai_ky, COUNT(*) as so_luong')->groupBy('trang_thai_ky')->get()->keyBy('trang_thai_ky');
        $thongKeTheoPhongBan = (clone $query)->join('nguoi_dung', 'hop_dong_lao_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->join('phong_ban', 'nguoi_dung.phong_ban_id', '=', 'phong_ban.id')
            ->selectRaw('phong_ban.ten_phong_ban, COUNT(*) as so_luong')->groupBy('phong_ban.id', 'phong_ban.ten_phong_ban')->orderBy('so_luong', 'desc')->get();

        $hopDongSapHetHan = HopDongLaoDong::where('trang_thai_hop_dong', 'hieu_luc')
            ->where('ngay_ket_thuc', '>', now())->where('ngay_ket_thuc', '<=', now()->addDays(30))
            ->with(['hoSoNguoiDung', 'chucVu'])->get();

        return view('admin.hop-dong-lao-dong.thong-ke', compact('tongHopDong', 'hopDongHieuLuc', 'hopDongChuaHieuLuc', 'hopDongHetHan', 'hopDongHuyBo', 'hopDongTaoMoi', 'thongKeLoaiHopDong', 'thongKeTrangThaiKy', 'thongKeTheoPhongBan', 'hopDongSapHetHan', 'tuNgay', 'denNgay'));
    }

    public function guiKy($id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);

        $hopDong->trang_thai_ky = 'cho_ky';

        // nếu có trạng thái hợp đồng
        if ($hopDong->trang_thai_hop_dong === 'tao_moi') {
            $hopDong->trang_thai_hop_dong = 'chua_hieu_luc';
        }

        $hopDong->save();

        return redirect()
            ->route('admin.hop-dong.show', $id)
            ->with('success', 'Đã gửi hợp đồng cho nhân viên ký');
    }

    public function huy(Request $request, $id)
    {
        $request->validate([
            'ly_do_huy' => 'required'
        ]);

        $hopDong = HopDongLaoDong::findOrFail($id);

        $hopDong->trang_thai_hop_dong = 'huy_bo';
        $hopDong->ly_do_huy = $request->ly_do_huy;

        $hopDong->save();

        return redirect()
            ->route('admin.hop-dong.show', $id)
            ->with('success', 'Đã hủy hợp đồng');
    }

    /**
     * API lấy thông tin nhân viên (cho AJAX)
     */
    public function getNhanVienInfo($id)
    {
        $nhanVien = NguoiDung::with(['chucVu', 'phuCapNhanViens.phuCap'])
            ->find($id);
        
        if (!$nhanVien) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy nhân viên']);
        }
        
        return response()->json([
            'success' => true,
            'luong_co_ban' => $nhanVien->chucVu->luong_co_ban ?? 0,
            'phu_cap_ids' => $nhanVien->phuCapNhanViens->pluck('phu_cap_id')->toArray(),
        ]);
    }

    /**
     * Tạo số hợp đồng tự động
     */
    private function generateSoHopDong()
    {
        $year = date('Y');
        do {
            $soHopDong = 'HD' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . '-' . $year;
        } while (HopDongLaoDong::where('so_hop_dong', $soHopDong)->exists());
        return $soHopDong;
    }
}