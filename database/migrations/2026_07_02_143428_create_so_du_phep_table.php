<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('so_du_phep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->onDelete('cascade');
            $table->integer('nam'); 
            $table->decimal('phep_nam_moi', 4, 1)->default(12.0); 
            $table->decimal('phep_cu_chuyen_sang', 4, 1)->default(0.0); 
            $table->decimal('phep_da_dung', 4, 1)->default(0.0); 
            $table->timestamps();
    
            $table->unique(['nguoi_dung_id', 'nam']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_du_phep');
    }
};
