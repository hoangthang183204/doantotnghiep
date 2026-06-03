<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Kiểm tra cookie đã có token chưa
        if (Cookie::has('access_token')) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = $request->only('email', 'password');
        
        if (!$token = auth('api')->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng'
            ])->withInput();
        }

        $user = auth('api')->user();
        
        $user->update([
            'lan_dang_nhap_cuoi' => now(),
            'ip_dang_nhap_cuoi' => $request->ip()
        ]);

        // Lưu user vào session (tạm thời)
        session()->put('user', [
            'id' => $user->id,
            'ten_dang_nhap' => $user->ten_dang_nhap,
            'email' => $user->email,
            'vai_tro' => $user->vai_tro->ten_hien_thi ?? 'Nhân viên',
            'ho_ten' => $user->ho_so->ho_ten ?? $user->ten_dang_nhap,
            'ma_nhan_vien' => $user->ho_so->ma_nhan_vien ?? null,
            'phong_ban' => $user->phong_ban->ten_phong_ban ?? null,
        ]);
        
        // Tạo cookie lưu token (thời gian 1 ngày = 1440 phút)
        $cookie = Cookie::make(
            'access_token',           // name
            $token,                   // value
            1440,                     // minutes (24 giờ)
            '/',                      // path
            null,                     // domain
            false,                    // secure (true nếu dùng HTTPS)
            true,                     // httpOnly
            false,                    // raw
            'lax'                     // sameSite
        );
        
        return $this->redirectBasedOnRole()->withCookie($cookie);
    }

    public function logout()
    {
        $token = Cookie::get('access_token');
        
        if ($token) {
            auth('api')->setToken($token)->logout();
        }
        
        // Xóa cookie
        $cookie = Cookie::forget('access_token');
        session()->flush();
        
        return redirect()->route('login')->withCookie($cookie);
    }

    private function redirectBasedOnRole()
    {
        $user = session()->get('user');
        $role = $user['vai_tro'] ?? 'Nhân viên';
        
        return match ($role) {
            'Super Admin', 'Admin' => redirect()->route('admin.dashboard'),
            'Trưởng phòng' => redirect()->route('truong-phong.dashboard'),
            'Kế toán' => redirect()->route('ke-toan.dashboard'),
            default => redirect()->route('nhan-vien.dashboard'),
        };
    }
}