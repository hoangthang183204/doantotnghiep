<?php

namespace App\Mail;

use App\Models\LuongNhanVien;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PhieuLuongMail extends Mailable
{
    use Queueable, SerializesModels;

    public $luong;

    public function __construct(LuongNhanVien $luong)
    {
        $this->luong = $luong;
    }

    public function build()
    {
        return $this->subject(
                'Phiếu lương tháng ' .
                $this->luong->luong_thang . '/' .
                $this->luong->luong_nam
            )
            ->view('emails.phieu-luong');
    }
}