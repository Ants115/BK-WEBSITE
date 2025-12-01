<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusKonsultasiNotification extends Notification
{
    use Queueable;

    public $konsultasi;
    public $pesan;

    /**
     * Create a new notification instance.
     */
    public function __construct($konsultasi, $pesan)
    {
        $this->konsultasi = $konsultasi;
        $this->pesan = $pesan;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Kita simpan ke database agar bisa tampil di lonceng
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'konsultasi_id' => $this->konsultasi->id,
            'status' => $this->konsultasi->status,
            'pesan' => $this->pesan,
            'guru_nama' => $this->konsultasi->guru->name,
        ];
    }
}