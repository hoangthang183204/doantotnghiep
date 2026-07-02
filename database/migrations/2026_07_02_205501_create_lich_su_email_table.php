<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lich_su_email', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ung_vien_id')->constrained('ung_vien')->onDelete('cascade');
            $table->foreignId('tin_tuyen_dung_id')->constrained('tin_tuyen_dung')->onDelete('cascade');
            $table->foreignId('nguoi_gui_id')->constrained('nguoi_dung')->onDelete('cascade');
            $table->string('tieu_de');
            $table->text('noi_dung');
            $table->string('trang_thai')->default('da_gui'); // da_gui, da_xem
            $table->timestamp('thoi_gian_gui')->nullable();
            $table->timestamp('thoi_gian_xem')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lich_su_email');
    }
};