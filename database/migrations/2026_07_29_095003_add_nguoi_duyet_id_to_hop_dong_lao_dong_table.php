<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            // Kiểm tra cột đã tồn tại chưa
            if (!Schema::hasColumn('hop_dong_lao_dong', 'nguoi_duyet_id')) {
                $table->unsignedBigInteger('nguoi_duyet_id')->nullable()->after('trang_thai');
            }
        });

        // Thêm foreign key riêng để tránh lỗi
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (!Schema::hasColumn('hop_dong_lao_dong', 'nguoi_duyet_id')) {
                $table->foreign('nguoi_duyet_id')->references('id')->on('nguoi_dung')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (Schema::hasColumn('hop_dong_lao_dong', 'nguoi_duyet_id')) {
                $table->dropForeign(['nguoi_duyet_id']);
                $table->dropColumn('nguoi_duyet_id');
            }
        });
    }
};