<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TinTuyenDungMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ungVien;
    public $tinTuyenDung;
    public $tieuDe;
    public $noiDung;

    /**
     * Create a new message instance.
     */
    public function __construct($ungVien, $tinTuyenDung, $tieuDe, $noiDung)
    {
        $this->ungVien = $ungVien;
        $this->tinTuyenDung = $tinTuyenDung;
        $this->tieuDe = $tieuDe;
        $this->noiDung = $noiDung;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->tieuDe . ' - Thông báo từ HRFlow',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tin-tuyen-dung-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}