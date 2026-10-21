<?php

namespace App\Support;

use App\Models\PhongBan;

class PhamViDuLieu
{
    public static function xemHetLuong($user): bool
    {
        if (!$user) {
            return false;
        }

        return VaiTroNghiepVu::laSuperAdmin($user)
            || $user->hasPermission('salary.index')
            || $user->vaiTros()->where('name', 'ke_toan')->exists();
    }

    /**
     * null: được xem mọi phòng. Mảng: chỉ các phòng mình làm trưởng phòng.
     */
    public static function gioiHanPhong($user): ?array
    {
        if (self::xemHetLuong($user)) {
            return null;
        }

        return PhongBan::where('truong_phong_id', $user->id)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public static function duocXemNhanVien($user, $nhanVien): bool
    {
        if (!$user || !$nhanVien) {
            return false;
        }
        if ((int) $user->id === (int) $nhanVien->id) {
            return true;
        }

        $gioiHan = self::gioiHanPhong($user);
        if ($gioiHan === null) {
            return true;
        }

        return in_array((int) ($nhanVien->phong_ban_id ?? 0), $gioiHan, true);
    }
}
