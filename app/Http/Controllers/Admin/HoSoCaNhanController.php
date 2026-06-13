<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash; // Dùng để check và đổi mật khẩu
use Illuminate\Support\Facades\Storage; // Dùng để xóa/lưu file ảnh

class HoSoCaNhanController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = NguoiDung::with(['phong_ban', 'chuc_vu', 'hoSo'])->find($userId);
        return view('admin.ho-so-ca-nhan.index', compact('user'));
    }

    // Hàm xử lý cập nhật thông tin và upload ảnh
    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = NguoiDung::with('hoSo')->find($userId);
        $hoSo = $user->hoSo;

        // Xử lý Upload Ảnh Đại Diện
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có (tránh rác bộ nhớ)
            if ($hoSo->anh_dai_dien) Storage::disk('public')->delete($hoSo->anh_dai_dien);
            // Lưu ảnh mới
            $hoSo->anh_dai_dien = $request->file('avatar')->store('avatars', 'public');
        }

        // Xử lý Upload CCCD Mặt Trước
        if ($request->hasFile('anh_cccd_truoc')) {
            if ($hoSo->anh_cccd_truoc) Storage::disk('public')->delete($hoSo->anh_cccd_truoc);
            $hoSo->anh_cccd_truoc = $request->file('anh_cccd_truoc')->store('cccd', 'public');
        }

        // Xử lý Upload CCCD Mặt Sau
        if ($request->hasFile('anh_cccd_sau')) {
            if ($hoSo->anh_cccd_sau) Storage::disk('public')->delete($hoSo->anh_cccd_sau);
            $hoSo->anh_cccd_sau = $request->file('anh_cccd_sau')->store('cccd', 'public');
        }

        // Cập nhật các trường thông tin text
        $hoSo->ho = $request->input('ho');
        $hoSo->ten = $request->input('ten');
        $hoSo->so_dien_thoai = $request->input('so_dien_thoai');
        $hoSo->ngay_sinh = $request->input('ngay_sinh');
        $hoSo->gioi_tinh = $request->input('gioi_tinh');
        $hoSo->tinh_trang_hon_nhan = $request->input('tinh_trang_hon_nhan');
        $hoSo->dia_chi_hien_tai = $request->input('dia_chi_hien_tai');
        $hoSo->dia_chi_thuong_tru = $request->input('dia_chi_thuong_tru');
        $hoSo->cmnd_cccd = $request->input('cmnd_cccd');
        $hoSo->so_ho_chieu = $request->input('so_ho_chieu');
        $hoSo->lien_he_khan_cap = $request->input('lien_he_khan_cap');
        $hoSo->sdt_khan_cap = $request->input('sdt_khan_cap');
        $hoSo->quan_he_khan_cap = $request->input('quan_he_khan_cap');

        // Lưu dữ liệu
        $hoSo->save();

        return redirect()->back()->with('success', 'Đã cập nhật hồ sơ thành công!');
    }

    // Hàm xử lý đổi mật khẩu
    public function changePassword(Request $request)
{
    // 1. Validate dữ liệu nhập vào
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6', // Tối thiểu 6 ký tự
        'new_password_confirmation' => 'required|same:new_password', // Phải khớp với mật khẩu mới
    ], [
        // Tùy chỉnh câu thông báo lỗi cho thân thiện (Tùy chọn)
        'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
        'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
        'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
        'new_password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
        'new_password_confirmation.same' => 'Xác nhận mật khẩu không khớp.'
    ]);

    $user = NguoiDung::find(Auth::id());

    // 2. Kiểm tra mật khẩu cũ có đúng không
    if (!Hash::check($request->current_password, $user->password)) {
        // Trả về kèm lỗi nếu sai
        return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
    }

    // 3. Nếu đúng thì lưu mật khẩu mới
    $user->password = Hash::make($request->new_password);
    $user->save();

    // 4. Trả về kèm thông báo thành công
    return back()->with('success', 'Đổi mật khẩu thành công!');
}
}