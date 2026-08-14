<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ChiTietBangLuongExport;
use App\Http\Controllers\Controller;
use App\Mail\PhieuLuongMail;
use App\Models\BangLuong;
use App\Models\KhauTruLuong;
use App\Models\LichSuLuong;
use App\Models\LuongNhanVien;
use App\Models\NguoiDung;
use App\Services\PdfService;
use App\Services\TinhLuongService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class BangLuongController extends Controller
{
    public function __construct(
        private TinhLuongService $tinhLuong,
        private PdfService $pdf
    ) {}

    /** Danh sách bảng lương */
    public function index()
    {
        $bangLuongs = BangLuong::withCount('luongNhanViens')
            ->withSum('luongNhanViens', 'tong_luong')
            ->withSum('luongNhanViens', 'luong_thuc_nhan')
            ->with('nguoiXuLy')
            ->orderByDesc('nam')
            ->orderByDesc('thang')
            ->paginate(10);

        return view('admin.bang-luong.index', compact('bangLuongs'));
    }

    /** Form chọn nhân viên để tính lương */
    public function create(Request $request)
    {
        // Mặc định tính cho tháng trước; cho phép chọn tháng/năm khác
        $macDinh   = Carbon::now()->subMonthNoOverflow();
        $thangTinh = (int) $request->input('thang', $macDinh->month);
        $namTinh   = (int) $request->input('nam', $macDinh->year);

        $exists = BangLuong::where('thang', $thangTinh)->where('nam', $namTinh)->exists();

        // Nhân viên chưa được tính trong tháng này
        $daTinh = LuongNhanVien::where('luong_thang', $thangTinh)
            ->where('luong_nam', $namTinh)
            ->pluck('nguoi_dung_id')
            ->all();

        $nhanViens = NguoiDung::with('ho_so', 'chuc_vu', 'hop_dongs')
            ->where('trang_thai', 1)
            ->whereNotIn('id', $daTinh)
            ->get();

        return view('admin.bang-luong.create', compact('thangTinh', 'namTinh', 'exists', 'nhanViens'));
    }

    /** Thực hiện tính lương cho danh sách nhân viên đã chọn */
    public function tinhLuong(Request $request)
    {
        $data = $request->validate([
            'thang'           => 'required|integer|between:1,12',
            'nam'             => 'required|integer|min:2000|max:2100',
            'nhan_vien_ids'   => 'required|array|min:1',
            'nhan_vien_ids.*' => 'integer|exists:nguoi_dung,id',
        ]);

        $bangLuong = $this->tinhLuong->taoBangLuong(
            $data['thang'],
            $data['nam'],
            $data['nhan_vien_ids'],
            auth()->id(),
            'dang_xu_ly'
        );

        return redirect()
            ->route('admin.bang-luong.show', $bangLuong->id)
            ->with('success', 'Đã tính lương cho ' . count($data['nhan_vien_ids']) . ' nhân viên.');
    }

    /** Chi tiết bảng lương (danh sách nhân viên) */
    public function show($id)
    {
        $bangLuong = BangLuong::with([
            'luongNhanViens.nguoiDung.ho_so',
            'luongNhanViens.nguoiDung.chuc_vu',
            'nguoiXuLy',
            'nguoiPheDuyet',
        ])->findOrFail($id);

        return view('admin.bang-luong.show', compact('bangLuong'));
    }

    /** Chi tiết phiếu lương 1 nhân viên - hiển thị rõ công thức tính */
    public function chiTietNhanVien($id, $luongId)
    {
        $bangLuong = BangLuong::findOrFail($id);

        $luong = LuongNhanVien::with([
            'nguoiDung.ho_so',
            'nguoiDung.chuc_vu',
            'nguoiDung.phong_ban',
            'phuCapLuongs.phuCap',
            'thuongLuongs',
            'khauTruLuongs',
            'khauTrus',
        ])->where('bang_luong_id', $id)->findOrFail($luongId);

        return view('admin.bang-luong.chi-tiet-nhan-vien', compact('bangLuong', 'luong'));
    }

    /** Form sửa lương cho 1 nhân viên */
    public function editNhanVien($id, $luongId)
    {
        $bangLuong = BangLuong::findOrFail($id);

        if (!$bangLuong->la_nhap) {
            return back()->with('error', 'Bảng lương đã chốt, không thể sửa thông tin lương.');
        }

        $luong = LuongNhanVien::with([
            'nguoiDung.ho_so',
            'nguoiDung.chuc_vu',
            'phuCapLuongs.phuCap',
            'thuongLuongs',
        ])->where('bang_luong_id', $id)->findOrFail($luongId);

        return view('admin.bang-luong.edit-nhan-vien', compact('bangLuong', 'luong'));
    }

    /** Cập nhật lại lương cho 1 nhân viên */
    public function updateNhanVien(Request $request, $id, $luongId)
    {
        $bangLuong = BangLuong::findOrFail($id);

        if (!$bangLuong->la_nhap) {
            return back()->with('error', 'Bảng lương đã chốt, không thể sửa thông tin lương.');
        }

        $data = $request->validate([
            'so_ngay_cong' => 'nullable|numeric|min:0|max:31',
            'gio_tang_ca' => 'nullable|numeric|min:0',
            'tong_phu_cap' => 'nullable|numeric|min:0',
            'tong_thuong' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable|string|max:500',
            'khau_tru_khac' => 'nullable|array',
            'khau_tru_khac.tam_ung' => 'nullable|numeric|min:0',
            'khau_tru_khac.phat' => 'nullable|numeric|min:0',
            'khau_tru_khac.boi_thuong' => 'nullable|numeric|min:0',
            'khau_tru_khac.khac' => 'nullable|numeric|min:0',
        ]);

        $luong = LuongNhanVien::where('bang_luong_id', $id)->findOrFail($luongId);

        $khoanKhauTruKhac = [
            'tam_ung' => (float) ($data['khau_tru_khac']['tam_ung'] ?? 0),
            'phat' => (float) ($data['khau_tru_khac']['phat'] ?? 0),
            'boi_thuong' => (float) ($data['khau_tru_khac']['boi_thuong'] ?? 0),
            'khac' => (float) ($data['khau_tru_khac']['khac'] ?? 0),
        ];

        $tongKhauTruKhac = array_sum($khoanKhauTruKhac);
        $ghiChu = trim((string) ($request->input('ghi_chu', '')));

        $luongCu = [
            'tong_luong' => (float) $luong->tong_luong,
            'tong_khau_tru' => (float) $luong->tong_khau_tru,
            'luong_thuc_nhan' => (float) $luong->luong_thuc_nhan,
            'tong_phu_cap' => (float) $luong->tong_phu_cap,
            'tong_khau_tru_khac' => (float) $luong->tong_khau_tru_khac,
        ];

        DB::transaction(function () use ($luong, $data, $tongKhauTruKhac, $ghiChu, $khoanKhauTruKhac, $luongCu) {
            $ketQua = $this->tinhLuong->tinhLaiLuong($luong, [
                'so_ngay_cong' => $data['so_ngay_cong'] ?? $luong->so_ngay_cong,
                'gio_tang_ca' => $data['gio_tang_ca'] ?? $luong->gio_tang_ca,
                'tong_phu_cap' => $data['tong_phu_cap'] ?? $luong->tong_phu_cap,
                'tong_thuong' => $data['tong_thuong'] ?? $luong->tong_thuong,
                'khau_tru_khac' => $tongKhauTruKhac,
            ]);

            $luong->update(array_merge($ketQua, [
                'ghi_chu' => $ghiChu,
            ]));

            KhauTruLuong::where('luong_nhan_vien_id', $luong->id)
                ->whereIn('loai_khau_tru', ['bhxh', 'bhyt', 'bhtn', 'thue_tncn', 'khau_tru_khac'])
                ->delete();

            if (($ketQua['bhxh'] ?? 0) > 0) {
                KhauTruLuong::create([
                    'luong_nhan_vien_id' => $luong->id,
                    'loai_khau_tru' => 'bhxh',
                    'so_tien' => $ketQua['bhxh'],
                    'ghi_chu' => 'BHXH',
                ]);
            }

            if (($ketQua['bhyt'] ?? 0) > 0) {
                KhauTruLuong::create([
                    'luong_nhan_vien_id' => $luong->id,
                    'loai_khau_tru' => 'bhyt',
                    'so_tien' => $ketQua['bhyt'],
                    'ghi_chu' => 'BHYT',
                ]);
            }

            if (($ketQua['bhtn'] ?? 0) > 0) {
                KhauTruLuong::create([
                    'luong_nhan_vien_id' => $luong->id,
                    'loai_khau_tru' => 'bhtn',
                    'so_tien' => $ketQua['bhtn'],
                    'ghi_chu' => 'BHTN',
                ]);
            }

            if (($ketQua['thue_thu_nhap_ca_nhan'] ?? 0) > 0) {
                KhauTruLuong::create([
                    'luong_nhan_vien_id' => $luong->id,
                    'loai_khau_tru' => 'thue_tncn',
                    'so_tien' => $ketQua['thue_thu_nhap_ca_nhan'],
                    'ghi_chu' => 'Thuế TNCN',
                ]);
            }

            foreach ($khoanKhauTruKhac as $khoa => $soTien) {
                if ($soTien <= 0) {
                    continue;
                }

                $tenLoai = match ($khoa) {
                    'tam_ung' => 'Tạm ứng',
                    'phat' => 'Phạt',
                    'boi_thuong' => 'Bồi thường',
                    default => 'Khấu trừ khác',
                };

                KhauTruLuong::create([
                    'luong_nhan_vien_id' => $luong->id,
                    'loai_khau_tru' => 'khau_tru_khac',
                    'so_tien' => $soTien,
                    'ghi_chu' => $tenLoai,
                ]);
            }
        });

        return redirect()->route('admin.bang-luong.chi-tiet-nhan-vien', [$id, $luongId])
            ->with('success', 'Đã cập nhật lại lương nhân viên.');
    }

    /** Chốt lương (khoá bảng lương) */
    public function chot($id)
    {
        $bangLuong = BangLuong::findOrFail($id);

        if (!$bangLuong->la_nhap) {
            return back()->with('error', 'Bảng lương đã được chốt trước đó.');
        }

        $bangLuong->update([
            'trang_thai'          => 'da_chot',
            'nguoi_phe_duyet_id'  => auth()->id(),
            'thoi_gian_phe_duyet' => now(),
        ]);

        return back()->with('success', 'Đã chốt bảng lương ' . $bangLuong->ma_bang_luong . '.');
    }

    /** Đánh dấu đã thanh toán */
    public function thanhToan($id)
    {
        $bangLuong = BangLuong::findOrFail($id);

        if ($bangLuong->trang_thai !== 'da_chot') {
            return back()->with('error', 'Chỉ bảng lương đã chốt mới được thanh toán.');
        }

        $bangLuong->update(['trang_thai' => 'da_tra']);

        return back()->with('success', 'Đã đánh dấu thanh toán bảng lương.');
    }

    /** Xoá bảng lương (chỉ khi còn nháp) */
    public function destroy($id)
    {
        $bangLuong = BangLuong::findOrFail($id);

        if ($bangLuong->da_chot) {
            return back()->with('error', 'Không thể xoá bảng lương đã chốt.');
        }

        // Xoá chi tiết khấu trừ (không có FK cascade) trước
        $lnvIds = $bangLuong->luongNhanViens()->pluck('id');
        \App\Models\KhauTruLuong::whereIn('luong_nhan_vien_id', $lnvIds)->delete();

        // bang_luong xoá -> luong_nhan_vien (cascade) -> phu_cap_luong (cascade)
        $bangLuong->delete();

        return redirect()
            ->route('admin.bang-luong.index')
            ->with('success', 'Đã xoá bảng lương.');
    }
    public function guiEmailLuong($luongId)
{
    $luong = LuongNhanVien::with([
        'nguoiDung',
        'bangLuong'
    ])->findOrFail($luongId);

    if (!$luong->nguoiDung || !$luong->nguoiDung->email) {
        return back()->with('error', 'Nhân viên chưa có email.');
    }

    Mail::to($luong->nguoiDung->email)
        ->send(new PhieuLuongMail($luong));

    return back()->with(
        'success',
        'Đã gửi phiếu lương cho ' . $luong->nguoiDung->ho_ten
    );
}
public function guiTatCaEmail($id)
{
    $bangLuong = BangLuong::with([
        'luongNhanViens.nguoiDung'
    ])->findOrFail($id);

    $soLuong = 0;

    foreach ($bangLuong->luongNhanViens as $luong) {

        if (!$luong->nguoiDung?->email) {
            continue;
        }

        Mail::to($luong->nguoiDung->email)
            ->send(new PhieuLuongMail($luong));

        $soLuong++;
    }

    return back()->with(
        'success',
        "Đã gửi {$soLuong} phiếu lương thành công."
    );
}
public function export($id)
{
    $bangLuong = BangLuong::findOrFail($id);

    $fileName = 'bang_luong_' .
        $bangLuong->thang .
        '_' .
        $bangLuong->nam .
        '.xlsx';

    return Excel::download(
        new ChiTietBangLuongExport($id),
        $fileName
    );
}

    /** Xuất báo cáo bảng lương tháng ra PDF */
    public function exportPdf($id)
    {
        $bangLuong = BangLuong::with([
            'luongNhanViens.nguoiDung.ho_so',
            'luongNhanViens.nguoiDung.chuc_vu',
        ])->findOrFail($id);

        $fileName = 'bang_luong_' . $bangLuong->thang . '_' . $bangLuong->nam . '.pdf';

        return $this->pdf->download(
            'admin.bang-luong.pdf.bang-luong',
            ['bangLuong' => $bangLuong, 'ngayXuat' => now()->format('d/m/Y H:i')],
            $fileName,
            'landscape'
        );
    }

    /** Xuất phiếu lương 1 nhân viên ra PDF */
    public function phieuLuongPdf($id, $luongId)
    {
        $luong = LuongNhanVien::with([
            'nguoiDung.ho_so',
            'nguoiDung.chuc_vu',
            'phuCapLuongs.phuCap',
            'thuongLuongs',
            'khauTruLuongs',
            'khauTrus',
        ])->where('bang_luong_id', $id)->findOrFail($luongId);

        $hoTen = trim(($luong->nguoiDung->ho_so->ho ?? '') . ' ' . ($luong->nguoiDung->ho_so->ten ?? ''))
            ?: ($luong->nguoiDung->ten_dang_nhap ?? 'NV');

        $fileName = 'phieu_luong_' . $luong->nguoi_dung_id . '_' . $luong->luong_thang . '_' . $luong->luong_nam . '.pdf';

        return $this->pdf->download(
            'admin.bang-luong.pdf.phieu-luong',
            ['luong' => $luong, 'hoTen' => $hoTen, 'ngayXuat' => now()->format('d/m/Y H:i')],
            $fileName,
            'portrait'
        );
    }
}
