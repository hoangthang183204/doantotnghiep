<?php
// database/migrations/xxxx_xx_xx_add_thieu_cham_cong_ra_to_dang_ky_tang_ca.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            $table->boolean('thieu_cham_cong_ra')->default(false)->after('da_checkout_thay_the');
            $table->timestamp('thoi_gian_checkout_du_kien')->nullable()->after('thieu_cham_cong_ra');
        });
    }

    public function down()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            $table->dropColumn(['thieu_cham_cong_ra', 'thoi_gian_checkout_du_kien']);
        });
    }
};