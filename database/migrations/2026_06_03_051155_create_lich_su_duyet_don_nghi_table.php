<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_su_duyet_don_nghi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('don_xin_nghi_id')->constrained('don_xin_nghi')->cascadeOnDelete();
            $table->tinyInteger('cap_duyet');
            $table->foreignId('nguoi_duyet_id')->constrained('nguoi_dung');
            $table->enum('ket_qua', ['cho_duyet', 'da_duyet', 'tu_choi'])->default('da_duyet');
            $table->text('ghi_chu')->nullable();
            $table->timestamp('thoi_gian_duyet')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_duyet_don_nghi');
    }
};