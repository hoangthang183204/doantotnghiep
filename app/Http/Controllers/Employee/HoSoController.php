<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\ChungChiNhanVien;
use App\Models\DaoTaoNhanVien;
use App\Models\HopDongLaoDong;
use App\Models\HoSoNguoiDung;
use App\Models\KyNangNhanVien;
use App\Models\NguoiDung;
use App\Models\NguoiPhuThuoc;
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

            // CV 
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
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

        if ($request->hasFile('cv_file')) {

            $hosoNhanSu = $hoSo->hoSo;

            if ($hosoNhanSu) {

                $cv = $hosoNhanSu->cv;

                if ($cv) {

                    $oldFile = $cv->duong_dan_file;

                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }

                // Lấy file upload
                $file = $request->file('cv_file');

                // Lưu file
                $path = $file->store('cv', 'public');

                $hosoNhanSu->cv()->updateOrCreate(
                    [],
                    [
                        'nguoi_dung_id'     => Auth::id(),
                        'loai_tai_lieu'     => 'cv',
                        'tieu_de'           => 'CV',
                        'ten_file_goc'      => $file->getClientOriginalName(),
                        'duong_dan_file'    => $path,
                        'kich_thuoc_file'   => $file->getSize(),
                        'loai_mime'         => $file->getMimeType(),
                        'nguoi_tai_len_id'  => Auth::id(),
                        'thoi_gian_tai_len' => now(),
                    ]
                );
            }
        }

        if ($request->filled('skills')) {

            foreach ($request->skills as $id => $skill) {

                KyNangNhanVien::where('id', $id)->update([
                    'ten_ky_nang' => $skill['ten_ky_nang'] ?? null,
                    'cap_do' => $skill['cap_do'] ?? null,
                ]);
            }
        }

        if ($request->filled('certificates')) {

            foreach ($request->certificates as $id => $cc) {

                ChungChiNhanVien::where('id', $id)->update([
                    'ten_chung_chi' => $cc['ten_chung_chi'] ?? null,
                    'to_chuc_cap' => $cc['to_chuc_cap'] ?? null,
                    'nam_cap' => $cc['nam_cap'] ?? null,
                    'ngay_het_han' => $cc['ngay_het_han'] ?? null,
                ]);
            }
        }

        if ($request->filled('trainings')) {

            foreach ($request->trainings as $id => $dt) {

                DaoTaoNhanVien::where('id', $id)->update([
                    'ten_khoa_hoc' => $dt['ten_khoa_hoc'] ?? null,
                    'to_chuc' => $dt['to_chuc'] ?? null,
                    'ket_qua' => $dt['ket_qua'] ?? null,
                    'ngay_bat_dau' => $dt['ngay_bat_dau'] ?? null,
                    'ngay_ket_thuc' => $dt['ngay_ket_thuc'] ?? null,
                ]);
            }
        }

        if ($request->filled('dependents')) {

            foreach ($request->dependents as $id => $npt) {

                NguoiPhuThuoc::where('id', $id)->update([
                    'ho_ten' => $npt['ho_ten'] ?? null,
                    'quan_he' => $npt['quan_he'] ?? null,
                    'ma_so_thue' => $npt['ma_so_thue'] ?? null,
                ]);
            }
        }

        if ($request->filled('contract.id')) {

            HopDongLaoDong::where('id', $request->contract['id'])
                ->update([
                    'so_hop_dong' => $request->contract['so_hop_dong'],
                    'loai_hop_dong' => $request->contract['loai_hop_dong'],
                    'ngay_bat_dau' => $request->contract['ngay_bat_dau'],
                    'ngay_ket_thuc' => $request->contract['ngay_ket_thuc'],
                ]);
        }

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
