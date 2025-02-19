<?php

namespace App\Notifications;

use App\Models\Transaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class KonfirmasiTransaksi extends Notification
{
    use Queueable;
    protected $transaksi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Konfirmasi Transaksi Besar')
            ->line("Anda telah membuat transaksi sebesar Rp" . number_format($this->transaksi->jumlah, 0, ',', '.') . " pada tanggal " . $this->transaksi->tanggal)
            ->line("Silakan konfirmasi transaksi ini untuk melanjutkan.")
            ->action('Konfirmasi Sekarang', url('/transaksi/konfirmasi/' . $this->transaksi->id))
            ->line('Jika transaksi ini bukan milik Anda, harap segera hubungi admin.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "Transaksi sebesar Rp" . number_format($this->transaksi->jumlah, 0, ',', '.') . " perlu dikonfirmasi.",
            'transaksi_id' => $this->transaksi->id,
        ];
    }
}
