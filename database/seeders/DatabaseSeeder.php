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

        // Người dùng
        $this->call(NguoiDungSeeder::class);
        $this->call(HoSoNguoiDungSeeder::class);
        $this->call(PhuCapNhanVienSeeder::class);

        // CV
        $this->call(TaiLieuSeeder::class);

        // Hợp đồng
        $this->call(HopDongLaoDongSeeder::class);

        // Chấm công
        $this->call(ChamCongSeeder::class);
        $this->call(DonXinNghiSeeder::class);

        // Tăng ca
        $this->call([DangKyTangCaSeeder::class,]);
        $this->call([ThucHienTangCaSeeder::class,]);

        // Công việc
        $this->call(CongViecSeeder::class);
        $this->call(PhanCongCongViecSeeder::class);

        // Tuyển dụng
        $this->call(TinTuyenDungSeeder::class);
        $this->call(UngVienSeeder::class);

        // Lương
        $this->call(LuongSeeder::class);        // module "Quản lý lương" (bảng luong theo hợp đồng)
        $this->call(LuongDemoSeeder::class);    // bảng lương tháng - tính bằng engine TinhLuongService

        $this->call(QuyenSeeder::class);
    }
}
