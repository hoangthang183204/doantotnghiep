<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            if (!Schema::hasColumn('dang_ky_tang_ca', 'don_tang_ca_id')) {
                $table->unsignedBigInteger('don_tang_ca_id')->nullable()->after('trang_thai');
                // Không thêm foreign key nếu chưa có bảng tham chiếu
            }
        });
    }

    public function down()
    {
        Schema::table('dang_ky_tang_ca', function (Blueprint $table) {
            if (Schema::hasColumn('dang_ky_tang_ca', 'don_tang_ca_id')) {
                $table->dropColumn('don_tang_ca_id');
            }
        });
    }
};