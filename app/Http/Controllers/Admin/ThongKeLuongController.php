<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LuongNhanVien;
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
}
