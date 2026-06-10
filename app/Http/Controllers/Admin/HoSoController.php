<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoSo;
use App\Models\NguoiDung;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HoSoController extends Controller
{
    /**
     * Danh sách hồ sơ nhân viên
     */
    public function index(Request $request)
    {
        $query = HoSo::with('nguoi_dung.vai_tro', 'nguoi_dung.phong_ban', 'nguoi_dung.chuc_vu');

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

        // Lọc theo trạng thái (lấy từ bảng nguoi_dung)
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

        // Lấy danh sách phòng ban để hiển thị filter
        $phongBans = PhongBan::all();

        return view('admin.ho-so.index', compact('hoSos', 'phongBans'));
    }

    /**
     * Chi tiết hồ sơ
     */
    public function show($id)
    {
        $hoSo = HoSo::with('nguoi_dung.vai_tro', 'nguoi_dung.phong_ban', 'nguoi_dung.chuc_vu')
            ->findOrFail($id);

        return view('admin.ho-so.show', compact('hoSo'));
    }

    /**
     * Form sửa hồ sơ
     */
    public function edit($id)
    {
        $hoSo = HoSo::with('nguoi_dung')->findOrFail($id);

        return view('admin.ho-so.edit', compact('hoSo'));
    }

    /**
     * Cập nhật hồ sơ
     */
    public function update(Request $request, $id)
    {
        $hoSo = HoSo::findOrFail($id);

        $validated = $request->validate([
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
            'lien_he_khan_cap' => 'nullable|string|max:255',
            'sdt_khan_cap' => 'nullable|string|max:20',
            'quan_he_khan_cap' => 'nullable|string|max:255',
            'anh_dai_dien' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'anh_cccd_truoc' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'anh_cccd_sau' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Xử lý upload ảnh đại diện
        if ($request->hasFile('anh_dai_dien')) {
            if ($hoSo->anh_dai_dien && Storage::disk('public')->exists($hoSo->anh_dai_dien)) {
                Storage::disk('public')->delete($hoSo->anh_dai_dien);
            }
            $validated['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
        }

        // Xử lý upload ảnh CCCD trước
        if ($request->hasFile('anh_cccd_truoc')) {
            if ($hoSo->anh_cccd_truoc && Storage::disk('public')->exists($hoSo->anh_cccd_truoc)) {
                Storage::disk('public')->delete($hoSo->anh_cccd_truoc);
            }
            $validated['anh_cccd_truoc'] = $request->file('anh_cccd_truoc')->store('cccd', 'public');
        }

        // Xử lý upload ảnh CCCD sau
        if ($request->hasFile('anh_cccd_sau')) {
            if ($hoSo->anh_cccd_sau && Storage::disk('public')->exists($hoSo->anh_cccd_sau)) {
                Storage::disk('public')->delete($hoSo->anh_cccd_sau);
            }
            $validated['anh_cccd_sau'] = $request->file('anh_cccd_sau')->store('cccd', 'public');
        }

        $hoSo->update($validated);

        return redirect()
            ->route('admin.ho-so.index')
            ->with('success', 'Cập nhật hồ sơ thành công');
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
    
}