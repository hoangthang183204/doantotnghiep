<?php

namespace App\Mail;

use App\Models\HopDongLaoDong;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HopDongDaKyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hopDong;
    public $nhanVien;
    public $hoSo;

    public function __construct(HopDongLaoDong $hopDong)
    {
        $this->hopDong = $hopDong;
        $this->nhanVien = $hopDong->nguoiDung;
        $this->hoSo = $hopDong->nguoiDung->hoSo ?? null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Xác nhận hợp đồng đã ký - ' . $this->hopDong->so_hop_dong,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.hop-dong-da-ky',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        if ($this->hopDong->file_hop_dong_da_ky) {
            $files = explode(';', $this->hopDong->file_hop_dong_da_ky);
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . trim($file));
                if (file_exists($filePath)) {
                    $attachments[] = $filePath;
                }
            }
        }
        return $attachments;
    }
}