<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kiểm tra bảng đã tồn tại chưa
        if (!Schema::hasTable('don_xin_ve_som')) {
            Schema::create('don_xin_ve_som', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nguoi_dung_id');
                $table->unsignedBigInteger('cham_cong_id')->nullable();
                $table->date('ngay');
                $table->time('gio_ra_du_kien');
                $table->integer('so_phut_ve_som');
                $table->text('ly_do');
                $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi'])->default('cho_duyet');
                $table->text('ly_do_tu_choi')->nullable();
                $table->unsignedBigInteger('nguoi_duyet_id')->nullable();
                $table->timestamp('thoi_gian_duyet')->nullable();
                $table->timestamps();
            });
        }

        // Thêm indexes sau khi tạo bảng
        try {
            Schema::table('don_xin_ve_som', function (Blueprint $table) {
                // Kiểm tra nếu index chưa tồn tại
                $table->index('nguoi_dung_id');
                $table->index('cham_cong_id');
                $table->index('trang_thai');
                $table->index(['nguoi_dung_id', 'ngay']);
            });
        } catch (\Exception $e) {
            // Index đã tồn tại
        }
    }

    public function down()
    {
        Schema::dropIfExists('don_xin_ve_som');
    }
};