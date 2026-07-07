<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sửa ENUM để thêm 'tang_ca'
        DB::statement("ALTER TABLE cham_cong MODIFY COLUMN trang_thai ENUM('dung_gio', 'di_muon', 've_som', 'khong_cham_cong', 'nghi_phep', 'vang_mat', 'den_som', 'tang_ca') DEFAULT 'dung_gio'");
    }

    public function down(): void
    {
        // Quay lại ENUM cũ (không có tang_ca)
        DB::statement("ALTER TABLE cham_cong MODIFY COLUMN trang_thai ENUM('dung_gio', 'di_muon', 've_som', 'khong_cham_cong', 'nghi_phep', 'vang_mat', 'den_som') DEFAULT 'dung_gio'");
    }
};