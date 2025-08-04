<?php

namespace App\Listeners;

use App\Events\StatusSantriDiperbarui;
use App\Notifications\NotifikasiStatusSantri;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class KirimNotifikasiWhatsApp implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(StatusSantriDiperbarui $event): void
    {
        $santri = $event->calonSantri;
        $santri->load('wali', 'hasilTes');

        // Kirim notifikasi hanya jika statusnya lulus atau belum lulus
        if (in_array($santri->status_pendaftaran, ['lulus_seleksi', 'belum_lulus'])) {

            // 1. Generate PDF
            $pdf = Pdf::loadView('pdf.hasil_tes', ['santri' => $santri]);
            $pdfPath = "hasil_tes/hasil-tes-{$santri->nomor_pendaftaran}.pdf";

            // 2. Simpan ke storage
            Storage::disk('public')->put($pdfPath, $pdf->output());

            // 3. Buat URL file PDF
            $pdfUrl = asset("storage/{$pdfPath}");

            // 4. Kirim notifikasi via WhatsApp
            $santri->notify(new NotifikasiStatusSantri($santri, $pdfUrl));
        }
    }
}
