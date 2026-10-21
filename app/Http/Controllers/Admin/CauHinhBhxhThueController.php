<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CauHinhBhxhThue;
use Illuminate\Http\Request;

class CauHinhBhxhThueController extends Controller
{
    public function index()
    {
        $cauHinhs = CauHinhBhxhThue::orderByDesc('nam')->get();
        $sua = null;
        if (request('nam')) {
            $sua = CauHinhBhxhThue::where('nam', request('nam'))->first();
        }
        return view('admin.bhxh-thue.index', compact('cauHinhs', 'sua'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nam' => 'required|integer|min:2020|max:2100',
            'ty_le_bhxh_nld' => 'required|numeric|min:0|max:50',
            'ty_le_bhyt_nld' => 'required|numeric|min:0|max:50',
            'ty_le_bhtn_nld' => 'required|numeric|min:0|max:50',
            'ty_le_bhxh_dn' => 'required|numeric|min:0|max:50',
            'ty_le_bhyt_dn' => 'required|numeric|min:0|max:50',
            'ty_le_bhtn_dn' => 'required|numeric|min:0|max:50',
            'giam_tru_ban_than' => 'required|numeric|min:0',
            'giam_tru_npt' => 'required|numeric|min:0',
            'tran_dong_bhxh' => 'required|numeric|min:0',
            'bac_thue' => 'required|string',
            'ghi_chu' => 'nullable|string|max:255',
        ]);

        $bac = json_decode($data['bac_thue'], true);
        if (!is_array($bac) || count($bac) < 1) {
            return back()->withInput()->with('error', 'Biểu thuế phải là JSON gồm các bậc den và thue_suat.');
        }

        foreach (['ty_le_bhxh_nld', 'ty_le_bhyt_nld', 'ty_le_bhtn_nld', 'ty_le_bhxh_dn', 'ty_le_bhyt_dn', 'ty_le_bhtn_dn'] as $key) {
            $data[$key] = round($data[$key] / 100, 4);
        }
        $data['bac_thue'] = $bac;

        CauHinhBhxhThue::updateOrCreate(['nam' => $data['nam']], $data);

        return redirect()->route('admin.bhxh-thue.index')->with('success', 'Đã lưu cấu hình BHXH / thuế năm ' . $data['nam']);
    }
}
