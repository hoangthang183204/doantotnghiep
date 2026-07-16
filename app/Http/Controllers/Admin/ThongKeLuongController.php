<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LuongNhanVien;
use App\Models\KhauTruLuong;
use App\Models\PhongBan;
use App\Services\PdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Thống kê quỹ lương theo phòng ban.
 */
class ThongKeLuongController extends Controller
{
    public function __construct(private PdfService $pdf) {}

    /** Trang thống kê */
    public function index(Request $request)
    {
        [$thang, $nam, $data] = $this->tongHop($request);

        return view('admin.thong-ke-luong.index', array_merge($data, [
            'thang' => $thang,
            'nam'   => $nam,
        ]));
    }

    /** Xuất báo cáo thống kê ra PDF */
    public function exportPdf(Request $request)
    {
        [$thang, $nam, $data] = $this->tongHop($request);

        return $this->pdf->download(
            'admin.thong-ke-luong.pdf',
            array_merge($data, [
                'thang'    => $thang,
                'nam'      => $nam,
                'ngayXuat' => now()->format('d/m/Y H:i'),
            ]),
            "thong_ke_quy_luong_{$thang}_{$nam}.pdf",
            'landscape'
        );
    }

    /**
     * Tổng hợp quỹ lương theo phòng ban cho 1 tháng/năm.
     * @return array{0:int,1:int,2:array}
     */
    private function tongHop(Request $request): array
    {
        $macDinh = Carbon::now()->subMonthNoOverflow();
        $thang   = (int) $request->input('thang', $macDinh->month);
        $nam     = (int) $request->input('nam', $macDinh->year);

        // Gom nhóm lương theo phòng ban qua join nguoi_dung
        $rows = LuongNhanVien::query()
            ->join('nguoi_dung', 'nguoi_dung.id', '=', 'luong_nhan_vien.nguoi_dung_id')
            ->leftJoin('phong_ban', 'phong_ban.id', '=', 'nguoi_dung.phong_ban_id')
            ->where('luong_nhan_vien.luong_thang', $thang)
            ->where('luong_nhan_vien.luong_nam', $nam)
            ->groupBy('phong_ban.id', 'phong_ban.ten_phong_ban')
            ->select([
                'phong_ban.id as phong_ban_id',
                DB::raw("COALESCE(phong_ban.ten_phong_ban, 'Chưa phân phòng') as ten_phong_ban"),
                DB::raw('COUNT(luong_nhan_vien.id) as so_nhan_vien'),
                DB::raw('SUM(luong_nhan_vien.tong_luong) as tong_luong'),
                DB::raw('SUM(luong_nhan_vien.tong_phu_cap) as tong_phu_cap'),
                DB::raw('SUM(luong_nhan_vien.tien_tang_ca) as tong_tang_ca'),
                DB::raw('SUM(luong_nhan_vien.tong_khau_tru) as tong_khau_tru'),
                DB::raw('SUM(luong_nhan_vien.luong_thuc_nhan) as tong_thuc_nhan'),
            ])
            ->orderByDesc('tong_thuc_nhan')
            ->get();

        $tongQuyLuong  = (float) $rows->sum('tong_luong');
        $tongThucNhan  = (float) $rows->sum('tong_thuc_nhan');
        $tongKhauTru   = (float) $rows->sum('tong_khau_tru');
        $tongNhanVien  = (int) $rows->sum('so_nhan_vien');
        $soPhongBan    = $rows->count();
        $luongTbNv     = $tongNhanVien > 0 ? $tongThucNhan / $tongNhanVien : 0;

        return [$thang, $nam, [
            'rows'          => $rows,
            'tongQuyLuong'  => $tongQuyLuong,
            'tongThucNhan'  => $tongThucNhan,
            'tongKhauTru'   => $tongKhauTru,
            'tongNhanVien'  => $tongNhanVien,
            'soPhongBan'    => $soPhongBan,
            'luongTbNv'     => $luongTbNv,
        ]];
    }

    // =====================================================================
    // TỔNG LƯƠNG THEO NĂM
    // =====================================================================

