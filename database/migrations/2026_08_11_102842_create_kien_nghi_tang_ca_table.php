<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kien_nghi_tang_ca', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nguoi_dung_id');
            $table->date('ngay_de_nghi');
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->float('so_gio');
            $table->string('loai_tang_ca')->default('ngay_thuong'); // ngay_thuong, ngay_nghi
            $table->text('ly_do');
            $table->text('noi_dung_cong_viec')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->string('trang_thai')->default('cho_xu_ly'); // cho_xu_ly, da_dong_y, tu_choi
            $table->unsignedBigInteger('nguoi_xu_ly_id')->nullable(); // trưởng phòng xử lý
            $table->text('ly_do_tu_choi')->nullable();
            $table->timestamp('thoi_gian_xu_ly')->nullable();
            $table->unsignedBigInteger('don_tang_ca_id')->nullable(); // Liên kết với đơn tăng ca
            $table->timestamps();
            
            $table->foreign('nguoi_dung_id')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('nguoi_xu_ly_id')->references('id')->on('nguoi_dung')->onDelete('set null');
            $table->foreign('don_tang_ca_id')->references('id')->on('dang_ky_tang_ca')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kien_nghi_tang_ca');
    }
};