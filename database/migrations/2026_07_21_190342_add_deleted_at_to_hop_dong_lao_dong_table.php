<?php
// database/migrations/2026_07_21_000001_add_deleted_at_to_hop_dong_lao_dong.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (!Schema::hasColumn('hop_dong_lao_dong', 'deleted_at')) {
                $table->timestamp('deleted_at')->nullable()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            if (Schema::hasColumn('hop_dong_lao_dong', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
        });
    }
};