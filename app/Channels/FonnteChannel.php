<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteChannel
{
    public function send(mixed $notifiable, Notification $notification): void
    {
        // Ambil isi pesan dari notifikasi
        $message = $notification->toFonnte($notifiable);
        if (!$message) return;

        // Ambil nomor tujuan
        $phoneNumber = $notifiable->routeNotificationFor('fonnte');
        if (!$phoneNumber) return;

        // Buat payload dasar
        $payload = [
            'target' => $phoneNumber,
            'message' => $message,
        ];

        // (Opsional) tambahkan URL file jika notifikasi punya file
        /*if (method_exists($notification, 'fonnteFileUrl')) {
            $fileUrl = $notification->fonnteFileUrl($notifiable);
            if (!empty($fileUrl)) {
                $payload['url'] = $fileUrl;
            }
        }*/

        // Kirim ke Fonnte
        $response = Http::withHeaders([
            'Authorization' => config('services.fonnte.token'),
        ])->post(config('services.fonnte.url'), $payload);

        // Log jika gagal
        if ($response->failed()) {
            Log::error('Gagal mengirim notifikasi Fonnte', [
                'target' => $phoneNumber,
                'payload' => $payload,
                'response' => $response->body(),
            ]);
        }
    }
}
