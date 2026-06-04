<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yeu_cau_dieu_chinh_cong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->date('ngay');
            $table->time('gio_vao')->nullable();
            $table->time('gio_ra')->nullable();
            $table->text('ly_do');
            $table->string('tep_dinh_kem')->nullable();
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi'])->default('cho_duyet');
            $table->foreignId('duyet_boi')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->datetime('duyet_vao')->nullable();
            $table->text('ghi_chu_duyet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yeu_cau_dieu_chinh_cong');
    }
};