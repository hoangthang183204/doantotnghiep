<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        // Kiểm tra đã đăng nhập chưa
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }

        // Kiểm tra cookie token (cho API)
        if (Cookie::has('access_token')) {
            try {
                $token = Cookie::get('access_token');
                auth('api')->setToken($token);
                
                if (auth('api')->check()) {
                    $user = auth('api')->user();
                    Auth::login($user);
                    return $this->redirectBasedOnRole();
                }
            } catch (\Exception $e) {
                Cookie::forget('access_token');
            }
        }

        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = $request->only('email', 'password');

        // Thử đăng nhập với guard web trước
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Cập nhật thông tin đăng nhập
            $user->update([
                'lan_dang_nhap_cuoi' => now(),
                'ip_dang_nhap_cuoi' => $request->ip()
            ]);

            // Tạo token cho API (nếu cần)
            $token = auth('api')->login($user);
            
            // Lưu token vào cookie
            $cookie = Cookie::make(
                'access_token',
                $token,
                1440, // 24 giờ
                '/',
                null,
                false,
                true,
                false,
                'lax'
            );

            // Lưu thông tin user vào session
            $this->setUserSession($user);

            return $this->redirectBasedOnRole()->withCookie($cookie);
        }

        // Thử đăng nhập với API guard
        if (!$token = auth('api')->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng'
            ])->withInput();
        }

        $user = auth('api')->user();
        
        // Đăng nhập cả guard web
        Auth::login($user);

        $user->update([
            'lan_dang_nhap_cuoi' => now(),
            'ip_dang_nhap_cuoi' => $request->ip()
        ]);

        // Lưu token vào cookie
        $cookie = Cookie::make(
            'access_token',
            $token,
            1440,
            '/',
            null,
            false,
            true,
            false,
            'lax'
        );

        $this->setUserSession($user);

        return $this->redirectBasedOnRole()->withCookie($cookie);
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        // Logout guard web
        Auth::logout();
        
        // Logout guard api
        $token = Cookie::get('access_token');
        if ($token) {
            try {
                auth('api')->setToken($token)->logout();
            } catch (\Exception $e) {
                // Bỏ qua lỗi token invalid
            }
        }

        // Xóa session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Xóa cookie
        $cookie = Cookie::forget('access_token');

        return redirect()->route('login')->withCookie($cookie);
    }

    /**
     * Chuyển hướng dựa trên vai trò
     */
    protected function redirectBasedOnRole()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Lấy vai trò đầu tiên của user
        $vaiTro = $user->vaiTros->first();
        $roleName = $vaiTro ? $vaiTro->name : 'nhan_vien';

        // Kiểm tra user có quyền admin không
        $isAdmin = in_array($roleName, ['admin', 'Super Admin', 'Admin']);
        
        // Hoặc kiểm tra user có role admin không
        if ($user->vaiTros()->whereIn('name', ['admin', 'Super Admin', 'Admin'])->exists()) {
            return redirect()->route('admin.dashboard');
        }

        // Mặc định là employee
        return redirect()->route('employee.dashboard');
    }

    /**
     * Lưu thông tin user vào session
     */
    protected function setUserSession($user)
    {
        $hoSo = $user->hoSo;
        $phongBan = $user->phongBan;
        $vaiTro = $user->vaiTros->first();

        session()->put('user', [
            'id' => $user->id,
            'ten_dang_nhap' => $user->ten_dang_nhap,
            'email' => $user->email,
            'vai_tro' => $vaiTro ? $vaiTro->ten_hien_thi : 'Nhân viên',
            'vai_tro_name' => $vaiTro ? $vaiTro->name : 'nhan_vien',
            'ho_ten' => $hoSo ? ($hoSo->ho . ' ' . $hoSo->ten) : $user->ten_dang_nhap,
            'ma_nhan_vien' => $hoSo ? $hoSo->ma_nhan_vien : null,
            'phong_ban' => $phongBan ? $phongBan->ten_phong_ban : null,
            'phong_ban_id' => $phongBan ? $phongBan->id : null,
            'avatar' => $hoSo ? $hoSo->anh_dai_dien : null,
        ]);
    }
}