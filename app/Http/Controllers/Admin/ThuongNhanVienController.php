<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiThuong;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use App\Models\ThuongNhanVien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Quản lý các khoản thưởng của nhân viên.
 *
 * Hai hình thức:
 *  - Thưởng ĐỊNH KỲ  : mặc định lặp lại hàng tháng trong khoảng hiệu lực.
 *  - Thưởng 1 LẦN    : chỉ áp dụng đúng 1 kỳ lương (tháng/năm).
 *
 * Khoản thưởng ở trạng thái "hiệu lực" sẽ được TinhLuongService cộng vào
 * tổng lương khi tính (hoặc tính lại) bảng lương tháng tương ứng.
 */
class ThuongNhanVienController extends Controller
{
    /** Danh sách khoản thưởng áp dụng cho 1 kỳ lương */
    public function index(Request $request)
    {
        $macDinh = Carbon::now();
        $thang   = (int) $request->input('thang', $macDinh->month);
        $nam     = (int) $request->input('nam', $macDinh->year);

        $query = ThuongNhanVien::with(['nguoiDung.ho_so', 'loaiThuong'])
            ->apDungChoKy($thang, $nam);

        if ($request->filled('hinh_thuc')) {
            $query->where('hinh_thuc', $request->input('hinh_thuc'));
        }

        if ($request->filled('loai_thuong_id')) {
            $query->where('loai_thuong_id', $request->input('loai_thuong_id'));
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->input('trang_thai'));
        }

        $khoanThuongs = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        // Tổng thưởng của kỳ: quy đổi cả khoản tính theo % lương cơ bản
        $hieuLuc = ThuongNhanVien::with(['loaiThuong', 'nguoiDung'])
            ->hieuLuc()
            ->apDungChoKy($thang, $nam)
            ->get();

        $tongTien = round($hieuLuc->sum(
            fn($tt) => $tt->tinhSoTien((float) ($tt->nguoiDung->luong_co_ban ?? 0))
        ), 2);

        $tongDinhKy = $hieuLuc->where('hinh_thuc', 'dinh_ky')->count();
        $tongMotLan = $hieuLuc->where('hinh_thuc', 'mot_lan')->count();

        $loaiThuongs = LoaiThuong::orderBy('ten')->get();

