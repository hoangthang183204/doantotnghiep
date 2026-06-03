<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dang_ky_tang_ca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->date('ngay_tang_ca');
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->decimal('so_gio_tang_ca', 5, 2);
            $table->enum('loai_tang_ca', ['thuong', 'cuoi_tuan', 'le'])->default('thuong');
            $table->text('ly_do_tang_ca');
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi', 'da_huy'])->default('cho_duyet');
            $table->foreignId('nguoi_duyet_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->timestamp('thoi_gian_duyet')->nullable();
            $table->text('ly_do_tu_choi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dang_ky_tang_ca');
    }
};