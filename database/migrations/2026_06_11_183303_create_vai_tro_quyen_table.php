<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vai_tro_quyen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vai_tro_id')->constrained('vai_tro')->onDelete('cascade');
            $table->foreignId('quyen_id')->constrained('quyen')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['vai_tro_id', 'quyen_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vai_tro_quyen');
    }
};