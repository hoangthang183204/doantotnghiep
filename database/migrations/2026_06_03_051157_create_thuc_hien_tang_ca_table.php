<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thuc_hien_tang_ca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dang_ky_tang_ca_id')->constrained('dang_ky_tang_ca')->cascadeOnDelete();
            $table->time('gio_bat_dau_thuc_te')->nullable();
            $table->time('gio_ket_thuc_thuc_te')->nullable();
            $table->decimal('so_gio_tang_ca_thuc_te', 5, 2)->default(0);
            $table->text('cong_viec_da_thuc_hien')->nullable();
            $table->decimal('so_cong_tang_ca', 5, 2)->default(0);
            $table->enum('trang_thai', ['chua_thuc_hien', 'dang_thuc_hien', 'da_hoan_thanh'])->default('chua_thuc_hien');
            $table->string('vi_tri_check_in')->nullable();
            $table->string('vi_tri_check_out')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thuc_hien_tang_ca');
    }
};