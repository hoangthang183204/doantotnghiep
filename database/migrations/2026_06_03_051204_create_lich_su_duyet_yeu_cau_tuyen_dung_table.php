<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_su_duyet_yeu_cau_tuyen_dung', function (Blueprint $table) {
            $table->id();
            $table->foreignId('yeu_cau_id')->constrained('yeu_cau_tuyen_dung')->cascadeOnDelete();
            $table->foreignId('nguoi_duyet_id')->constrained('nguoi_dung');
            $table->enum('hanh_dong', ['duyet', 'tu_choi', 'yeu_cau_sua']);
            $table->text('ghi_chu')->nullable();
            $table->timestamp('thoi_gian')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_duyet_yeu_cau_tuyen_dung');
    }
};