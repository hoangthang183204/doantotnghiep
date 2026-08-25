<?php
// app/Exports/HopDongExport.php

namespace App\Exports;

use App\Models\HopDongLaoDong;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HopDongExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $hopDongs;

    public function __construct($hopDongs)
    {
        $this->hopDongs = $hopDongs;
    }

    /**
     * Lấy dữ liệu để xuất
     */
    public function collection()
    {
        return $this->hopDongs;
    }

    /**
     * Tiêu đề cột
     */
    public function headings(): array
    {
        return [
            'STT',
            'Số hợp đồng',
            'Nhân viên',
            'Mã nhân viên',
            'Chức vụ',
            'Phòng ban',
            'Loại hợp đồng',
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Lương cơ bản',
            'Trạng thái hợp đồng',
            'Trạng thái ký',
            'Ngày tạo'
        ];
    }

    /**
     * Ánh xạ dữ liệu từng dòng
     */
    public function map($hopDong): array
    {
        static $stt = 0;
        $stt++;

        // Lấy thông tin nhân viên
        $tenNhanVien = 'Chưa có';
        $maNhanVien = 'Chưa có';
        if ($hopDong->nguoiDung && $hopDong->nguoiDung->hoSo) {
            $tenNhanVien = $hopDong->nguoiDung->hoSo->ho . ' ' . $hopDong->nguoiDung->hoSo->ten;
            $maNhanVien = $hopDong->nguoiDung->hoSo->ma_nhan_vien ?? 'Chưa có';
        }

        // Lấy chức vụ
        $chucVu = $hopDong->chucVu ? $hopDong->chucVu->ten : 'Chưa có';

        // Lấy phòng ban
        $phongBan = 'Chưa có';
        if ($hopDong->nguoiDung && $hopDong->nguoiDung->phongBan) {
            $phongBan = $hopDong->nguoiDung->phongBan->ten_phong_ban;
        }

        // Map trạng thái
        $trangThaiHopDong = [
            'tao_moi' => 'Tạo mới',
            'chua_hieu_luc' => 'Chưa hiệu lực',
            'hieu_luc' => 'Hiệu lực',
            'het_han' => 'Hết hạn',
            'huy_bo' => 'Hủy bỏ'
        ];

        $trangThaiKy = [
            'cho_ky' => 'Chờ ký',
            'da_ky' => 'Đã ký',
            'tu_choi_ky' => 'Từ chối ký'
        ];

        $loaiHopDong = [
            'thu_viec' => 'Thử việc',
            'xac_dinh_thoi_han' => 'Xác định thời hạn',
            'khong_xac_dinh_thoi_han' => 'Không xác định thời hạn',
            'mua_vu' => 'Mùa vụ'
        ];

        return [
            $stt,
            $hopDong->so_hop_dong ?? '---',
            $tenNhanVien,
            $maNhanVien,
            $chucVu,
            $phongBan,
            $loaiHopDong[$hopDong->loai_hop_dong] ?? $hopDong->loai_hop_dong,
            $hopDong->ngay_bat_dau ? date('d/m/Y', strtotime($hopDong->ngay_bat_dau)) : '---',
            $hopDong->ngay_ket_thuc ? date('d/m/Y', strtotime($hopDong->ngay_ket_thuc)) : '---',
            number_format($hopDong->luong_co_ban, 0, ',', '.') . 'đ',
            $trangThaiHopDong[$hopDong->trang_thai_hop_dong] ?? $hopDong->trang_thai_hop_dong,
            $trangThaiKy[$hopDong->trang_thai_ky] ?? $hopDong->trang_thai_ky,
            $hopDong->created_at ? date('d/m/Y H:i', strtotime($hopDong->created_at)) : '---'
        ];
    }

    /**
     * Style cho bảng
     */
    public function styles(Worksheet $sheet)
    {
        // Style cho header
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E79']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Style cho toàn bảng
        $sheet->getStyle('A1:M' . ($this->collection()->count() + 1))
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'DDDDDD']
                    ]
                ]
            ]);

        // Style cho các dòng dữ liệu
        $sheet->getStyle('A2:M' . ($this->collection()->count() + 1))
            ->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

        // Căn giữa cho các cột STT, Số hợp đồng, Loại, Trạng thái
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K:K')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L:L')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Căn phải cho cột lương và STT
        $sheet->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Độ cao dòng
        $sheet->getRowDimension(1)->setRowHeight(25);
        for ($i = 2; $i <= $this->collection()->count() + 1; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }

        // Đóng băng dòng header
        $sheet->freezePane('A2');

        // Thêm filter
        $sheet->setAutoFilter('A1:M' . ($this->collection()->count() + 1));

        return [];
    }
}