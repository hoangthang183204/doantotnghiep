<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE cham_cong MODIFY trang_thai ENUM('dung_gio', 'di_muon', 've_som', 'khong_cham_cong', 'nghi_phep', 'vang_mat', 'den_som') DEFAULT 'dung_gio'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE cham_cong MODIFY trang_thai ENUM('dung_gio', 'di_muon', 've_som', 'khong_cham_cong', 'nghi_phep', 'vang_mat') DEFAULT 'dung_gio'");
    }
};