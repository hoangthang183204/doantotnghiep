<?php

namespace App\Support;

use App\Models\NguoiDung;

class VaiTroNghiepVu
{
    public static function ten($user): array
    {
        if (!$user) {
            return [];
        }

        return $user->vaiTros()->pluck('name')->all();
    }

    public static function co($user, array $ten): bool
    {
        return count(array_intersect(self::ten($user), $ten)) > 0;
    }

    public static function laSuperAdmin($user): bool
    {
        return self::co($user, ['admin', 'Super Admin', 'Admin']);
    }

    public static function laQuanTri($user): bool
    {
        return self::laSuperAdmin($user) || self::co($user, ['quan_tri_vien']);
    }

    public static function laHr($user): bool
    {
        return self::co($user, ['hr', 'HR']);
    }

    public static function laKeToan($user): bool
    {
        return self::co($user, ['ke_toan', 'Ke Toan']);
    }

    /** Kế toán thuần: không kiêm HR hoặc quản trị. */
    public static function laKeToanThuan($user): bool
    {
        return self::laKeToan($user) && !self::laHr($user) && !self::laQuanTri($user);
    }

    public static function thongBaoCho(array $vaiTro, string $tieuDe, string $noiDung, ?string $url = null): void
    {
        $nguoi = NguoiDung::where('trang_thai', 1)
            ->whereHas('vaiTros', fn ($q) => $q->whereIn('name', $vaiTro))
            ->get();

        foreach ($nguoi as $user) {
            $user->notify(new \App\Notifications\ThongBaoChung($tieuDe, $noiDung, $url));
        }
    }
}
