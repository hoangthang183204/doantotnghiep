<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            if (!Schema::hasColumn('dang_ky_tang_ca', 'noi_dung_cong_viec')) {
                $table->text('noi_dung_cong_viec')->nullable()->after('ly_do_tang_ca');
            }
            if (!Schema::hasColumn('dang_ky_tang_ca', 'ghi_chu')) {
                $table->text('ghi_chu')->nullable()->after('noi_dung_cong_viec');
            }
        });
    }

    public function down()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            $table->dropColumn(['noi_dung_cong_viec', 'ghi_chu']);
        });
    }
};