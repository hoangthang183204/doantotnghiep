<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function can($permission): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->hasPermission($permission);
    }

    public static function canAny(array $permissions): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->hasAnyPermission($permissions);
    }

    public static function canAll(array $permissions): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->hasAllPermissions($permissions);
    }

    public static function isAdmin(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->isAdmin();
    }

    public static function isHR(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->isHR();
    }

    public static function isTruongPhong(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->isTruongPhong();
    }

    public static function isNhanVien(): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $user->isNhanVien();
    }
}