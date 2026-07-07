<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChungChiNhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChungChiNhanVienController extends Controller
{
    /**
     * Danh sách chứng chỉ
     */
    public function index(Request $request)
    {
        $query = ChungChiNhanVien::with('hoSo');

        if ($request->filled('search')) {

            $keyword = $request->search;

            $query->where(function ($q) use ($keyword) {

                $q->where('ten_chung_chi', 'like', "%{$keyword}%")
                    ->orWhere('to_chuc_cap', 'like', "%{$keyword}%")
                    ->orWhereHas('hoSo', function ($qq) use ($keyword) {

                        $qq->where('ma_nhan_vien', 'like', "%{$keyword}%")
                            ->orWhereRaw("CONCAT(ho,' ',ten) LIKE ?", ["%{$keyword}%"]);

                    });

            });

        }

        $chungChis = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.chung-chi.index',
            compact('chungChis')
        );
    }

    /**
     * Chi tiết
     */
    public function show($id)
{
    $chungChi = ChungChiNhanVien::with('hoSo')
        ->findOrFail($id);

    return view(
        'admin.chung-chi.show',
        compact('chungChi')
    );
}

    /**
     * Form sửa
     */
    public function edit($id)
    {
        $chungChi = ChungChiNhanVien::with('hoSo')
            ->findOrFail($id);

        return view(
            'admin.chung-chi.edit',
            compact('chungChi')
        );
    }

    /**
     * Cập nhật
     */
    public function update(Request $request, $id)
    {
        $request->validate([

            'ten_chung_chi' => 'required|max:255',

            'to_chuc_cap' => 'required|max:255',

            'nam_cap' => 'required|digits:4',

            'ngay_het_han' => 'nullable|date',

            'file_dinh_kem' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',

        ]);

        $chungChi = ChungChiNhanVien::findOrFail($id);

        $data = $request->only([

            'ten_chung_chi',

            'to_chuc_cap',

            'nam_cap',

            'ngay_het_han',

        ]);

        if ($request->hasFile('file_dinh_kem')) {

            if (
                $chungChi->file_dinh_kem &&
                Storage::disk('public')->exists($chungChi->file_dinh_kem)
            ) {

                Storage::disk('public')->delete($chungChi->file_dinh_kem);

            }

            $data['file_dinh_kem'] = $request
                ->file('file_dinh_kem')
                ->store('chung-chi', 'public');
        }

        $chungChi->update($data);

        return redirect()
            ->route('admin.chung-chi.show', $id)
            ->with('success', 'Cập nhật chứng chỉ thành công.');
    }

    /**
     * Xóa
     */
    public function destroy($id)
    {
        $chungChi = ChungChiNhanVien::findOrFail($id);

        if (
            $chungChi->file_dinh_kem &&
            Storage::disk('public')->exists($chungChi->file_dinh_kem)
        ) {

            Storage::disk('public')->delete($chungChi->file_dinh_kem);

        }

        $chungChi->delete();

        return redirect()
            ->route('admin.chung-chi.index')
            ->with('success', 'Đã xóa chứng chỉ.');
    }
}