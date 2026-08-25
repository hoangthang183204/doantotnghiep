<?php
// app/Http/Controllers/Auth/LoginController.php

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
                    
                    // ⭐ KIỂM TRA TRẠNG THÁI TÀI KHOẢN
                    if ($user->trang_thai != 1) {
                        Cookie::forget('access_token');
                        return view('auth.login')->withErrors([
                            'email' => 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động. Vui lòng liên hệ quản trị viên.'
                        ]);
                    }
                    
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

        // ⭐ KIỂM TRA TÀI KHOẢN CÓ TỒN TẠI VÀ TRẠNG THÁI TRƯỚC KHI ĐĂNG NHẬP
        $user = \App\Models\NguoiDung::where('email', $request->email)->first();

        // Kiểm tra tài khoản có bị khóa không
        if ($user && $user->trang_thai != 1) {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động. Vui lòng liên hệ quản trị viên.'
            ])->withInput();
        }

        // Thử đăng nhập với guard web trước
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ⭐ KIỂM TRA LẠI TRẠNG THÁI (đề phòng trường hợp user bị khóa sau khi attempt)
            if ($user->trang_thai != 1) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động.'
                ])->withInput();
            }

            // Cập nhật thông tin đăng nhập
            $user->update([
                'lan_dang_nhap_cuoi' => now(),
                'ip_dang_nhap_cuoi' => $request->ip(),
                'dang_nhap_lan_dau' => 0 // Đánh dấu đã đăng nhập
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

        // ⭐ KIỂM TRA TRẠNG THÁI
        if ($user->trang_thai != 1) {
            auth('api')->logout();
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động. Vui lòng liên hệ quản trị viên.'
            ])->withInput();
        }

        // Đăng nhập cả guard web
        Auth::login($user);

        $user->update([
            'lan_dang_nhap_cuoi' => now(),
            'ip_dang_nhap_cuoi' => $request->ip(),
            'dang_nhap_lan_dau' => 0
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
     * ⭐ Chuyển hướng dựa trên vai trò - TẤT CẢ VÀO CHẤM CÔNG, TRỪ ADMIN
     */
    protected function redirectBasedOnRole()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Lấy danh sách vai trò của user
        $roleNames = $user->vaiTros->pluck('name')->toArray();
        
        // ===== LOG ĐỂ DEBUG =====
        \Log::info('User roles:', ['user_id' => $user->id, 'email' => $user->email, 'roles' => $roleNames]);

        // ============================================================
        // ⭐ CHỈ ADMIN, SUPER ADMIN MỚI VÀO ADMIN DASHBOARD
        // ============================================================
        
        // 1️⃣ ADMIN -> admin dashboard (CHỈ ADMIN MỚI ĐƯỢC VÀO)
        if (array_intersect($roleNames, ['admin', 'Super Admin', 'Admin'])) {
            return redirect()->route('admin.dashboard');
        }

        // ============================================================
        // ⭐ TẤT CẢ CÁC VAI TRÒ KHÁC (HR, TRƯỞNG PHÒNG, KẾ TOÁN, NHÂN VIÊN) ĐỀU VÀO CHẤM CÔNG
        // ============================================================
        
        // 2️⃣ HR -> vào trang chấm công (không vào admin)
        // 3️⃣ TRƯỞNG PHÒNG -> vào trang chấm công (không vào admin)
        // 4️⃣ KẾ TOÁN -> vào trang chấm công (không vào admin)
        // 5️⃣ NHÂN VIÊN -> vào trang chấm công

        // Thêm thông báo chào mừng
        $hoTen = $user->hoSo ? ($user->hoSo->ho . ' ' . $user->hoSo->ten) : $user->ten_dang_nhap;
        session()->flash('info', '👋 Chào mừng ' . $hoTen . '! Vui lòng chấm công để bắt đầu ngày làm việc.');
        
        return redirect()->route('employee.cham-cong.index');
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
            'trang_thai' => $user->trang_thai,
        ]);
    }
}