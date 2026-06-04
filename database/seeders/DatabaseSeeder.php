<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Bảng danh mục
        $this->call(VaiTroSeeder::class);
        $this->call(LoaiNghiPhepSeeder::class);
        $this->call(KyNangSeeder::class);
        $this->call(PhuCapSeeder::class);
        $this->call(ChiNhanhCongTySeeder::class);
        $this->call(PhongBanSeeder::class);
        $this->call(ChucVuSeeder::class);
        $this->call(GioLamViecSeeder::class);
        $this->call(CompanyLocationSeeder::class);
        
        // Bảng người dùng
        $this->call(NguoiDungSeeder::class);
        $this->call(HoSoNguoiDungSeeder::class);
        
        // Bảng chấm công và nghỉ phép
        $this->call(ChamCongSeeder::class);
        $this->call(DonXinNghiSeeder::class);
        
        // Bảng công việc
        $this->call(CongViecSeeder::class);
        $this->call(PhanCongCongViecSeeder::class);
        
        // Bảng tuyển dụng
        $this->call(TinTuyenDungSeeder::class);
        $this->call(UngVienSeeder::class);
    }
}
