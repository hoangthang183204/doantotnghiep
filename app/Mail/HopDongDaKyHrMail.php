<?php
// app/Mail/HopDongDaKyHrMail.php

namespace App\Mail;

use App\Models\HopDongLaoDong;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HopDongDaKyHrMail extends Mailable
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
            subject: '✅ Nhân viên đã ký hợp đồng - ' . $this->hopDong->so_hop_dong,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.hop-dong-da-ky-hr',
        );
    }
}