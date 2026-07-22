<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cham_cong', function (Blueprint $table) {
            if (!Schema::hasColumn('cham_cong', 'ca_lam_viec_id')) {
                $table->unsignedBigInteger('ca_lam_viec_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('cham_cong', function (Blueprint $table) {
            if (Schema::hasColumn('cham_cong', 'ca_lam_viec_id')) {
                $table->dropColumn('ca_lam_viec_id');
            }
        });
    }
};