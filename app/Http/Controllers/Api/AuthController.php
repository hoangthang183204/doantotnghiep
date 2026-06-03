<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class AuthController extends Controller
{
    /**
     * Đăng nhập
     * POST /api/login
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Sửa: thêm guard('api') để xác định rõ
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        // Lấy thông tin user
        $user = auth('api')->user();
        
        // Cập nhật thời gian đăng nhập cuối
        if ($user) {
            $user->update([
                'lan_dang_nhap_cuoi' => now(),
                'ip_dang_nhap_cuoi' => $request->ip()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id ?? null,
                'ten_dang_nhap' => $user->ten_dang_nhap ?? null,
                'email' => $user->email ?? null,
                'vai_tro' => $user->vai_tro->ten_hien_thi ?? null,
                'ho_ten' => $user->ho_so->ho_ten ?? $user->ten_dang_nhap ?? null,
                'ma_nhan_vien' => $user->ho_so->ma_nhan_vien ?? null,
                'phong_ban' => $user->phong_ban->ten_phong_ban ?? null,
            ]
        ]);
    }

    /**
     * Lấy thông tin user hiện tại
     * GET /api/me
     */
    public function me(): JsonResponse
    {
        $user = auth('api')->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id ?? null,
                'ten_dang_nhap' => $user->ten_dang_nhap ?? null,
                'email' => $user->email ?? null,
                'vai_tro' => $user->vai_tro->ten_hien_thi ?? null,
                'ho_so' => $user->ho_so,
                'phong_ban' => $user->phong_ban,
                'chuc_vu' => $user->chuc_vu,
                'trang_thai' => $user->trang_thai ?? null,
                'trang_thai_cong_viec' => $user->trang_thai_cong_viec ?? null,
                'lan_dang_nhap_cuoi' => $user->lan_dang_nhap_cuoi ?? null,
                'ip_dang_nhap_cuoi' => $user->ip_dang_nhap_cuoi ?? null,
            ]
        ]);
    }

    /**
     * Đăng xuất
     * POST /api/logout
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();
        
        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /**
     * Refresh token
     * POST /api/refresh
     */
    public function refresh(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'access_token' => auth('api')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}