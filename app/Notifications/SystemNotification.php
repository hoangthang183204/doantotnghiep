<?php
// app/Notifications/SystemNotification.php

namespace App\Notifications;

class SystemNotification extends BaseNotification
{
    public function __construct(
        string $title,
        string $message,
        array $extra = []
    ) {
        $data = array_merge([
            'title' => $title,
            'message' => $message,
            'icon' => 'info-circle',
            'color' => 'primary',
            'time' => now()->toISOString(),
        ], $extra);

        parent::__construct($data, 'system');
    }
}