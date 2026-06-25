<?php
// app/Notifications/BaseNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $data;
    protected string $type;

    public function __construct(array $data, string $type = 'info')
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
        ];
    }

    public function toArray($notifiable): array
    {
        return $this->data;
    }
}