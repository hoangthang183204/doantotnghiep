<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cham_cong', function (Blueprint $table) {
            // Kiểm tra tránh lỗi nếu chạy lại migration
            if (!Schema::hasColumn('cham_cong', 'co_tang_ca')) {
                $table->boolean('co_tang_ca')->default(false)->after('so_cong');
            }
            
            if (!Schema::hasColumn('cham_cong', 'tang_ca_id')) {
                $table->unsignedBigInteger('tang_ca_id')->nullable()->after('co_tang_ca');
            }
            
            if (!Schema::hasColumn('cham_cong', 'so_gio_ca_chinh')) {
                $table->decimal('so_gio_ca_chinh', 5, 2)->default(0)->after('tang_ca_id');
            }
            
            if (!Schema::hasColumn('cham_cong', 'so_gio_tang_ca')) {
                $table->decimal('so_gio_tang_ca', 5, 2)->default(0)->after('so_gio_ca_chinh');
            }
            
            if (!Schema::hasColumn('cham_cong', 'so_gio_nghi_trua')) {
                $table->decimal('so_gio_nghi_trua', 5, 2)->default(0)->after('so_gio_tang_ca');
            }
            
            // Thêm foreign key nếu bảng dang_ky_tang_ca tồn tại
            if (Schema::hasTable('dang_ky_tang_ca')) {
                $table->foreign('tang_ca_id')->references('id')->on('dang_ky_tang_ca')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('cham_cong', function (Blueprint $table) {
            $table->dropForeign(['tang_ca_id']);
            $table->dropColumn([
                'co_tang_ca',
                'tang_ca_id',
                'so_gio_ca_chinh',
                'so_gio_tang_ca',
                'so_gio_nghi_trua'
            ]);
        });
    }
};