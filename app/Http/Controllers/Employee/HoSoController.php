<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\HoSoNguoiDung;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HoSoController extends Controller
{
    public function index()
    {
        /** @var NguoiDung $user */
        $user = Auth::user();

        $user->load([
            'hoSo',
            'phong_ban',
            'chuc_vu',
            'vai_tro',

            'hoSo.hoSo.cv',
            'hoSo.hoSo.ky_nang',
            'hoSo.hoSo.chung_chi',
            'hoSo.hoSo.dao_tao',
            'hoSo.hoSo.nguoiPhuThuoc',
            'hoSo.hoSo.hop_dong',
            'hoSo.hoSo.khen_thuong_ky_luat',
        ]);

        return view('employee.ho-so.index', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ho' => 'required|string|max:100',
            'ten' => 'required|string|max:100',

            'so_dien_thoai' => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:nam,nu,khac',

            'dia_chi_hien_tai' => 'nullable|string|max:500',
            'dia_chi_thuong_tru' => 'nullable|string|max:500',

            'cmnd_cccd' => 'nullable|string|max:20',
            'so_ho_chieu' => 'nullable|string|max:50',

            'tinh_trang_hon_nhan' => 'nullable|string|max:50',

            'lien_he_khan_cap' => 'nullable|string|max:255',
            'sdt_khan_cap' => 'nullable|string|max:20',
            'quan_he_khan_cap' => 'nullable|string|max:100',

            // Ngân hàng
            'chu_tai_khoan' => 'nullable|string|max:255',
            'so_tai_khoan' => 'nullable|string|max:100',
            'ten_ngan_hang' => 'nullable|string|max:255',
            'chi_nhanh_ngan_hang' => 'nullable|string|max:255',

            // BHXH & Thuế
            'so_bhxh' => 'nullable|string|max:100',
            'ma_so_thue' => 'nullable|string|max:100',
            'noi_dang_ky_kcb' => 'nullable|string|max:255',

            // Hình ảnh
            'anh_dai_dien' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'anh_cccd_truoc' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'anh_cccd_sau' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /** @var NguoiDung $user */
        $user = Auth::user();

        $hoSo = $user->hoSo;

        if (!$hoSo) {
            $hoSo = HoSoNguoiDung::create([
                'nguoi_dung_id' => $user->id,
            ]);
        }

        $data = [

            // Cá nhân
            'ho' => $request->ho,
            'ten' => $request->ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,

            // Địa chỉ
            'dia_chi_hien_tai' => $request->dia_chi_hien_tai,
            'dia_chi_thuong_tru' => $request->dia_chi_thuong_tru,

            // Giấy tờ
            'cmnd_cccd' => $request->cmnd_cccd,
            'so_ho_chieu' => $request->so_ho_chieu,

            // Hôn nhân
            'tinh_trang_hon_nhan' => $request->tinh_trang_hon_nhan,

            // Khẩn cấp
            'lien_he_khan_cap' => $request->lien_he_khan_cap,
            'sdt_khan_cap' => $request->sdt_khan_cap,
            'quan_he_khan_cap' => $request->quan_he_khan_cap,

            // Ngân hàng
            'chu_tai_khoan' => $request->chu_tai_khoan,
            'so_tai_khoan' => $request->so_tai_khoan,
            'ten_ngan_hang' => $request->ten_ngan_hang,
            'chi_nhanh_ngan_hang' => $request->chi_nhanh_ngan_hang,

            // BHXH & Thuế
            'so_bhxh' => $request->so_bhxh,
            'ma_so_thue' => $request->ma_so_thue,
            'noi_dang_ky_kcb' => $request->noi_dang_ky_kcb,
        ];

        /*
    |--------------------------------------------------------------------------
    | Avatar
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('anh_dai_dien')) {

            if (
                $hoSo->anh_dai_dien &&
                Storage::disk('public')->exists($hoSo->anh_dai_dien)
            ) {
                Storage::disk('public')->delete($hoSo->anh_dai_dien);
            }

            $data['anh_dai_dien'] = $request
                ->file('anh_dai_dien')
                ->store('avatars', 'public');
        }

        /*
    |--------------------------------------------------------------------------
    | CCCD mặt trước
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('anh_cccd_truoc')) {

            if (
                $hoSo->anh_cccd_truoc &&
                Storage::disk('public')->exists($hoSo->anh_cccd_truoc)
            ) {
                Storage::disk('public')->delete($hoSo->anh_cccd_truoc);
            }

            $data['anh_cccd_truoc'] = $request
                ->file('anh_cccd_truoc')
                ->store('cccd', 'public');
        }

        /*
    |--------------------------------------------------------------------------
    | CCCD mặt sau
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('anh_cccd_sau')) {

            if (
                $hoSo->anh_cccd_sau &&
                Storage::disk('public')->exists($hoSo->anh_cccd_sau)
            ) {
                Storage::disk('public')->delete($hoSo->anh_cccd_sau);
            }

            $data['anh_cccd_sau'] = $request
                ->file('anh_cccd_sau')
                ->store('cccd', 'public');
        }

        $hoSo->update($data);

        return redirect()
            ->route('employee.ho-so.index')
            ->with(
                'success',
                'Cập nhật hồ sơ thành công'
            );
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /** @var \App\Models\NguoiDung $user */
        $user = Auth::user();

        if (!$user || !$user->password) {
            return back()->withErrors([
                'current_password' => 'User hoặc password không tồn tại'
            ]);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không chính xác'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công');
    }
}
