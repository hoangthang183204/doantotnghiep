<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BangLuong;
use App\Models\LuongNhanVien;
use App\Models\NguoiDung;
use App\Services\TinhLuongService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BangLuongController extends Controller
{
    public function __construct(private TinhLuongService $tinhLuong) {}

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
            'khauTruLuongs',
        ])->where('bang_luong_id', $id)->findOrFail($luongId);

        return view('admin.bang-luong.chi-tiet-nhan-vien', compact('bangLuong', 'luong'));
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
}
