<?php

namespace App\Notifications;

use App\Channels\FonnteChannel;
use App\Models\CalonSantri;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotifikasiStatusSantri extends Notification implements ShouldQueue
{
    use Queueable;
    protected CalonSantri $calonSantri;
    protected string $pdfUrl;

    public function __construct(CalonSantri $calonSantri, string $pdfUrl)
    {
        $this->calonSantri = $calonSantri;
        $this->pdfUrl = $pdfUrl;
    }

    public function via(object $notifiable): array
    {
        return [FonnteChannel::class];
    }

    /**
     * Get the Fonnte representation of the notification.
     * // -->> BUAT METHOD BARU INI <<--
     */
    public function toFonnte(object $notifiable): string
    {
        $status = $this->calonSantri->status_pendaftaran;
        $namaSantri = $this->calonSantri->nama_lengkap;
        $pesan = '';

        if ($status === 'lulus_seleksi') {
            $pesan = "Assalamualaikum Wr. Wb. Alhamdulillah, ananda *{$namaSantri}* dinyatakan LULUS SELEKSI sebagai calon santri baru di Pondok Pesantren Kun Karima. Informasi selanjutnya mengenai daftar ulang akan kami sampaikan kembali. Terima kasih.";
        } elseif ($status === 'belum_lulus') {
            $pesan = "Assalamualaikum Wr. Wb. Dengan berat hati kami sampaikan bahwa ananda *{$namaSantri}* dinyatakan BELUM LULUS SELEKSI sebagai calon santri baru di Pondok Pesantren Kun Karima. Semoga sukses di lain kesempatan. Terima kasih.";
        }

        return $pesan;
    }

    public function fonnteFileUrl($notifiable): ?string
    {
        return $this->pdfUrl ?? null;
    }
    
    public function toArray(object $notifiable): array
    {
        // Baris load() sudah tidak diperlukan di sini
        $status = $this->calonSantri->status_pendaftaran;
        $namaSantri = $this->calonSantri->nama_lengkap;
        $pesan = '';

        if ($status === 'lulus_seleksi') {
            $pesan = "Assalamualaikum Wr. Wb. Alhamdulillah, ananda {$namaSantri} dinyatakan LULUS SELEKSI sebagai calon santri baru. Informasi selanjutnya akan kami sampaikan kembali. Terima kasih.";
        } elseif ($status === 'belum_lulus') {
            $pesan = "Assalamualaikum Wr. Wb. Dengan berat hati kami sampaikan bahwa ananda {$namaSantri} dinyatakan BELUM LULUS SELEKSI sebagai calon santri baru. Semoga sukses di lain kesempatan. Terima kasih.";
        }

        if (!empty($pesan)) {
            Log::info("===== SIMULASI KIRIM WA =====");
            Log::info("KE: " . ($this->calonSantri->wali->nomor_whatsapp ?? 'NOMOR TIDAK ADA'));
            Log::info("PESAN: " . $pesan);
            Log::info("=============================");
        }

        return [
            'message' => $pesan,
            'santri_id' => $this->calonSantri->id,
            'url' => url($this->pdfUrl)
        ];
    }
}