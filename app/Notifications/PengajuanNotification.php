<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $tanggal;

    public function __construct($status, $tanggal)
    {
        $this->status = $status;
        $this->tanggal = $tanggal;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Ada pengajuan {$this->status} pada tanggal {$this->tanggal}",
            'status' => $this->status,
            'tanggal' => $this->tanggal,
        ];
    }
}