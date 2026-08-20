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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Config;

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

        // Lọc theo trạng thái duyệt
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

        // 🔥 SỬA: Lọc hợp đồng sắp hết hạn - hỗ trợ tham số so_ngay
        if ($request->sap_het_han) {
            $now = now();
            // 🔥 SỬA LỖI: Chuyển $soNgay sang integer (int)
            $soNgay = (int) $request->input('so_ngay', Config::get('contract.tai_ky_so_ngay_truoc', 3));
            $inDays = now()->addDays($soNgay);

            $query->where('trang_thai_hop_dong', 'hieu_luc')
                ->whereNotNull('ngay_ket_thuc')
                ->where('ngay_ket_thuc', '>=', $now)
                ->where('ngay_ket_thuc', '<=', $inDays)
                ->where(function ($q) {
                    $q->whereNull('trang_thai_tai_ky')
                        ->orWhere('trang_thai_tai_ky', '!=', 'da_tai_ky');
                });
        }

        // LOẠI BỎ CÁC HỢP ĐỒNG ĐÃ TÁI KÝ (da_tai_ky) KHỎI DANH SÁCH CHÍNH
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
                })
                ->where(function ($subQ) {
                    $subQ->whereNull('trang_thai_tai_ky')
                        ->orWhere('trang_thai_tai_ky', '!=', 'da_tai_ky');
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
        $soNgayTruoc = Config::get('contract.tai_ky_so_ngay_truoc', 3);
        $inDays = now()->addDays($soNgayTruoc);

        $hieuLuc = HopDongLaoDong::where('trang_thai_hop_dong', 'hieu_luc')
            ->where(function ($q) {
                $q->whereNull('trang_thai_tai_ky')->orWhere('trang_thai_tai_ky', '!=', 'da_tai_ky');
            })
            ->count();

        $chuaCoHopDong = HoSoNguoiDung::whereDoesntHave('hopDongLaoDong')->count();

        // Hợp đồng sắp hết hạn (có thể tái ký) - 3 ngày
        $sapHetHan = HopDongLaoDong::where('trang_thai_hop_dong', 'hieu_luc')
            ->whereNotNull('ngay_ket_thuc')
            ->where('ngay_ket_thuc', '>', $now)
            ->where('ngay_ket_thuc', '<=', $inDays)
            ->where(function ($q) {
                $q->whereNull('trang_thai_tai_ky')
                    ->orWhere('trang_thai_tai_ky', '!=', 'da_tai_ky');
            })
            ->count();

        $hetHanChuaTaiKy = HopDongLaoDong::where('trang_thai_tai_ky', 'cho_tai_ky')
            ->where('trang_thai_hop_dong', 'het_han')
            ->count();

        $choDuyet = HopDongLaoDong::where('trang_thai_duyet', 'cho_duyet')
            ->where(function ($q) {
                $q->whereNull('trang_thai_tai_ky')->orWhere('trang_thai_tai_ky', '!=', 'da_tai_ky');
            })
            ->count();

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

        // LẤY TẤT CẢ HỢP ĐỒNG KHÔNG CÒN HIỆU LỰC (bao gồm đã tái ký)
        $query->where(function ($q) {
            $q->where('trang_thai_hop_dong', 'huy_bo')
                ->orWhere('trang_thai_ky', 'tu_choi_ky')
                ->orWhere('trang_thai_duyet', 'tu_choi')
                ->orWhere('trang_thai_tai_ky', 'da_tai_ky')
                ->orWhere(function ($subQ) {
                    $subQ->where('trang_thai_hop_dong', 'het_han')
                        ->where('trang_thai_tai_ky', 'da_tai_ky');
                });
        });

        // Loại trừ hợp đồng đang hiệu lực và chưa hiệu lực
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
            'phu_cap_ids' => 'nullable|array',
            'phu_cap_ids.*' => 'exists:phu_cap,id',
        ]);

        $data = $request->all();

        // Trạng thái mới: Tạo mới + Chờ duyệt
        $data['trang_thai_hop_dong'] = HopDongLaoDong::TRANG_THAI_TAO_MOI;
        $data['trang_thai_ky'] = HopDongLaoDong::TRANG_THAI_KY_CHO_KY;
        $data['trang_thai_duyet'] = HopDongLaoDong::TRANG_THAI_DUYET_CHO_DUYET;
        $data['created_by'] = Auth::id();

        // Xử lý phụ cấp ID
        if ($request->has('phu_cap_ids') && is_array($request->phu_cap_ids)) {
            $data['phu_cap_id'] = $request->phu_cap_ids[0] ?? null;
            $data['phu_cap'] = json_encode($request->phu_cap_ids);
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

        // Tự động tạo file PDF hợp đồng
        try {
            $this->generatePdfContract($hopDong);
        } catch (\Exception $e) {
            Log::error('Tạo PDF hợp đồng thất bại: ' . $e->getMessage());
        }

        // Gửi thông báo cho Admin/Giám đốc
        $this->notifyAdminsAboutNewContract($hopDong);

        return redirect()->route('admin.hop-dong.index')
            ->with('success', '✅ Hợp đồng đã được tạo, file PDF đã được sinh tự động và gửi lên Giám đốc duyệt.');
    }

    /**
     * Tạo file PDF hợp đồng
     */
    private function generatePdfContract($hopDong)
    {
        // Load lại dữ liệu với các quan hệ cần thiết
        $hopDong->load([
            'hoSoNguoiDung',
            'nguoiDung.phongBan',
            'chucVu',
            'nguoiDung.phuCapNhanViens.phuCap'
        ]);

        // Chuẩn bị dữ liệu
        $hoSo = $hopDong->hoSoNguoiDung;
        $ngayBatDau = Carbon::parse($hopDong->ngay_bat_dau);
        $ngayKetThuc = $hopDong->ngay_ket_thuc ? Carbon::parse($hopDong->ngay_ket_thuc) : null;

        // Thông tin công ty
        $company = [
            'ten' => 'Công ty TNHH Công nghệ HR Flow Việt Nam',
            'dia_chi' => 'Tầng 8, Tòa CT1, Khu đô thị Nam Cường, Bắc Từ Liêm, Hà Nội',
            'dien_thoai' => '024 3765 8899',
            'ma_so_thue' => '0109876543',
            'tai_khoan' => '1903688888888',
            'nguoi_dai_dien' => 'Nguyễn Văn Minh',
            'chuc_vu_dai_dien' => 'Giám đốc',
        ];

        // Xác định loại hợp đồng
        $loaiHopDongText = [
            'thu_viec' => 'Thử việc',
            'xac_dinh_thoi_han' => 'Xác định thời hạn',
            'khong_xac_dinh_thoi_han' => 'Không xác định thời hạn',
            'mua_vu' => 'Mùa vụ',
        ][$hopDong->loai_hop_dong] ?? $hopDong->loai_hop_dong;

        // Tính thời hạn hợp đồng từ ngày bắt đầu và ngày kết thúc
        $thoiHan = '';
        if ($hopDong->loai_hop_dong == 'khong_xac_dinh_thoi_han') {
            $thoiHan = 'Không xác định thời hạn';
        } elseif ($hopDong->loai_hop_dong == 'xac_dinh_thoi_han' && $ngayKetThuc) {
            $months = $ngayBatDau->diffInMonths($ngayKetThuc);
            $days = $ngayBatDau->diffInDays($ngayKetThuc);
            if ($months > 0) {
                $thoiHan = $months . ' tháng ' . ($days % 30 > 0 ? 'và ' . ($days % 30) . ' ngày' : '');
            } else {
                $thoiHan = $days . ' ngày';
            }
            $thoiHan .= ' (từ ' . $ngayBatDau->format('d/m/Y') . ' đến ' . $ngayKetThuc->format('d/m/Y') . ')';
        } elseif ($hopDong->loai_hop_dong == 'xac_dinh_thoi_han' && !$ngayKetThuc) {
            $thoiHan = 'Xác định thời hạn (chưa có ngày kết thúc)';
        } else {
            $thoiHan = $ngayBatDau->format('d/m/Y') . ' - ' . ($ngayKetThuc ? $ngayKetThuc->format('d/m/Y') : '...');
        }

        // Lấy phụ cấp
        $phuCapText = 'Không có';
        $phuCapDisplay = 0;

        $phuCapNhanViens = $hopDong->nguoiDung->phuCapNhanViens ?? collect();
        if ($phuCapNhanViens->count() > 0) {
            $phuCapItems = [];
            foreach ($phuCapNhanViens as $pc) {
                $phuCapDisplay += $pc->so_tien;
                $phuCapItems[] = ($pc->phuCap->ten ?? 'Phụ cấp') . ': ' . number_format($pc->so_tien, 0, ',', '.') . ' VNĐ';
            }
            $phuCapText = implode('; ', $phuCapItems);
        }

        // Dữ liệu cho PDF
        $data = [
            'hopDong' => $hopDong,
            'hoSo' => $hoSo,
            'company' => $company,
            'ngayBatDau' => $ngayBatDau,
            'ngayKetThuc' => $ngayKetThuc,
            'loaiHopDongText' => $loaiHopDongText,
            'thoiHan' => $thoiHan,
            'luongCoBan' => number_format($hopDong->luong_co_ban, 0, ',', '.') . ' VNĐ',
            'phuCapText' => $phuCapText,
            'phuCapDisplay' => $phuCapDisplay,
            'ngayHienTai' => Carbon::now()->format('d/m/Y'),
            'diaChiLamViec' => $hopDong->dia_diem_lam_viec ?? $company['dia_chi'],
            'tenPhongBan' => $hopDong->nguoiDung && $hopDong->nguoiDung->phongBan ? $hopDong->nguoiDung->phongBan->ten_phong_ban : 'N/A',
            'tenChucVu' => $hopDong->chucVu ? $hopDong->chucVu->ten : 'N/A',
        ];

        // Tạo PDF với options hỗ trợ UTF-8
        $pdf = Pdf::loadView('admin.hop-dong-lao-dong.pdf_template', $data);
        $pdf->setPaper('a4', 'portrait');

        // Thêm options hỗ trợ tiếng Việt
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFontSize' => 12,
        ]);

        // Lưu file
        $fileName = 'hop_dong_' . $hopDong->so_hop_dong . '_' . date('Ymd_His') . '.pdf';
        $path = 'hop_dong/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        // Cập nhật đường dẫn file vào database
        $hopDong->duong_dan_file = $path;
        $hopDong->save();

        return $path;
    }

    /**
     * Gửi thông báo cho Admin/Giám đốc khi có hợp đồng mới
     */
    private function notifyAdminsAboutNewContract($hopDong)
    {
        try {
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
     * DUYỆT HỢP ĐỒNG (Cho Admin/Giám đốc)
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

        // Gửi thông báo cho HR
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
     * TỪ CHỐI DUYỆT HỢP ĐỒNG (Cho Admin/Giám đốc)
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

        $hopDong->update([
            'trang_thai_duyet' => HopDongLaoDong::TRANG_THAI_DUYET_TU_CHOI,
            'nguoi_duyet_id' => Auth::id(),
            'thoi_gian_duyet' => now(),
            'ly_do_tu_choi' => $request->ly_do_tu_choi,
            'trang_thai_hop_dong' => HopDongLaoDong::TRANG_THAI_HUY_BO,
            'nguoi_huy_id' => Auth::id(),
            'thoi_gian_huy' => now(),
            'trang_thai_ky' => 'tu_choi_ky',
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

        if ($hopDong->trang_thai_duyet !== HopDongLaoDong::TRANG_THAI_DUYET_DA_DUYET) {
            return redirect()->back()->with('error', '❌ Hợp đồng chưa được Giám đốc duyệt. Không thể gửi cho nhân viên.');
        }

        if ($hopDong->trang_thai_hop_dong === HopDongLaoDong::TRANG_THAI_HUY_BO) {
            return redirect()->back()->with('error', '❌ Hợp đồng đã bị hủy.');
        }

        if ($hopDong->trang_thai_ky === HopDongLaoDong::TRANG_THAI_KY_DA_KY) {
            return redirect()->back()->with('error', '❌ Hợp đồng đã được ký rồi.');
        }

        if ($hopDong->thoi_gian_gui) {
            return redirect()->back()->with('error', '❌ Hợp đồng đã được gửi cho nhân viên từ trước.');
        }

        $hopDong->update([
            'trang_thai_ky' => HopDongLaoDong::TRANG_THAI_KY_CHO_KY,
            'trang_thai_hop_dong' => HopDongLaoDong::TRANG_THAI_CHUA_HIEU_LUC,
            'thoi_gian_gui' => now(),
        ]);

        $nhanVien = NguoiDung::with('hoSo')->find($hopDong->nguoi_dung_id);

        if ($nhanVien) {
            try {
                $nhanVien->notify(new \App\Notifications\HopDongGuiKyNotification($hopDong));
            } catch (\Exception $e) {
                Log::error('Gửi thông báo hợp đồng cho nhân viên thất bại: ' . $e->getMessage());
            }
        }

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
            'nguoiDuyet.hoSo',
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

        if ($hopDong->trang_thai_ky === 'tu_choi_ky') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể sửa hợp đồng đã bị nhân viên từ chối ký. Vui lòng tạo lại hợp đồng mới.');
        }

        if ($hopDong->trang_thai_hop_dong === 'huy_bo') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể sửa hợp đồng đã bị hủy bỏ.');
        }

        if ($hopDong->trang_thai_duyet === 'da_duyet') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể sửa hợp đồng đã được duyệt.');
        }

        if ($hopDong->trang_thai_ky === 'da_ky') {
            return redirect()->route('admin.hop-dong.show', $hopDong->id)
                ->with('error', '❌ Không thể sửa hợp đồng đã được ký.');
        }

        if ($hopDong->trang_thai_ky === 'cho_ky' && $hopDong->trang_thai_duyet !== 'da_duyet') {
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

        $validationRules = [
            'chuc_vu_id' => 'required|exists:chuc_vu,id',
            'loai_hop_dong' => 'required|string',
            'ngay_bat_dau' => 'required|date',
            'luong_co_ban' => 'required|numeric|min:0',
            'phu_cap' => 'nullable|numeric|min:0',
            'dia_diem_lam_viec' => 'required|string',
            'ghi_chu' => 'nullable|string',
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

        if ($request->has('phu_cap_ids') && is_array($request->phu_cap_ids)) {
            $data['phu_cap_id'] = $request->phu_cap_ids[0] ?? null;
            $data['phu_cap'] = json_encode($request->phu_cap_ids);
        } else {
            $data['phu_cap_id'] = null;
            $data['phu_cap'] = null;
        }

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

        if ($request->hasFile('file_dinh_kem')) {
            if ($hopDong->file_dinh_kem) Storage::disk('public')->delete($hopDong->file_dinh_kem);
            $data['file_dinh_kem'] = $request->file('file_dinh_kem')->store('file_dinh_kem', 'public');
        }

        // Xóa phụ cấp cũ
        PhuCapNhanVien::where('nguoi_dung_id', $hopDong->nguoi_dung_id)
            ->where('ghi_chu', 'LIKE', '%từ hợp đồng ' . $hopDong->so_hop_dong . '%')
            ->delete();

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

        // Tự động tạo lại PDF khi cập nhật
        try {
            $this->generatePdfContract($hopDong);
        } catch (\Exception $e) {
            Log::error('Tạo lại PDF hợp đồng thất bại: ' . $e->getMessage());
        }

        return redirect()->route('admin.hop-dong.index')
            ->with('success', '✅ Cập nhật hợp đồng thành công và tạo lại file PDF.');
    }

    /**
     * Xóa hợp đồng
     */
    public function destroy($id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);

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

        PhuCapNhanVien::where('nguoi_dung_id', $hopDong->nguoi_dung_id)
            ->where('ghi_chu', 'LIKE', '%từ hợp đồng ' . $hopDong->so_hop_dong . '%')
            ->delete();

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

        $hopDong->update([
            'trang_thai_hop_dong' => 'huy_bo',
            'ly_do_huy' => $request->ly_do_huy,
            'nguoi_huy_id' => Auth::id(),
            'thoi_gian_huy' => now(),
            'trang_thai_duyet' => 'tu_choi',
            'trang_thai_ky' => 'tu_choi_ky',
        ]);

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

        // 🔥 SỬA LỖI: Thêm tên bảng vào whereBetween
        if ($tuNgay && $denNgay) {
            $query->whereBetween('hop_dong_lao_dong.created_at', [$tuNgay . ' 00:00:00', $denNgay . ' 23:59:59']);
        }

        $tongHopDong = (clone $query)->count();
        $hopDongHieuLuc = (clone $query)->where('trang_thai_hop_dong', 'hieu_luc')->count();
        $hopDongChuaHieuLuc = (clone $query)->where('trang_thai_hop_dong', 'chua_hieu_luc')->count();
        $hopDongHetHan = (clone $query)->where('trang_thai_hop_dong', 'het_han')->count();
        $hopDongHuyBo = (clone $query)->where('trang_thai_hop_dong', 'huy_bo')->count();
        $hopDongTaoMoi = (clone $query)->where('trang_thai_hop_dong', 'tao_moi')->count();

        $thongKeLoaiHopDong = (clone $query)->selectRaw('loai_hop_dong, COUNT(*) as so_luong')->groupBy('loai_hop_dong')->get()->keyBy('loai_hop_dong');
        $thongKeTrangThaiKy = (clone $query)->selectRaw('trang_thai_ky, COUNT(*) as so_luong')->groupBy('trang_thai_ky')->get()->keyBy('trang_thai_ky');

        // 🔥 SỬA LỖI: Thêm tên bảng vào groupBy cho rõ ràng
        $thongKeTheoPhongBan = (clone $query)->join('nguoi_dung', 'hop_dong_lao_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->join('phong_ban', 'nguoi_dung.phong_ban_id', '=', 'phong_ban.id')
            ->selectRaw('phong_ban.ten_phong_ban, COUNT(*) as so_luong')
            ->groupBy('phong_ban.id', 'phong_ban.ten_phong_ban')
            ->orderBy('so_luong', 'desc')
            ->get();

        // 🔥 SỬA: Hợp đồng sắp hết hạn trong 30 ngày tới (KHÔNG bị giới hạn bởi bộ lọc ngày)
        $soNgayCanhBao = 30;
        $hopDongSapHetHan30Ngay = HopDongLaoDong::where('trang_thai_hop_dong', 'hieu_luc')
            ->whereNotNull('ngay_ket_thuc')
            ->where('ngay_ket_thuc', '>', now())
            ->where('ngay_ket_thuc', '<=', now()->addDays($soNgayCanhBao))
            ->where(function ($q) {
                $q->whereNull('trang_thai_tai_ky')
                    ->orWhere('trang_thai_tai_ky', '!=', 'da_tai_ky');
            })
            ->with(['hoSoNguoiDung', 'chucVu'])
            ->orderBy('ngay_ket_thuc', 'asc')
            ->get();

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
            'hopDongSapHetHan30Ngay',
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
     * Tái ký hợp đồng (gia hạn)
     * Cho phép tái ký khi hợp đồng đang hiệu lực và còn <= 3 ngày hết hạn
     * HOẶC khi hợp đồng đã hết hạn
     */
    public function taiKy($id)
    {
        $hopDongCu = HopDongLaoDong::findOrFail($id);

        // Kiểm tra trạng thái hợp đồng
        $isHetHan = $hopDongCu->trang_thai_hop_dong === 'het_han';
        $isHieuLuc = $hopDongCu->trang_thai_hop_dong === 'hieu_luc';

        // Lấy số ngày cấu hình
        $soNgayTruoc = Config::get('contract.tai_ky_so_ngay_truoc', 3);

        // Kiểm tra xem có được phép tái ký không
        $canTaiKy = false;
        $lyDo = '';

        if ($isHetHan) {
            $canTaiKy = true;
            $lyDo = 'Hợp đồng đã hết hạn';
        } elseif ($isHieuLuc && $hopDongCu->ngay_ket_thuc) {
            $ngayConLai = Carbon::now()->diffInDays($hopDongCu->ngay_ket_thuc, false);
            // Cho phép tái ký khi còn <= số ngày cấu hình
            if ($ngayConLai <= $soNgayTruoc && $ngayConLai >= 0) {
                $canTaiKy = true;
                $lyDo = 'Hợp đồng sắp hết hạn (còn ' . $ngayConLai . ' ngày)';
            } elseif ($ngayConLai < 0) {
                // Trường hợp này là đã hết hạn nhưng trạng thái chưa được cập nhật
                $canTaiKy = true;
                $lyDo = 'Hợp đồng đã quá hạn (cập nhật tự động)';
            } else {
                return back()->with('error', '❌ Hợp đồng còn ' . $ngayConLai . ' ngày mới hết hạn. Chỉ được tái ký khi còn <= ' . $soNgayTruoc . ' ngày hoặc đã hết hạn.');
            }
        } else {
            return back()->with('error', '❌ Hợp đồng không ở trạng thái có thể tái ký (gia hạn).');
        }

        // Kiểm tra đã tái ký chưa
        if ($hopDongCu->trang_thai_tai_ky == 'da_tai_ky') {
            return back()->with('error', '❌ Hợp đồng này đã được tái ký.');
        }

        // TẠO HỢP ĐỒNG MỚI
        $hopDongMoi = HopDongLaoDong::create([
            'nguoi_dung_id' => $hopDongCu->nguoi_dung_id,
            'chuc_vu_id' => $hopDongCu->chuc_vu_id,
            'so_hop_dong' => $this->generateSoHopDong(),
            'loai_hop_dong' => $hopDongCu->loai_hop_dong,
            'ngay_bat_dau' => now()->addDays(1)->format('Y-m-d'),
            'ngay_ket_thuc' => $hopDongCu->loai_hop_dong == 'khong_xac_dinh_thoi_han'
                ? null
                : now()->addYear()->format('Y-m-d'),
            'luong_co_ban' => $hopDongCu->luong_co_ban,
            'phu_cap_id' => $hopDongCu->phu_cap_id,
            'phu_cap' => $hopDongCu->phu_cap,
            'dia_diem_lam_viec' => $hopDongCu->dia_diem_lam_viec,
            'dieu_khoan' => $hopDongCu->dieu_khoan,
            'trang_thai_hop_dong' => HopDongLaoDong::TRANG_THAI_TAO_MOI,
            'trang_thai_ky' => HopDongLaoDong::TRANG_THAI_KY_CHO_KY,
            'trang_thai_duyet' => HopDongLaoDong::TRANG_THAI_DUYET_CHO_DUYET,
            'created_by' => auth()->id(),
            'ghi_chu' => '🔄 Tái ký (gia hạn) từ hợp đồng ' . $hopDongCu->so_hop_dong .
                ' (Lý do: ' . $lyDo . ' - Ngày ' . now()->format('d/m/Y') . ')',
        ]);

        // CẬP NHẬT HỢP ĐỒNG CŨ: ĐÁNH DẤU ĐÃ TÁI KÝ VÀ CHUYỂN SANG TRẠNG THÁI "HẾT HẠN"
        $hopDongCu->update([
            'trang_thai_tai_ky' => 'da_tai_ky',
            'trang_thai_hop_dong' => 'het_han',  // CHUYỂN SANG HẾT HẠN
            'ngay_ket_thuc' => now()->subDay(),   // ĐẶT NGÀY KẾT THÚC LÀ HÔM QUA
            'ghi_chu' => ($hopDongCu->ghi_chu ? $hopDongCu->ghi_chu . ' | ' : '') .
                '🔄 Đã tái ký (gia hạn) sang hợp đồng ' . $hopDongMoi->so_hop_dong .
                ' (Lý do: ' . $lyDo . ' - Ngày ' . now()->format('d/m/Y') . ')',
        ]);

        // Tạo PDF cho hợp đồng mới
        try {
            $this->generatePdfContract($hopDongMoi);
        } catch (\Exception $e) {
            Log::error('Tạo PDF hợp đồng gia hạn thất bại: ' . $e->getMessage());
        }

        // Lưu lịch sử tái ký
        try {
            if (class_exists(\App\Models\LichSuTaiKy::class)) {
                \App\Models\LichSuTaiKy::create([
                    'hop_dong_cu_id' => $hopDongCu->id,
                    'hop_dong_moi_id' => $hopDongMoi->id,
                    'nguoi_thuc_hien_id' => auth()->id(),
                    'ly_do_tai_ky' => '🔄 Tái ký (gia hạn) - ' . $lyDo,
                    'loai' => 'tai_ky',
                ]);
            }
        } catch (\Exception $e) {
            // Bỏ qua nếu chưa có bảng
        }

        $this->notifyAdminsAboutNewContract($hopDongMoi);

        return redirect()
            ->route('admin.hop-dong.edit', $hopDongMoi->id)
            ->with('success', '🔄 Đã tạo hợp đồng gia hạn thành công! Hợp đồng cũ đã được chuyển sang trạng thái "Hết hạn" và không hiển thị trong danh sách chính.');
    }

    /**
     * Tạo lại hợp đồng (khi nhân viên từ chối ký)
     */
    public function taoLai($id)
    {
        $hopDongCu = HopDongLaoDong::findOrFail($id);

        if ($hopDongCu->trang_thai_ky !== 'tu_choi_ky') {
            return back()->with('error', '❌ Chỉ có thể tạo lại hợp đồng khi nhân viên từ chối ký.');
        }

        if ($hopDongCu->nguoi_huy_id == null) {
            $hopDongCu->update([
                'nguoi_huy_id' => Auth::id(),
                'thoi_gian_huy' => now(),
                'trang_thai_hop_dong' => 'huy_bo',
                'trang_thai_ky' => 'tu_choi_ky',
            ]);
        }

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

        $hopDongCu->update([
            'trang_thai_tai_ky' => 'da_tai_ky',
            'ghi_chu' => ($hopDongCu->ghi_chu ? $hopDongCu->ghi_chu . ' | ' : '') .
                '📝 Đã tạo lại hợp đồng mới ' . $hopDongMoi->so_hop_dong . ' (ngày ' . now()->format('d/m/Y') . ')',
        ]);

        // Tạo PDF cho hợp đồng mới
        try {
            $this->generatePdfContract($hopDongMoi);
        } catch (\Exception $e) {
            Log::error('Tạo PDF hợp đồng tạo lại thất bại: ' . $e->getMessage());
        }

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
            // Bỏ qua
        }

        $this->notifyAdminsAboutNewContract($hopDongMoi);

        return redirect()
            ->route('admin.hop-dong.edit', $hopDongMoi->id)
            ->with('success', '📝 Đã tạo lại hợp đồng mới! Vui lòng điều chỉnh thông tin và gửi lên duyệt.');
    }
}
