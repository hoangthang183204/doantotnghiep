<?php
// app/Http/Controllers/Admin/HopDongPdfController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HopDongLaoDong;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class HopDongPdfController extends Controller
{
    /**
     * Tạo file PDF hợp đồng
     */
    public function generate($id)
    {
        $hopDong = HopDongLaoDong::with([
            'hoSoNguoiDung',
            'nguoiDung.phongBan',
            'chucVu',
            'nguoiDung'
        ])->findOrFail($id);

        $data = $this->prepareData($hopDong);
        
        $pdf = Pdf::loadView('admin.hop-dong-lao-dong.pdf_template', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Lưu file PDF
        $fileName = 'hop_dong_' . $hopDong->so_hop_dong . '_' . date('Ymd_His') . '.pdf';
        $path = 'hop_dong/' . $fileName;
        
        Storage::disk('public')->put($path, $pdf->output());
        
        // Cập nhật đường dẫn file vào database
        $hopDong->duong_dan_file = $path;
        $hopDong->save();
        
        return $pdf->download($fileName);
    }

    /**
     * Tải file PDF hợp đồng
     */
    public function download($id)
    {
        $hopDong = HopDongLaoDong::with([
            'hoSoNguoiDung',
            'nguoiDung.phongBan',
            'chucVu'
        ])->findOrFail($id);

        $data = $this->prepareData($hopDong);
        
        $pdf = Pdf::loadView('admin.hop-dong-lao-dong.pdf_template', $data);
        $pdf->setPaper('a4', 'portrait');
        
        $fileName = 'hop_dong_' . $hopDong->so_hop_dong . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Xem trước PDF hợp đồng
     */
    public function preview($id)
    {
        $hopDong = HopDongLaoDong::with([
            'hoSoNguoiDung',
            'nguoiDung.phongBan',
            'chucVu'
        ])->findOrFail($id);

        $data = $this->prepareData($hopDong);
        
        $pdf = Pdf::loadView('admin.hop-dong-lao-dong.pdf_template', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('hop_dong_' . $hopDong->so_hop_dong . '.pdf');
    }

    /**
     * Chuẩn bị dữ liệu cho PDF
     */
    private function prepareData($hopDong)
    {
        $hoSo = $hopDong->hoSoNguoiDung;
        $ngayBatDau = Carbon::parse($hopDong->ngay_bat_dau);
        $ngayKetThuc = $hopDong->ngay_ket_thuc ? Carbon::parse($hopDong->ngay_ket_thuc) : null;
        
        // Lấy thông tin công ty từ config hoặc database
        $company = [
            'ten' => 'Công ty TNHH Công nghệ HR Flow Việt Nam',
            'dia_chi' => 'Tầng 8, Tòa CT1, Khu đô thị Nam Cường, Bắc Từ Liêm, Hà Nội',
            'dien_thoai' => '024 3765 8899',
            'ma_so_thue' => '0109876543',
            'tai_khoan' => '1903688888888',
            'nguoi_dai_dien' => 'Nguyễn Văn Minh',
            'chuc_vu_dai_dien' => 'Giám đốc',
        ];

        // Xác định loại hợp đồng
        $loaiHopDongText = [
            'thu_viec' => 'Thử việc',
            'xac_dinh_thoi_han' => 'Xác định thời hạn',
            'khong_xac_dinh_thoi_han' => 'Không xác định thời hạn',
            'mua_vu' => 'Mùa vụ',
        ][$hopDong->loai_hop_dong] ?? $hopDong->loai_hop_dong;

        // Tính thời hạn hợp đồng
        $thoiHan = '';
        if ($hopDong->loai_hop_dong == 'khong_xac_dinh_thoi_han') {
            $thoiHan = 'Không xác định thời hạn';
        } elseif ($hopDong->loai_hop_dong == 'xac_dinh_thoi_han' && $ngayKetThuc) {
            $months = $ngayBatDau->diffInMonths($ngayKetThuc);
            $thoiHan = $months . ' tháng (từ ' . $ngayBatDau->format('d/m/Y') . ' đến ' . $ngayKetThuc->format('d/m/Y') . ')';
        } else {
            $thoiHan = $ngayBatDau->format('d/m/Y') . ' - ' . ($ngayKetThuc ? $ngayKetThuc->format('d/m/Y') : '...');
        }

        // Định dạng số tiền
        $luongCoBan = number_format($hopDong->luong_co_ban, 0, ',', '.') . ' VNĐ';

        // Lấy phụ cấp
        $phuCapText = 'Không có';
        $phuCapDisplay = 0;
        
        if ($hopDong->nguoiDung) {
            $phuCapNhanViens = $hopDong->nguoiDung->phuCapNhanViens ?? collect();
            if ($phuCapNhanViens->count() > 0) {
                $phuCapItems = [];
                foreach ($phuCapNhanViens as $pc) {
                    $phuCapDisplay += $pc->so_tien;
                    $phuCapItems[] = ($pc->phuCap->ten ?? 'Phụ cấp') . ': ' . number_format($pc->so_tien, 0, ',', '.') . ' VNĐ';
                }
                $phuCapText = implode('; ', $phuCapItems);
            }
        }

        // Nếu không có phụ cấp từ bảng phu_cap_nhan_vien, lấy từ hợp đồng
        if ($phuCapDisplay == 0 && !empty($hopDong->phu_cap)) {
            $phuCapValue = $hopDong->phu_cap;
            if (is_array($phuCapValue) && count($phuCapValue) > 0) {
                $phuCaps = \App\Models\PhuCap::whereIn('id', $phuCapValue)->get();
                $phuCapItems = [];
                foreach ($phuCaps as $pc) {
                    $phuCapDisplay += $pc->so_tien_mac_dinh;
                    $phuCapItems[] = $pc->ten . ': ' . number_format($pc->so_tien_mac_dinh, 0, ',', '.') . ' VNĐ';
                }
                if (count($phuCapItems) > 0) {
                    $phuCapText = implode('; ', $phuCapItems);
                }
            }
        }

        return [
            'hopDong' => $hopDong,
            'hoSo' => $hoSo,
            'company' => $company,
            'ngayBatDau' => $ngayBatDau,
            'ngayKetThuc' => $ngayKetThuc,
            'loaiHopDongText' => $loaiHopDongText,
            'thoiHan' => $thoiHan,
            'luongCoBan' => $luongCoBan,
            'phuCapText' => $phuCapText,
            'phuCapDisplay' => $phuCapDisplay,
            'ngayHienTai' => Carbon::now()->format('d/m/Y'),
            'diaChiLamViec' => $hopDong->dia_diem_lam_viec ?? $company['dia_chi'],
            'tenPhongBan' => $hopDong->nguoiDung && $hopDong->nguoiDung->phongBan ? $hopDong->nguoiDung->phongBan->ten_phong_ban : 'N/A',
            'tenChucVu' => $hopDong->chucVu ? $hopDong->chucVu->ten : 'N/A',
        ];
    }
}