<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDongLaoDong;
use App\Models\NguoiDung;
use App\Models\ChucVu;
use App\Models\HoSoNguoiDung;
use App\Models\Luong;
use App\Models\PhuCap;
use App\Models\PhuCapNhanVien;
use App\Models\LichSuTaiKy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Exports\HopDongExport;
use App\Mail\HopDongGuiKyMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class HopDongLaoDongController extends Controller
{
    /**
     * Danh sách hợp đồng (chính)
     */
    public function index(Request $request)
    {
        $query = HopDongLaoDong::with(['hoSoNguoiDung', 'nguoiKy', 'chucVu', 'nguoiDuyet']);

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

        // 🔥 Lọc theo trạng thái duyệt
        if ($request->trang_thai_duyet) {
            $query->where('trang_thai_duyet', $request->trang_thai_duyet);
        }

        // Bộ lọc theo trạng thái nộp file scan ký tay
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

        // 🔥 Thống kê chờ duyệt
        $choDuyet = HopDongLaoDong::where('trang_thai_duyet', 'cho_duyet')->count();

        return view('admin.hop-dong-lao-dong.index', compact(
            'hopDongs',
            'hieuLuc',
            'chuaCoHopDong',
            'sapHetHan',
            'hetHanChuaTaiKy',
            'choDuyet'
        ));
    }

    /**
     * Danh sách hợp đồng của tôi (cho nhân viên)
     */
    public function cuaToi()
    {
        $user = Auth::user();
        $hopDongs = HopDongLaoDong::with(['hoSoNguoiDung', 'nguoiDung.phongBan', 'nguoiKy.hoSo', 'chucVu', 'nguoiDuyet'])
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
        $query = HopDongLaoDong::with(['hoSoNguoiDung', 'nguoiKy', 'chucVu', 'nguoiDuyet']);

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

        $query->where(function ($q) {
            $q->where('trang_thai_hop_dong', 'huy_bo')
                ->orWhere('trang_thai_ky', 'tu_choi_ky')
                ->orWhere('trang_thai_duyet', 'tu_choi')
                ->orWhere(function ($subQ) {
                    $subQ->where('trang_thai_hop_dong', 'het_han')
                        ->where('trang_thai_tai_ky', 'da_tai_ky'); // Hết hạn + đã tái ký
                })
                ->orWhere(function ($subQ) {
                    $subQ->where('trang_thai_ky', 'da_ky')
                        ->where('trang_thai_tai_ky', 'da_tai_ky'); // Đã ký + đã tái ký (hợp đồng cũ)
                });
        });

        // 🔥 LOẠI TRỪ: Không đưa vào lưu trữ nếu đang ở trạng thái hiệu lực hoặc chưa hiệu lực
        $query->whereNotIn('trang_thai_hop_dong', ['hieu_luc', 'chua_hieu_luc']);

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
        $phuCaps = PhuCap::where('trang_thai', 1)->get();

        return view('admin.hop-dong-lao-dong.create', compact(
            'nhanViens',
            'chucVus',
            'selectedNhanVienId',
            'soHopDongTuDong',
            'phuCaps'
        ));
    }

    /**
     * Lưu hợp đồng mới
     */
    public function store(Request $request)
    {
        if (!auth()->check()) return redirect()->route('login');

        $user = auth()->user();
        $hasPermission = $user->vaiTros()->whereIn('name', ['admin', 'hr'])->exists();
        if (!$hasPermission) {
            return redirect()->back()->with('error', 'Bạn không có quyền tạo hợp đồng.');
        }

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

        // 🔥 TRẠNG THÁI MỚI: Tạo mới + Chờ duyệt
        $data['trang_thai_hop_dong'] = HopDongLaoDong::TRANG_THAI_TAO_MOI;
        $data['trang_thai_ky'] = HopDongLaoDong::TRANG_THAI_KY_CHO_KY;
        $data['trang_thai_duyet'] = HopDongLaoDong::TRANG_THAI_DUYET_CHO_DUYET; // 🔥 Chờ duyệt
        $data['created_by'] = Auth::id();

        // Xử lý phụ cấp ID
        if ($request->has('phu_cap_ids') && is_array($request->phu_cap_ids)) {
            $data['phu_cap_id'] = $request->phu_cap_ids[0] ?? null;
            $data['phu_cap'] = json_encode($request->phu_cap_ids);
        }

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

        // Lưu phụ cấp vào bảng phu_cap_nhan_vien
        PhuCapNhanVien::where('nguoi_dung_id', $request->nguoi_dung_id)
            ->where('trang_thai', 'hieu_luc')
            ->delete();

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

        // 🔥 GỬI THÔNG BÁO CHO ADMIN/GIÁM ĐỐC (trong hệ thống)
        $this->notifyAdminsAboutNewContract($hopDong);

        return redirect()->route('admin.hop-dong.index')
            ->with('success', '✅ Hợp đồng đã được tạo và gửi lên Giám đốc duyệt.');
    }

    /**
     * 🔥 Gửi thông báo cho Admin/Giám đốc khi có hợp đồng mới (trong hệ thống)
     */
    private function notifyAdminsAboutNewContract($hopDong)
    {
        try {
            // Kiểm tra class Notification có tồn tại không
            if (!class_exists('\App\Notifications\HopDongChoDuyetNotification')) {
                Log::warning('Notification HopDongChoDuyetNotification chưa được tạo');
                return;
            }

            $admins = NguoiDung::whereHas('vaiTros', function ($q) {
                $q->whereIn('name', ['admin']);
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\HopDongChoDuyetNotification($hopDong));
            }
        } catch (\Exception $e) {
            Log::error('Gửi thông báo cho Admin thất bại: ' . $e->getMessage());
        }
    }

    /**
     * 🔥 DUYỆT HỢP ĐỒNG (Cho Admin/Giám đốc)
     */
    public function duyet(Request $request, $id)
    {
        $hopDong = HopDongLaoDong::with(['nguoiDung', 'hoSoNguoiDung'])->findOrFail($id);

        $user = Auth::user();
        $roleName = $user->vaiTros->first()->name ?? '';
        if (!in_array($roleName, ['admin'])) {
            return redirect()->back()->with('error', 'Bạn không có quyền duyệt hợp đồng.');
        }

        if ($hopDong->trang_thai_duyet !== HopDongLaoDong::TRANG_THAI_DUYET_CHO_DUYET) {
            return redirect()->back()->with('error', 'Hợp đồng này đã được xử lý.');
        }

        $hopDong->update([
            'trang_thai_duyet' => HopDongLaoDong::TRANG_THAI_DUYET_DA_DUYET,
            'nguoi_duyet_id' => Auth::id(),
            'thoi_gian_duyet' => now(),
            'trang_thai_hop_dong' => HopDongLaoDong::TRANG_THAI_CHUA_HIEU_LUC,
        ]);

        // 🔥 GỬI THÔNG BÁO CHO HR (trong hệ thống)
        $hrUsers = NguoiDung::whereHas('vaiTros', function ($q) {
            $q->where('name', 'hr');
        })->get();

        foreach ($hrUsers as $hr) {
            try {
                $hr->notify(new \App\Notifications\HopDongDuyetNotification($hopDong, 'duyet'));
            } catch (\Exception $e) {
                Log::error('Gửi thông báo duyệt hợp đồng cho HR thất bại: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.hop-dong.show', $hopDong->id)
            ->with('success', '✅ Đã duyệt hợp đồng. HR có thể gửi cho nhân viên ký.');
    }
    /**
     * 🔥 TỪ CHỐI DUYỆT HỢP ĐỒNG (Cho Admin/Giám đốc)
     */
    public function tuChoiDuyet(Request $request, $id)
    {
        $request->validate([
            'ly_do_tu_choi' => 'required|string|min:10|max:1000',
        ]);

        $hopDong = HopDongLaoDong::with(['nguoiDung', 'hoSoNguoiDung'])->findOrFail($id);

        $user = Auth::user();
        $roleName = $user->vaiTros->first()->name ?? '';
        if (!in_array($roleName, ['admin'])) {
            return redirect()->back()->with('error', 'Bạn không có quyền từ chối duyệt hợp đồng.');
        }

        if ($hopDong->trang_thai_duyet !== HopDongLaoDong::TRANG_THAI_DUYET_CHO_DUYET) {
            return redirect()->back()->with('error', 'Hợp đồng này đã được xử lý.');
        }

        // 🔥 CẬP NHẬT ĐẦY ĐỦ THÔNG TIN
        $hopDong->update([
            'trang_thai_duyet' => HopDongLaoDong::TRANG_THAI_DUYET_TU_CHOI,
            'nguoi_duyet_id' => Auth::id(),
            'thoi_gian_duyet' => now(),
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
            'trang_thai_hop_dong' => HopDongLaoDong::TRANG_THAI_HUY_BO,
            'nguoi_huy_id' => Auth::id(),      // 🔥 LƯU NGƯỜI HỦY
            'thoi_gian_huy' => now(),          // 🔥 LƯU THỜI GIAN HỦY
            'trang_thai_ky' => 'tu_choi_ky',   // 🔥 CẬP NHẬT TRẠNG THÁI KÝ
        ]);

        // Gửi thông báo cho HR
        $hrUsers = NguoiDung::whereHas('vaiTros', function ($q) {
            $q->where('name', 'hr');
        })->get();

        foreach ($hrUsers as $hr) {
            try {
                $hr->notify(new \App\Notifications\HopDongDuyetNotification($hopDong, 'tu_choi'));
            } catch (\Exception $e) {
                Log::error('Gửi thông báo từ chối duyệt hợp đồng cho HR thất bại: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.hop-dong.show', $hopDong->id)
            ->with('success', '✅ Đã từ chối duyệt hợp đồng.');
    }

    /**
     * GỬI HỢP ĐỒNG CHO NHÂN VIÊN (HR thực hiện)
     */
    public function guiKy($id)
    {
        $hopDong = HopDongLaoDong::with(['nguoiDung', 'hoSoNguoiDung'])->findOrFail($id);

        // 🔥 KIỂM TRA: Chỉ gửi khi đã được duyệt
        if ($hopDong->trang_thai_duyet !== HopDongLaoDong::TRANG_THAI_DUYET_DA_DUYET) {
            return redirect()->back()->with('error', '❌ Hợp đồng chưa được Giám đốc duyệt. Không thể gửi cho nhân viên.');
        }

        if ($hopDong->trang_thai_hop_dong === HopDongLaoDong::TRANG_THAI_HUY_BO) {
            return redirect()->back()->with('error', '❌ Hợp đồng đã bị hủy.');
        }

        if ($hopDong->trang_thai_ky === HopDongLaoDong::TRANG_THAI_KY_DA_KY) {
            return redirect()->back()->with('error', '❌ Hợp đồng đã được ký rồi.');
        }

        // 🔥 KIỂM TRA ĐÃ GỬI CHƯA
        if ($hopDong->thoi_gian_gui) {
            return redirect()->back()->with('error', '❌ Hợp đồng đã được gửi cho nhân viên từ trước.');
        }

        // 🔥 LƯU THỜI GIAN GỬI
        $hopDong->update([
            'trang_thai_ky' => HopDongLaoDong::TRANG_THAI_KY_CHO_KY,
            'trang_thai_hop_dong' => HopDongLaoDong::TRANG_THAI_CHUA_HIEU_LUC,
            'thoi_gian_gui' => now(),
        ]);

        // 🔥 LẤY THÔNG TIN NHÂN VIÊN
        $nhanVien = NguoiDung::with('hoSo')->find($hopDong->nguoi_dung_id);

        // 🔥 GỬI THÔNG BÁO TRONG HỆ THỐNG CHO NHÂN VIÊN
        if ($nhanVien) {
            try {
                $nhanVien->notify(new \App\Notifications\HopDongGuiKyNotification($hopDong));
            } catch (\Exception $e) {
                Log::error('Gửi thông báo hợp đồng cho nhân viên thất bại: ' . $e->getMessage());
            }
        }

        // 🔥 GỬI EMAIL CHO NHÂN VIÊN
        if ($nhanVien && $nhanVien->email) {
            try {
                Mail::to($nhanVien->email)->send(new HopDongGuiKyMail($hopDong));
                Log::info('Đã gửi email hợp đồng cho nhân viên: ' . $nhanVien->email);
            } catch (\Exception $e) {
                Log::error('Gửi email hợp đồng cho nhân viên thất bại: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.hop-dong.show', $hopDong->id)
            ->with('success', '✅ Đã gửi hợp đồng cho nhân viên ký và email thông báo.');
    }

    /**
     * Hiển thị chi tiết hợp đồng
     */
    public function show($id)
    {
        $hopDong = HopDongLaoDong::with([
            'hoSoNguoiDung',
            'nguoiDung.phongBan',
            'nguoiKy.hoSo',
            'nguoiDuyet.hoSo', // 🔥 MỚI
            'chucVu',
            'nguoiHuy.hoSo',
            'nguoiGuiHopDong.hoSo'
        ])->findOrFail($id);

        $user = Auth::user();
        $roleName = $user->vaiTros->first()->name ?? '';

        return view('admin.hop-dong-lao-dong.show', compact('hopDong', 'roleName'));
    }

    /**
     * Form chỉnh sửa hợp đồng
     */
    public function edit($id)
    {
        $hopDong = HopDongLaoDong::with(['hoSoNguoiDung', 'chucVu', 'nguoiDung.phuCapNhanViens.phuCap'])->findOrFail($id);

        // 🔥 CHẶN SỬA KHI HỢP ĐỒNG ĐÃ BỊ TỪ CHỐI HOẶC HỦY
        if ($hopDong->trang_thai_ky === 'tu_choi_ky') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể sửa hợp đồng đã bị nhân viên từ chối ký. Vui lòng tạo lại hợp đồng mới.');
        }

        if ($hopDong->trang_thai_hop_dong === 'huy_bo') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể sửa hợp đồng đã bị hủy bỏ.');
        }

        // 🔥 CHẶN SỬA KHI HỢP ĐỒNG ĐÃ DUYỆT HOẶC ĐÃ GỬI CHO NHÂN VIÊN
        if ($hopDong->trang_thai_duyet === 'da_duyet') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể sửa hợp đồng đã được duyệt.');
        }

        if ($hopDong->trang_thai_ky === 'da_ky') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể sửa hợp đồng đã được ký.');
        }

        // Nếu hợp đồng đã gửi cho nhân viên nhưng chưa ký (trang_thai_ky = cho_ky)
        // Vẫn cho phép sửa nếu chưa duyệt
        if ($hopDong->trang_thai_ky === 'cho_ky' && $hopDong->trang_thai_duyet !== 'da_duyet') {
            // Cho phép sửa nhưng hiển thị cảnh báo
            session()->flash('warning', '⚠️ Hợp đồng đã được gửi cho nhân viên. Việc sửa đổi có thể ảnh hưởng đến quá trình ký.');
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
        $phuCaps = PhuCap::where('trang_thai', 1)->get();
        $selectedPhuCapIds = $hopDong->nguoiDung->phuCapNhanViens->pluck('phu_cap_id')->toArray();

        return view('admin.hop-dong-lao-dong.edit', compact(
            'hopDong',
            'nhanViens',
            'chucVus',
            'phuCaps',
            'selectedPhuCapIds'
        ));
    }

    /**
     * Cập nhật hợp đồng
     */
    public function update(Request $request, $id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);

        // 🔥 CHẶN CẬP NHẬT KHI HỢP ĐỒNG ĐÃ BỊ TỪ CHỐI HOẶC HỦY
        if ($hopDong->trang_thai_ky === 'tu_choi_ky') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể cập nhật hợp đồng đã bị nhân viên từ chối ký.');
        }

        if ($hopDong->trang_thai_hop_dong === 'huy_bo') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể cập nhật hợp đồng đã bị hủy bỏ.');
        }

        if ($hopDong->trang_thai_duyet === 'da_duyet') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể cập nhật hợp đồng đã được duyệt.');
        }

        if ($hopDong->trang_thai_ky === 'da_ky') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể cập nhật hợp đồng đã được ký.');
        }

        // 🔥 SỬA: Bỏ 'trang_thai_ky' khỏi validation hoặc cho phép nullable
        $validationRules = [
            'chuc_vu_id' => 'required|exists:chuc_vu,id',
            'loai_hop_dong' => 'required|string',
            'ngay_bat_dau' => 'required|date',
            'luong_co_ban' => 'required|numeric|min:0',
            'phu_cap' => 'nullable|numeric|min:0',
            'dia_diem_lam_viec' => 'required|string',
            'ghi_chu' => 'nullable|string',
            // 🔥 BỎ HOẶC CHO PHÉP NULL
            // 'trang_thai_ky' => 'required|in:cho_ky,da_ky',
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

        // Cập nhật phụ cấp ID
        if ($request->has('phu_cap_ids') && is_array($request->phu_cap_ids)) {
            $data['phu_cap_id'] = $request->phu_cap_ids[0] ?? null;
            $data['phu_cap'] = json_encode($request->phu_cap_ids);
        } else {
            $data['phu_cap_id'] = null;
            $data['phu_cap'] = null;
        }

        // 🔥 GIỮ NGUYÊN TRẠNG THÁI KÝ CŨ NẾU KHÔNG CÓ GIÁ TRỊ MỚI
        if (!isset($data['trang_thai_ky']) || empty($data['trang_thai_ky'])) {
            $data['trang_thai_ky'] = $hopDong->trang_thai_ky;
        }

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

        // Xóa phụ cấp cũ
        PhuCapNhanVien::where('nguoi_dung_id', $hopDong->nguoi_dung_id)
            ->where('ghi_chu', 'LIKE', '%từ hợp đồng ' . $hopDong->so_hop_dong . '%')
            ->delete();

        // Cập nhật hợp đồng
        $hopDong->update($data);

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

        return redirect()->route('admin.hop-dong.index')
            ->with('success', '✅ Cập nhật hợp đồng thành công');
    }

    /**
     * Xóa hợp đồng
     */
    public function destroy($id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);

        // 🔥 CHỈ CHO XÓA KHI Ở TRẠNG THÁI: tao_moi, het_han, huy_bo, tu_choi_ky
        $allowedStatus = ['tao_moi', 'het_han', 'huy_bo'];
        if (!in_array($hopDong->trang_thai_hop_dong, $allowedStatus) && $hopDong->trang_thai_ky !== 'tu_choi_ky') {
            return redirect()->back()->with('error', '❌ Không thể xóa hợp đồng ở trạng thái này.');
        }

        // Xóa file hợp đồng
        if ($hopDong->duong_dan_file) {
            foreach (explode(';', $hopDong->duong_dan_file) as $file) {
                if (trim($file)) Storage::disk('public')->delete(trim($file));
            }
        }

        // Xóa phụ cấp của nhân viên liên quan đến hợp đồng này
        PhuCapNhanVien::where('nguoi_dung_id', $hopDong->nguoi_dung_id)
            ->where('ghi_chu', 'LIKE', '%từ hợp đồng ' . $hopDong->so_hop_dong . '%')
            ->delete();

        // Xóa hợp đồng
        $hopDong->delete();

        return redirect()->route('admin.hop-dong.index')
            ->with('success', '✅ Xóa hợp đồng và phụ cấp liên quan thành công');
    }

    /**
     * Hủy hợp đồng
     */
    public function huy(Request $request, $id)
    {
        $request->validate([
            'ly_do_huy' => 'required|string|max:1000'
        ]);

        $hopDong = HopDongLaoDong::findOrFail($id);
        $user = Auth::user();
        $userRoles = optional($user->vaiTros)->pluck('name')->toArray();

        if (!in_array('admin', $userRoles) && !in_array('hr', $userRoles)) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy hợp đồng');
        }

        // 🔥 LƯU ĐẦY ĐỦ THÔNG TIN
        $hopDong->update([
            'trang_thai_hop_dong' => 'huy_bo',
            'ly_do_huy' => $request->ly_do_huy,
            'nguoi_huy_id' => Auth::id(),
            'thoi_gian_huy' => now(),
            'trang_thai_duyet' => 'tu_choi',
            'trang_thai_ky' => 'tu_choi_ky',
        ]);

        // Gửi thông báo cho nhân viên
        try {
            $nhanVien = NguoiDung::find($hopDong->nguoi_dung_id);
            if ($nhanVien) {
                $nhanVien->notify(new \App\Notifications\HopDongBiHuyNotification($hopDong));
            }
        } catch (\Exception $e) {
            Log::error('Gửi thông báo hủy hợp đồng thất bại: ' . $e->getMessage());
        }

        return redirect()->route('admin.hop-dong.show', $hopDong->id)
            ->with('success', '✅ Hủy hợp đồng thành công.');
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

        return view('admin.hop-dong-lao-dong.thong-ke', compact(
            'tongHopDong',
            'hopDongHieuLuc',
            'hopDongChuaHieuLuc',
            'hopDongHetHan',
            'hopDongHuyBo',
            'hopDongTaoMoi',
            'thongKeLoaiHopDong',
            'thongKeTrangThaiKy',
            'thongKeTheoPhongBan',
            'hopDongSapHetHan',
            'tuNgay',
            'denNgay'
        ));
    }

    /**
     * API lấy thông tin nhân viên
     */
    public function getNhanVienInfo($id)
    {
        $nhanVien = NguoiDung::with(['chucVu', 'phuCapNhanViens.phuCap'])
            ->find($id);

        if (!$nhanVien) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy nhân viên']);
        }

        $phuCapIds = $nhanVien->phuCapNhanViens
            ->where('trang_thai', 'hieu_luc')
            ->where('ngay_hieu_luc', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ngay_ket_thuc')
                    ->orWhere('ngay_ket_thuc', '>=', now());
            })
            ->pluck('phu_cap_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'luong_co_ban' => $nhanVien->chucVu->luong_co_ban ?? 0,
            'phu_cap_ids' => $phuCapIds,
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

    /**
     * Tái ký hợp đồng (gia hạn - chỉ dùng khi hợp đồng hết hạn)
     */
    public function taiKy($id)
    {
        $hopDongCu = HopDongLaoDong::findOrFail($id);

        // 🔥 KIỂM TRA: Chỉ cho phép tái ký khi hợp đồng hết hạn hoặc đang hiệu lực
        if (!in_array($hopDongCu->trang_thai_hop_dong, ['het_han', 'hieu_luc'])) {
            return back()->with('error', '❌ Hợp đồng không ở trạng thái có thể tái ký (gia hạn).');
        }

        // 🔥 KIỂM TRA: Không cho tái ký nếu đã tái ký trước đó
        if ($hopDongCu->trang_thai_tai_ky == 'da_tai_ky') {
            return back()->with('error', '❌ Hợp đồng này đã được tái ký.');
        }

        // 🔥 Tạo hợp đồng mới (gia hạn)
        $hopDongMoi = HopDongLaoDong::create([
            'nguoi_dung_id' => $hopDongCu->nguoi_dung_id,
            'chuc_vu_id' => $hopDongCu->chuc_vu_id,
            'so_hop_dong' => $this->generateSoHopDong(),
            'loai_hop_dong' => $hopDongCu->loai_hop_dong,
            'ngay_bat_dau' => now()->addDays(1)->format('Y-m-d'), // 🔥 Ngày bắt đầu mới (ngày mai)
            'ngay_ket_thuc' => $hopDongCu->loai_hop_dong == 'khong_xac_dinh_thoi_han'
                ? null
                : now()->addYear()->format('Y-m-d'), // 🔥 Ngày kết thúc mới (1 năm sau)
            'luong_co_ban' => $hopDongCu->luong_co_ban,
            'phu_cap_id' => $hopDongCu->phu_cap_id,
            'phu_cap' => $hopDongCu->phu_cap,
            'dia_diem_lam_viec' => $hopDongCu->dia_diem_lam_viec,
            'dieu_khoan' => $hopDongCu->dieu_khoan,
            'trang_thai_hop_dong' => HopDongLaoDong::TRANG_THAI_TAO_MOI,
            'trang_thai_ky' => HopDongLaoDong::TRANG_THAI_KY_CHO_KY,
            'trang_thai_duyet' => HopDongLaoDong::TRANG_THAI_DUYET_CHO_DUYET,
            'created_by' => auth()->id(),
            'ghi_chu' => '🔄 Tái ký (gia hạn) từ hợp đồng ' . $hopDongCu->so_hop_dong . ' (ngày ' . now()->format('d/m/Y') . ')',
        ]);

        // 🔥 Đánh dấu hợp đồng cũ đã được tái ký
        $hopDongCu->update([
            'trang_thai_tai_ky' => 'da_tai_ky',
            'ghi_chu' => ($hopDongCu->ghi_chu ? $hopDongCu->ghi_chu . ' | ' : '') .
                '🔄 Đã tái ký (gia hạn) sang hợp đồng ' . $hopDongMoi->so_hop_dong . ' (ngày ' . now()->format('d/m/Y') . ')',
        ]);

        // 🔥 Lưu lịch sử tái ký
        try {
            if (class_exists(\App\Models\LichSuTaiKy::class)) {
                \App\Models\LichSuTaiKy::create([
                    'hop_dong_cu_id' => $hopDongCu->id,
                    'hop_dong_moi_id' => $hopDongMoi->id,
                    'nguoi_thuc_hien_id' => auth()->id(),
                    'ly_do_tai_ky' => '🔄 Tái ký (gia hạn) do hợp đồng ' . $hopDongCu->so_hop_dong . ' đã ' . ($hopDongCu->trang_thai_hop_dong == 'het_han' ? 'hết hạn' : 'cần gia hạn'),
                    'loai' => 'tai_ky',
                ]);
            }
        } catch (\Exception $e) {
            // Bỏ qua nếu chưa có bảng lịch sử hoặc chưa có cột loai
        }

        // 🔥 Gửi thông báo cho Admin/Giám đốc
        $this->notifyAdminsAboutNewContract($hopDongMoi);

        return redirect()
            ->route('admin.hop-dong.edit', $hopDongMoi->id)
            ->with('success', '🔄 Đã tạo hợp đồng gia hạn thành công! Vui lòng kiểm tra và gửi lên duyệt.');
    }

    /**
     * Tạo lại hợp đồng (dùng khi nhân viên từ chối ký)
     */
    /**
     * Tạo lại hợp đồng (dùng khi nhân viên từ chối ký)
     */
    /**
     * Tạo lại hợp đồng (dùng khi nhân viên từ chối ký)
     */
    public function taoLai($id)
    {
        $hopDongCu = HopDongLaoDong::findOrFail($id);

        // 🔥 KIỂM TRA: Chỉ cho phép tạo lại khi nhân viên từ chối ký
        if ($hopDongCu->trang_thai_ky !== 'tu_choi_ky') {
            return back()->with('error', '❌ Chỉ có thể tạo lại hợp đồng khi nhân viên từ chối ký.');
        }

        // 🔥 NẾU HỢP ĐỒNG CHƯA CÓ NGƯỜI HỦY, LƯU THÔNG TIN HỦY
        if ($hopDongCu->nguoi_huy_id == null) {
            $hopDongCu->update([
                'nguoi_huy_id' => Auth::id(),      // Lưu người hủy (HR/Admin)
                'thoi_gian_huy' => now(),          // Lưu thời gian hủy
                'trang_thai_hop_dong' => 'huy_bo', // Cập nhật trạng thái hợp đồng
                'trang_thai_ky' => 'tu_choi_ky',   // Giữ nguyên trạng thái từ chối
            ]);
        }

        // Tạo hợp đồng mới (giữ nguyên thông tin)
        $hopDongMoi = HopDongLaoDong::create([
            'nguoi_dung_id' => $hopDongCu->nguoi_dung_id,
            'chuc_vu_id' => $hopDongCu->chuc_vu_id,
            'so_hop_dong' => $this->generateSoHopDong(),
            'loai_hop_dong' => $hopDongCu->loai_hop_dong,
            'ngay_bat_dau' => $hopDongCu->ngay_bat_dau,
            'ngay_ket_thuc' => $hopDongCu->ngay_ket_thuc,
            'luong_co_ban' => $hopDongCu->luong_co_ban,
            'phu_cap_id' => $hopDongCu->phu_cap_id,
            'phu_cap' => $hopDongCu->phu_cap,
            'dia_diem_lam_viec' => $hopDongCu->dia_diem_lam_viec,
            'dieu_khoan' => $hopDongCu->dieu_khoan,
            'trang_thai_hop_dong' => HopDongLaoDong::TRANG_THAI_TAO_MOI,
            'trang_thai_ky' => HopDongLaoDong::TRANG_THAI_KY_CHO_KY,
            'trang_thai_duyet' => HopDongLaoDong::TRANG_THAI_DUYET_CHO_DUYET,
            'created_by' => auth()->id(),
            'ghi_chu' => '📝 Tạo lại từ hợp đồng bị từ chối ' . $hopDongCu->so_hop_dong .
                ' (Lý do từ chối: ' . str_replace('Từ chối ký: ', '', $hopDongCu->ghi_chu ?? 'Không có lý do') . ')',
        ]);

        // Đánh dấu hợp đồng cũ đã được tạo lại
        $hopDongCu->update([
            'trang_thai_tai_ky' => 'da_tai_ky',
            'ghi_chu' => ($hopDongCu->ghi_chu ? $hopDongCu->ghi_chu . ' | ' : '') .
                '📝 Đã tạo lại hợp đồng mới ' . $hopDongMoi->so_hop_dong . ' (ngày ' . now()->format('d/m/Y') . ')',
        ]);

        // Lưu lịch sử tạo lại
        try {
            if (class_exists(\App\Models\LichSuTaiKy::class)) {
                \App\Models\LichSuTaiKy::create([
                    'hop_dong_cu_id' => $hopDongCu->id,
                    'hop_dong_moi_id' => $hopDongMoi->id,
                    'nguoi_thuc_hien_id' => auth()->id(),
                    'ly_do_tai_ky' => '📝 Tạo lại hợp đồng do nhân viên từ chối ký ' . $hopDongCu->so_hop_dong,
                    'loai' => 'tao_lai',
                ]);
            }
        } catch (\Exception $e) {
            // Bỏ qua nếu chưa có bảng
        }

        // Gửi thông báo cho Admin/Giám đốc
        $this->notifyAdminsAboutNewContract($hopDongMoi);

        return redirect()
            ->route('admin.hop-dong.edit', $hopDongMoi->id)
            ->with('success', '📝 Đã tạo lại hợp đồng mới! Vui lòng điều chỉnh thông tin và gửi lên duyệt.');
    }
}