        return view('admin.thuong.index', compact(
            'khoanThuongs', 'thang', 'nam', 'tongTien', 'tongDinhKy', 'tongMotLan', 'loaiThuongs'
        ));
    }

    /** Form thêm khoản thưởng (hỗ trợ gán hàng loạt) */
    public function create(Request $request)
    {
        $macDinh = Carbon::now();
        $thang   = (int) $request->input('thang', $macDinh->month);
        $nam     = (int) $request->input('nam', $macDinh->year);

        $loaiThuongs = LoaiThuong::hoatDong()->orderBy('ten')->get();
        $nhanViens   = NguoiDung::with(['ho_so', 'phongBan'])->where('trang_thai', 1)->get();
        $phongBans   = PhongBan::orderBy('ten_phong_ban')->get();

        return view('admin.thuong.create', compact(
            'loaiThuongs', 'nhanViens', 'phongBans', 'thang', 'nam'
        ));
    }

    /** Lưu khoản thưởng cho 1 hoặc nhiều nhân viên */
    public function store(Request $request)
    {
        $data       = $this->validateData($request);
        $nhanVienIds = $this->layNhanVienIds($request);

        if (empty($nhanVienIds)) {
            return back()->withInput()->with('error', 'Chưa chọn nhân viên nào để áp dụng thưởng.');
        }

        DB::transaction(function () use ($data, $nhanVienIds) {
            foreach ($nhanVienIds as $id) {
                ThuongNhanVien::create(array_merge($data, [
                    'nguoi_dung_id' => $id,
                    'nguoi_tao_id'  => auth()->id(),
                ]));
            }
        });

        $moTaKy = $data['hinh_thuc'] === 'mot_lan'
            ? 'kỳ lương ' . $data['thang'] . '/' . $data['nam']
            : 'hàng tháng kể từ ' . ($data['ngay_bat_dau'] ?? 'nay');

        return redirect()
            ->route('admin.thuong.index', [
                'thang' => $data['thang'] ?? Carbon::now()->month,
                'nam'   => $data['nam'] ?? Carbon::now()->year,
            ])
            ->with('success', sprintf(
                'Đã thêm khoản thưởng cho %d nhân viên, áp dụng %s.',
                count($nhanVienIds),
                $moTaKy
            ));
    }

    public function show($id)
    {
        $thuong = ThuongNhanVien::with(['nguoiDung.ho_so', 'loaiThuong', 'nguoiTao'])->findOrFail($id);
        $soTien = $thuong->tinhSoTien((float) ($thuong->nguoiDung->luong_co_ban ?? 0));

        return view('admin.thuong.show', compact('thuong', 'soTien'));
    }

    public function edit($id)
    {
        $thuong      = ThuongNhanVien::findOrFail($id);
        $loaiThuongs = LoaiThuong::orderBy('ten')->get();
        $nhanViens   = NguoiDung::with('ho_so')->where('trang_thai', 1)->get();

        return view('admin.thuong.edit', compact('thuong', 'loaiThuongs', 'nhanViens'));
    }

    public function update(Request $request, $id)
    {
        $thuong = ThuongNhanVien::findOrFail($id);
        $data   = $this->validateData($request);

        $request->validate(['nguoi_dung_id' => 'required|integer|exists:nguoi_dung,id']);
        $data['nguoi_dung_id'] = $request->input('nguoi_dung_id');

        $thuong->update($data);

        return redirect()
            ->route('admin.thuong.index', ['thang' => $thuong->thang ?? Carbon::now()->month, 'nam' => $thuong->nam ?? Carbon::now()->year])
            ->with('success', 'Đã cập nhật khoản thưởng.');
    }

    public function destroy($id)
    {
        $thuong = ThuongNhanVien::findOrFail($id);
        $thang  = $thuong->thang ?? Carbon::now()->month;
        $nam    = $thuong->nam ?? Carbon::now()->year;
        $thuong->delete();

        return redirect()
            ->route('admin.thuong.index', ['thang' => $thang, 'nam' => $nam])
            ->with('success', 'Đã xoá khoản thưởng.');
    }

    /** Bật lại khoản thưởng đang tạm dừng/huỷ */
    public function kichHoat($id)
    {
        ThuongNhanVien::findOrFail($id)->update(['trang_thai' => 'hieu_luc']);

        return back()->with('success', 'Đã kích hoạt khoản thưởng.');
    }

    /** Tạm dừng khoản thưởng (không tính vào lương từ lần tính tiếp theo) */
    public function tamDung($id)
    {
        ThuongNhanVien::findOrFail($id)->update(['trang_thai' => 'tam_dung']);

        return back()->with('success', 'Đã tạm dừng khoản thưởng.');
    }

    /** Huỷ khoản thưởng */
    public function huy($id)
    {
        ThuongNhanVien::findOrFail($id)->update(['trang_thai' => 'huy']);

        return back()->with('success', 'Đã huỷ khoản thưởng.');
    }

    // =====================================================================
    // INTERNAL
    // =====================================================================

    /**
     * Danh sách nhân viên được áp dụng: chọn tay, cả phòng ban, hoặc toàn công ty.
     *
     * @return array<int, int>
     */
    private function layNhanVienIds(Request $request): array
    {
        $pham_vi = $request->input('pham_vi', 'nhan_vien');

        $ids = match ($pham_vi) {
            'toan_cong_ty' => NguoiDung::where('trang_thai', 1)->pluck('id')->all(),
            'phong_ban'    => NguoiDung::where('trang_thai', 1)
                ->whereIn('phong_ban_id', (array) $request->input('phong_ban_ids', []))
                ->pluck('id')->all(),
            default        => (array) $request->input('nguoi_dung_ids', []),
        };

        return array_values(array_unique(array_map('intval', array_filter($ids))));
    }

    private function validateData(Request $request): array
    {
        $rules = [
            'loai_thuong_id' => 'required|integer|exists:loai_thuong,id',
            'hinh_thuc'      => 'required|in:dinh_ky,mot_lan',
            'cach_tinh'      => 'required|in:so_tien_co_dinh,phan_tram_luong_cb',
            'gia_tri'        => 'required|numeric|min:0',
            'ly_do'          => 'nullable|string|max:255',
            'trang_thai'     => 'required|in:hieu_luc,tam_dung,huy',
            'chiu_thue'      => 'nullable|in:mac_dinh,co,khong',
        ];

        // Tính theo % lương cơ bản thì giá trị là phần trăm, không vượt quá 100
        if ($request->input('cach_tinh') === 'phan_tram_luong_cb') {
            $rules['gia_tri'] = 'required|numeric|min:0|max:100';
        }

        // Thưởng 1 lần bắt buộc có kỳ lương; thưởng định kỳ bắt buộc có ngày bắt đầu
        if ($request->input('hinh_thuc') === 'mot_lan') {
            $rules['thang'] = 'required|integer|between:1,12';
            $rules['nam']   = 'required|integer|min:2000|max:2100';
        } else {
            $rules['ngay_bat_dau']  = 'required|date';
            $rules['ngay_ket_thuc'] = 'nullable|date|after_or_equal:ngay_bat_dau';
        }

        $data = $request->validate($rules, [], [
            'loai_thuong_id' => 'loại thưởng',
            'gia_tri'        => 'giá trị',
            'ngay_bat_dau'   => 'ngày bắt đầu',
            'ngay_ket_thuc'  => 'ngày kết thúc',
        ]);

        // Chuẩn hoá các trường theo hình thức để không lẫn dữ liệu thừa
        if ($data['hinh_thuc'] === 'mot_lan') {
            $data['ngay_bat_dau']  = null;
            $data['ngay_ket_thuc'] = null;
        } else {
            $data['thang'] = null;
            $data['nam']   = null;
        }

        // chiu_thue: mac_dinh = theo loại thưởng (null)
        $data['chiu_thue'] = match ($request->input('chiu_thue', 'mac_dinh')) {
            'co'    => true,
            'khong' => false,
            default => null,
        };

        return $data;
    }
}
