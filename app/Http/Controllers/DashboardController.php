<?php

namespace App\Http\Controllers;

use App\Models\CalonSantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Mengambil statistik pendaftaran dengan satu query untuk efisiensi
        $statusCounts = CalonSantri::query()
            ->select('status_pendaftaran', DB::raw('count(*) as total'))
            ->groupBy('status_pendaftaran')
            ->get()
            ->pluck('total', 'status_pendaftaran');

        // Menyiapkan data statistik untuk dikirim ke view
        $stats = [
            'total' => $statusCounts->sum(),
            'menunggu_verifikasi' => $statusCounts->get('menunggu_verifikasi', 0),
            'lulus_seleksi' => $statusCounts->get('lulus_seleksi', 0),
            'belum_lulus' => $statusCounts->get('belum_lulus', 0),
        ];

        // Mengambil 5 pendaftar terbaru
        $santriTerbaru = CalonSantri::with('wali')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'santriTerbaru'));
    }
}