    /**
     * Trang tổng hợp lương theo năm: mỗi năm một dòng
     * (tổng lương, thuế TNCN, bảo hiểm, thực nhận). Bấm vào 1 năm -> chi tiết 12 tháng.
     */
    public function theoNam(Request $request)
    {
        // Bộ lọc theo năm (null = xem tất cả các năm)
        $namChon = $request->filled('nam') ? (int) $request->input('nam') : null;

        // Danh sách năm có dữ liệu lương (đổ vào dropdown lọc)
        $namList = LuongNhanVien::query()
            ->distinct()
            ->orderByDesc('luong_nam')
            ->pluck('luong_nam');

        // Gom nhóm toàn bộ dòng lương theo NĂM (lọc theo năm nếu có chọn)
        $rows = LuongNhanVien::query()
            ->when($namChon, fn($q) => $q->where('luong_nam', $namChon))
            ->selectRaw('luong_nam as nam')
            ->selectRaw('COUNT(*) as so_ky_luong')
            ->selectRaw('COUNT(DISTINCT nguoi_dung_id) as so_nhan_vien')
            ->selectRaw('SUM(tong_luong) as tong_luong')
            ->selectRaw('SUM(thue_thu_nhap_ca_nhan) as tong_thue')
            ->selectRaw('SUM(tong_khau_tru) as tong_khau_tru')
            ->selectRaw('SUM(luong_thuc_nhan) as tong_thuc_nhan')
            ->groupBy('luong_nam')
            ->orderByDesc('luong_nam')
            ->get();

        // Bảo hiểm (BHXH + BHYT + BHTN) không lưu trên luong_nhan_vien -> lấy từ bảng khấu trừ chi tiết
        $baoHiemTheoNam = KhauTruLuong::query()
            ->join('luong_nhan_vien', 'luong_nhan_vien.id', '=', 'khau_tru_luong.luong_nhan_vien_id')
            ->when($namChon, fn($q) => $q->where('luong_nhan_vien.luong_nam', $namChon))
            ->whereIn('khau_tru_luong.loai_khau_tru', ['bhxh', 'bhyt', 'bhtn'])
            ->groupBy('luong_nhan_vien.luong_nam')
            ->selectRaw('luong_nhan_vien.luong_nam as nam, SUM(khau_tru_luong.so_tien) as tong_bao_hiem')
            ->pluck('tong_bao_hiem', 'nam');

        $rows->each(function ($r) use ($baoHiemTheoNam) {
            $r->tong_bao_hiem = (float) ($baoHiemTheoNam[$r->nam] ?? 0);
        });

        $tongLuong    = (float) $rows->sum('tong_luong');
        $tongThue     = (float) $rows->sum('tong_thue');
        $tongKhauTru  = (float) $rows->sum('tong_khau_tru');
        $tongBaoHiem  = (float) $rows->sum('tong_bao_hiem');
        $tongThucNhan = (float) $rows->sum('tong_thuc_nhan');

        return view('admin.tong-luong.index', compact(
            'rows',
            'namList',
            'namChon',
            'tongLuong',
            'tongThue',
            'tongKhauTru',
            'tongBaoHiem',
            'tongThucNhan'
        ));
    }

    /**
     * Chi tiết lương của 1 năm: mỗi tháng một dòng.
     */
    public function chiTietNam(Request $request, $nam)
    {
        $nam = (int) $nam;

        $rows = LuongNhanVien::query()
            ->where('luong_nam', $nam)
            ->selectRaw('luong_thang as thang')
            ->selectRaw('COUNT(DISTINCT nguoi_dung_id) as so_nhan_vien')
            ->selectRaw('SUM(tong_luong) as tong_luong')
            ->selectRaw('SUM(thue_thu_nhap_ca_nhan) as tong_thue')
            ->selectRaw('SUM(tong_khau_tru) as tong_khau_tru')
            ->selectRaw('SUM(luong_thuc_nhan) as tong_thuc_nhan')
            ->groupBy('luong_thang')
            ->orderBy('luong_thang')
            ->get();

        $baoHiemTheoThang = KhauTruLuong::query()
            ->join('luong_nhan_vien', 'luong_nhan_vien.id', '=', 'khau_tru_luong.luong_nhan_vien_id')
            ->where('luong_nhan_vien.luong_nam', $nam)
            ->whereIn('khau_tru_luong.loai_khau_tru', ['bhxh', 'bhyt', 'bhtn'])
            ->groupBy('luong_nhan_vien.luong_thang')
            ->selectRaw('luong_nhan_vien.luong_thang as thang, SUM(khau_tru_luong.so_tien) as tong_bao_hiem')
            ->pluck('tong_bao_hiem', 'thang');

        $rows->each(function ($r) use ($baoHiemTheoThang) {
            $r->tong_bao_hiem = (float) ($baoHiemTheoThang[$r->thang] ?? 0);
        });

        $tongLuong    = (float) $rows->sum('tong_luong');
        $tongThue     = (float) $rows->sum('tong_thue');
        $tongKhauTru  = (float) $rows->sum('tong_khau_tru');
        $tongBaoHiem  = (float) $rows->sum('tong_bao_hiem');
        $tongThucNhan = (float) $rows->sum('tong_thuc_nhan');

        return view('admin.tong-luong.chi-tiet', compact(
            'nam',
            'rows',
            'tongLuong',
            'tongThue',
            'tongKhauTru',
            'tongBaoHiem',
            'tongThucNhan'
        ));
    }

    /**
     * Chi tiết lương của 1 THÁNG: mỗi nhân viên một dòng.
     * Bấm vào một nhân viên -> xem phiếu lương chi tiết của họ.
     */
    public function chiTietThang(Request $request, $nam, $thang)
    {
        $nam   = (int) $nam;
        $thang = (int) $thang;

        $luongs = LuongNhanVien::with(['nguoiDung.hoSo', 'nguoiDung.phongBan', 'khauTrus'])
            ->where('luong_nam', $nam)
            ->where('luong_thang', $thang)
            ->get()
            ->sortByDesc('luong_thuc_nhan')
            ->values();

        // Bảo hiểm mỗi dòng lấy từ chi tiết khấu trừ; đồng thời cộng dồn số tổng của tháng
        $tongLuong = $tongThue = $tongKhauTru = $tongBaoHiem = $tongThucNhan = 0.0;
        foreach ($luongs as $lnv) {
            $lnv->bao_hiem = (float) $lnv->khauTrus
                ->whereIn('loai_khau_tru', ['bhxh', 'bhyt', 'bhtn'])
                ->sum('so_tien');
            $tongLuong    += (float) $lnv->tong_luong;
            $tongThue     += (float) $lnv->thue_thu_nhap_ca_nhan;
            $tongKhauTru  += (float) $lnv->tong_khau_tru;
            $tongBaoHiem  += $lnv->bao_hiem;
            $tongThucNhan += (float) $lnv->luong_thuc_nhan;
        }

        return view('admin.tong-luong.chi-tiet-thang', compact(
            'nam',
            'thang',
            'luongs',
            'tongLuong',
            'tongThue',
            'tongKhauTru',
            'tongBaoHiem',
            'tongThucNhan'
        ));
    }
}
