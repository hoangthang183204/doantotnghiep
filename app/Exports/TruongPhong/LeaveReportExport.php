<?php
// app/Exports/TruongPhong/LeaveReportExport.php

namespace App\Exports\TruongPhong;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class LeaveReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $phongBan;
    protected $thang;
    protected $nam;

    public function __construct($data, $phongBan, $thang, $nam)
    {
        $this->data = $data;
        $this->phongBan = $phongBan;
        $this->thang = $thang;
        $this->nam = $nam;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã đơn',
            'Nhân viên',
            'Loại nghỉ',
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Số ngày',
            'Trạng thái',
        ];
    }

    public function map($don): array
    {
        static $index = 0;
        $index++;
        return [
            $index,
            $don->ma_don_nghi,
            ($don->nguoiDung->hoSo->ho ?? '') . ' ' . ($don->nguoiDung->hoSo->ten ?? ''),
            $don->loaiNghiPhep->ten ?? 'N/A',
            Carbon::parse($don->ngay_bat_dau)->format('d/m/Y'),
            Carbon::parse($don->ngay_ket_thuc)->format('d/m/Y'),
            $don->so_ngay_nghi,
            $don->trang_thai == 'cho_duyet' ? 'Chờ duyệt' : ($don->trang_thai == 'da_duyet' ? 'Đã duyệt' : 'Từ chối'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E79'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border
        $sheet->getStyle('A1:H' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D0D0D0'],
                ],
            ],
        ]);

        // Chiều cao header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Đóng băng dòng header
        $sheet->freezePane('A2');

        // Thêm tiêu đề báo cáo
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'BÁO CÁO NGHỈ PHÉP');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Phòng: ' . $this->phongBan->ten_phong_ban . ' | Tháng: ' . $this->thang . '/' . $this->nam);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 12],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        return $sheet;
    }
}