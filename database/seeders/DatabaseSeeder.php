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
        $this->call(VaiTroSeeder::class);
        $this->call(LoaiNghiPhepSeeder::class);
        $this->call(KyNangSeeder::class);
        $this->call(PhuCapSeeder::class);
        $this->call(GioLamViecSeeder::class);
        
        $this->call(ChiNhanhCongTySeeder::class);
        $this->call(PhongBanSeeder::class);
        $this->call(ChucVuSeeder::class);
        
        $this->call(NguoiDungSeeder::class);
        $this->call(NguoiDungVaiTroSeeder::class); 
        $this->call(HoSoNguoiDungSeeder::class);
        
        $this->call(ThongBaoSeeder::class); 
        
        $this->call(HopDongLaoDongSeeder::class);
        $this->call(KyNangNhanVienSeeder::class);
        $this->call(PhuCapNhanVienSeeder::class);
        $this->call(SoDuNghiPhepNhanVienSeeder::class);
        
        $this->call(DonXinNghiSeeder::class);
        $this->call(LichSuDuyetDonNghiSeeder::class);
        $this->call(CongViecSeeder::class);
        $this->call(PhanCongCongViecSeeder::class);
        
        $this->call(ChamCongSeeder::class);
        $this->call(DangKyTangCaSeeder::class);
        $this->call(ThucHienTangCaSeeder::class);
        $this->call(YeuCauDieuChinhCongSeeder::class);
        $this->call(LichSuChamCongIpSeeder::class);
        
        $this->call(DiaChiIpDuocPhepSeeder::class);
        $this->call(ViTriCongTySeeder::class);
        
        $this->call(LuongSeeder::class);
        $this->call(BangLuongSeeder::class);
        $this->call(LuongNhanVienSeeder::class);
        $this->call(PhuCapLuongSeeder::class);
        $this->call(KhauTruLuongSeeder::class);
        
        $this->call(TinTuyenDungSeeder::class);
        $this->call(UngVienSeeder::class);
        $this->call(UngTuyenSeeder::class);
        $this->call(YeuCauTuyenDungSeeder::class);
        $this->call(LichSuDuyetYeuCauTuyenDungSeeder::class);
        
        $this->call(TaiLieuSeeder::class);
    
        $this->call(TinNhanSeeder::class);
    }
}
