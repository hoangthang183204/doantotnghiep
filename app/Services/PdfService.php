<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;

/**
 * Trình bao (wrapper) mỏng quanh dompdf để xuất PDF từ view Blade.
 * Dùng font DejaVu Sans để hiển thị tiếng Việt có dấu.
 */
class PdfService
{
    /**
     * Render 1 view Blade thành PDF và trả về response tải xuống.
     *
     * @param string $view       tên view blade
     * @param array  $data       dữ liệu truyền vào view
     * @param string $fileName   tên file .pdf
     * @param string $orientation 'portrait' | 'landscape'
     */
    public function download(string $view, array $data, string $fileName, string $orientation = 'portrait'): Response
    {
        $pdf = $this->render($view, $data, $orientation);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /** Trả về PDF hiển thị ngay trên trình duyệt (inline) */
    public function stream(string $view, array $data, string $fileName, string $orientation = 'portrait'): Response
    {
        $pdf = $this->render($view, $data, $orientation);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    /** Sinh nội dung PDF (chuỗi nhị phân) */
    public function render(string $view, array $data, string $orientation = 'portrait'): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view($view, $data)->render(), 'UTF-8');
        $dompdf->setPaper('A4', $orientation);
        $dompdf->render();

        return $dompdf->output();
    }
}
