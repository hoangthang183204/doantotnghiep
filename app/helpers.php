<?php

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}

if (!function_exists('isHR')) {
    function isHR()
    {
        return auth()->check() && auth()->user()->isHR();
    }
}

if (!function_exists('isTruongPhong')) {
    function isTruongPhong()
    {
        return auth()->check() && auth()->user()->isTruongPhong();
    }
}

if (!function_exists('isNhanVien')) {
    function isNhanVien()
    {
        return auth()->check() && auth()->user()->isNhanVien();
    }
}

if (!function_exists('can')) {
    function can($permission)
    {
        return auth()->check() && auth()->user()->hasPermission($permission);
    }
}

if (!function_exists('canAny')) {
    function canAny(...$permissions)
    {
        return auth()->check() && auth()->user()->hasAnyPermission($permissions);
    }
}

if (!function_exists('canAll')) {
    function canAll(...$permissions)
    {
        return auth()->check() && auth()->user()->hasAllPermissions($permissions);
    }
}