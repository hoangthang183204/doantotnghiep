<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Helpers\PermissionHelper;

class BladePermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // @canPermission('permission_name')
        Blade::directive('canPermission', function ($expression) {
            return "<?php if(\\App\\Helpers\\PermissionHelper::can({$expression})): ?>";
        });

        Blade::directive('endcanPermission', function () {
            return "<?php endif; ?>";
        });

        // @cannotPermission('permission_name')
        Blade::directive('cannotPermission', function ($expression) {
            return "<?php if(!\\App\\Helpers\\PermissionHelper::can({$expression})): ?>";
        });

        Blade::directive('endcannotPermission', function () {
            return "<?php endif; ?>";
        });

        // @canAnyPermission(['perm1', 'perm2'])
        Blade::directive('canAnyPermission', function ($expression) {
            return "<?php if(\\App\\Helpers\\PermissionHelper::canAny({$expression})): ?>";
        });

        Blade::directive('endcanAnyPermission', function () {
            return "<?php endif; ?>";
        });

        // @isAdmin
        Blade::directive('isAdmin', function () {
            return "<?php if(\\App\\Helpers\\PermissionHelper::isAdmin()): ?>";
        });

        Blade::directive('endisAdmin', function () {
            return "<?php endif; ?>";
        });

        // @isHR
        Blade::directive('isHR', function () {
            return "<?php if(\\App\\Helpers\\PermissionHelper::isHR()): ?>";
        });

        Blade::directive('endisHR', function () {
            return "<?php endif; ?>";
        });

        // @isTruongPhong
        Blade::directive('isTruongPhong', function () {
            return "<?php if(\\App\\Helpers\\PermissionHelper::isTruongPhong()): ?>";
        });

        Blade::directive('endisTruongPhong', function () {
            return "<?php endif; ?>";
        });

        // @isNhanVien
        Blade::directive('isNhanVien', function () {
            return "<?php if(\\App\\Helpers\\PermissionHelper::isNhanVien()): ?>";
        });

        Blade::directive('endisNhanVien', function () {
            return "<?php endif; ?>";
        });
    }

    public function register()
    {
        //
    }
}