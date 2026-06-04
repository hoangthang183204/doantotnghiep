<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoi_dung_vai_tro', function (Blueprint $table) {
            $table->foreignId('vai_tro_id')->constrained('vai_tro')->cascadeOnDelete();
            $table->string('model_type')->default('App\\Models\\NguoiDung');
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->timestamps();
            
            $table->primary(['vai_tro_id', 'nguoi_dung_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung_vai_tro');
    }
};