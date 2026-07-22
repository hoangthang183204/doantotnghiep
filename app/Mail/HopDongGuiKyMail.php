<?php
// app/Mail/HopDongGuiKyMail.php

namespace App\Mail;

use App\Models\HopDongLaoDong;
use App\Models\NguoiDung;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HopDongGuiKyMail extends Mailable
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
            subject: '📄 Hợp đồng lao động cần ký - ' . $this->hopDong->so_hop_dong,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.hop-dong-gui-ky',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        // Đính kèm file hợp đồng gốc
        if ($this->hopDong->duong_dan_file) {
            $files = explode(';', $this->hopDong->duong_dan_file);
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