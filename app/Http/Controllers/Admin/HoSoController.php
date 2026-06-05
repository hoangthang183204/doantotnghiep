<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HoSoController extends Controller
{
    public function index(Request $request)
    {
        $query = HoSo::query();

        if ($request->keyword) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where('ho', 'like', "%{$keyword}%")
                    ->orWhere('ten', 'like', "%{$keyword}%")
                    ->orWhere('email_cong_ty', 'like', "%{$keyword}%")
                    ->orWhere('ma_nhan_vien', 'like', "%{$keyword}%")
                    ->orWhereRaw(
                        "CONCAT(ho,' ',ten) LIKE ?",
                        ["%{$keyword}%"]
                    );
            });
        }

        $hoSos = $query->latest()->paginate(10);

        return view('admin.ho-so.index', compact('hoSos'));
    }

    public function show($id)
    {
        $hoSo = HoSo::findOrFail($id);

        return view('admin.ho-so.show', compact('hoSo'));
    }

    public function edit($id)
    {
        $hoSo = HoSo::findOrFail($id);

        return view('admin.ho-so.edit', compact('hoSo'));
    }

    public function update(Request $request, $id)
    {
        $hoSo = HoSo::findOrFail($id);

        $validated = $request->validate([
            'ho' => 'required|string|max:255',
            'ten' => 'required|string|max:255',
            'email_cong_ty' => 'nullable|email',
            'so_dien_thoai' => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|string',
            'dia_chi_hien_tai' => 'nullable|string',
            'anh_dai_dien' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('anh_dai_dien')) {

            if (
                $hoSo->anh_dai_dien &&
                Storage::disk('public')->exists($hoSo->anh_dai_dien)
            ) {
                Storage::disk('public')->delete($hoSo->anh_dai_dien);
            }

            $validated['anh_dai_dien'] = $request
                ->file('anh_dai_dien')
                ->store('avatars', 'public');
        }

        $hoSo->update($validated);

        return redirect()
            ->route('admin.ho-so.index')
            ->with('success', 'Cập nhật thành công');
    }

    // =========================
    // 🔴 ĐÁNH DẤU NGHỈ VIỆC
    // =========================
    public function resign($id)
    {
        $hoSo = HoSo::findOrFail($id);

        $hoSo->trang_thai = 0; // 0 = nghỉ việc
        $hoSo->save();

        return back()->with('success', 'Đã đánh dấu nhân viên nghỉ việc');
    }

    // =========================
    // 🟢 KÍCH HOẠT LẠI
    // =========================
    public function activate($id)
    {
        $hoSo = HoSo::findOrFail($id);

        $hoSo->trang_thai = 1; // 1 = đang làm
        $hoSo->save();

        return back()->with('success', 'Đã kích hoạt nhân viên');
    }
}
