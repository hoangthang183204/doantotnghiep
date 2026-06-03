<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tin_nhan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_gui_id')->constrained('nguoi_dung');
            $table->foreignId('nguoi_nhan_id')->constrained('nguoi_dung');
            $table->enum('loai_tin_nhan', ['text', 'file', 'system'])->default('text');
            $table->text('noi_dung');
            $table->string('duong_dan_file')->nullable();
            $table->tinyInteger('da_doc')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tin_nhan');
    }
};