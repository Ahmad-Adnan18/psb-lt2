<?php

namespace App\Http\Controllers;

use App\Events\StatusSantriDiperbarui; // <-- Import Event
use App\Models\CalonSantri; // <-- Import model CalonSantri
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use Illuminate\Support\Facades\DB;   // <-- Tambahkan ini
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Str;         // <-- Tambahkan ini
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SantriExport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\DokumenSantri;
use App\Models\HasilTes;




class SantriController extends Controller
{
    use AuthorizesRequests;

    public function updateStatus(Request $request, CalonSantri $santri)
    {
        // 1. Otorisasi: Pastikan hanya admin yang bisa melakukan ini.
        if (auth()->user()->role !== 'admin') {
            abort(403, 'TINDAKAN INI TIDAK DIIZINKAN.');
        }

        // 2. Validasi input
        $validated = $request->validate([
            'status_pendaftaran' => ['required', Rule::in(['menunggu_verifikasi', 'lulus_seleksi', 'belum_lulus'])],
        ]);

        // 3. Update status di database
        try {
            $santri->update([
                'status_pendaftaran' => $validated['status_pendaftaran']
            ]);

            // Di sini nanti kita akan menambahkan trigger untuk notifikasi WhatsApp
            // Panggil event setelah status berhasil diupdate
            StatusSantriDiperbarui::dispatch($santri);

            return redirect()->route('santri.show', $santri->id)->with('success', 'Status pendaftaran berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal update status santri: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status.');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data santri, urutkan dari yang terbaru,
        // dan gunakan pagination.
        // Kita juga mengambil data relasi 'wali' untuk ditampilkan.
        $santri = CalonSantri::with('wali')->latest()->paginate(10);

        // Kirim data ke view
        return view('santri.index', compact('santri'));
    }

    public function create()
    {
        return view('santri.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (termasuk file-file baru)
        $validated = $request->validate([
            // Data Santri & Wali (sama seperti sebelumnya)
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20|unique:calon_santri,nisn',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'asal_sekolah' => 'required|string|max:100',
            'nama_wali' => 'required|string|max:255',
            'pekerjaan' => 'nullable|string|max:100',
            'nomor_whatsapp' => 'required|string|max:15',
            'alamat_wali' => 'nullable|string',

            // Validasi Dokumen sesuai rancangan
            'dokumen.akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen.kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen.foto_formal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen.raport' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // <-- BARU
            'dokumen.ktp_wali' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // <-- BARU
        ]);

        DB::beginTransaction();
        try {
            // 2. Generate Nomor Pendaftaran terlebih dahulu
            // Hitung tahun ajaran otomatis dari tahun sekarang
                $tahunSekarang = now()->format('y'); // Contoh: 25
                $tahunDepan = now()->addYear()->format('y'); // Contoh: 26
                $tahunAjaran = $tahunSekarang . $tahunDepan; // Hasil: 2526

                // Hitung jumlah santri dengan tahun ajaran ini
                $jumlahSantri = CalonSantri::where('nomor_pendaftaran', 'like', "KK-{$tahunAjaran}-%")->count() + 1;

                // Format nomor urut jadi 4 digit
                $urutan = str_pad($jumlahSantri, 4, '0', STR_PAD_LEFT);

                // Hasil akhir nomor pendaftaran
                $nomorPendaftaran = "LT2-{$tahunAjaran}-{$urutan}";


            // 3. Simpan data CalonSantri
            $calonSantri = CalonSantri::create([
                'nomor_pendaftaran' => $nomorPendaftaran, // <-- Gunakan nomor yang sudah di-generate
                'nama_lengkap' => $validated['nama_lengkap'],
                'nisn' => $validated['nisn'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat' => $validated['alamat'],
                'asal_sekolah' => $validated['asal_sekolah'],
                'status_pendaftaran' => 'menunggu_verifikasi', 
                'user_id' => Auth::id(),
            ]);

            // 4. Simpan data WaliSantri
            $calonSantri->wali()->create([
                'nama_wali' => $validated['nama_wali'],
                'pekerjaan' => $validated['pekerjaan'],
                'nomor_whatsapp' => $validated['nomor_whatsapp'],
                'alamat_wali' => $validated['alamat_wali'],
            ]);

            // 5. Proses dan Simpan Dokumen dengan path yang sesuai rancangan
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    $tahunAjaran = date('Y');
                    // PERBAIKAN: Menggunakan variabel $calonSantri yang benar
                    $pathWithYear = "PSB_{$tahunAjaran}/{$calonSantri->nomor_pendaftaran}";
                    $fileName = $key . '_' . time() . '.' . $file->extension();
                    $filePath = $file->storeAs($pathWithYear, $fileName, 'arsip_dokumen');

                    // Simpan info file ke database
                    $calonSantri->dokumen()->create([
                        'jenis_dokumen' => $key,
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $filePath,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('santri.index')->with('status', 'Data santri baru berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data santri: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function show(CalonSantri $calonSantri)
    {
        // Eager load relasi untuk efisiensi query
        $calonSantri->load('wali', 'dokumen', 'panitia');

        return view('santri.show', compact('calonSantri'));
    }

     public function edit(CalonSantri $calonSantri)
    {
        // Menggunakan Route Model Binding, Laravel otomatis mencari CalonSantri berdasarkan ID.
        // Kita juga perlu memuat relasi 'wali' agar datanya tersedia di view.
        $calonSantri->load('wali', 'dokumen');

        return view('santri.edit', compact('calonSantri'));
    }

    public function update(Request $request, CalonSantri $calonSantri)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            // Data Santri & Wali
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => ['nullable', 'string', 'max:20', Rule::unique('calon_santri')->ignore($calonSantri->id)],
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'asal_sekolah' => 'required|string|max:100',
            'nama_wali' => 'required|string|max:255',
            'pekerjaan' => 'nullable|string|max:100',
            'nomor_whatsapp' => 'required|string|max:15',
            'alamat_wali' => 'nullable|string',

            // Validasi Dokumen
            'dokumen.akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen.kartu_keluarga' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen.foto_formal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen.raport' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen.ktp_wali' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // 2. Update data CalonSantri
            $calonSantri->update($validated);

            // 3. Update data WaliSantri
            $calonSantri->wali()->updateOrCreate(
                ['calon_santri_id' => $calonSantri->id],
                $validated
            );

            // 4. Proses dan Simpan Dokumen jika ada file baru yang diupload
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    $tahunAjaran = date('Y');
                    // PERBAIKAN: Menggunakan variabel $calonSantri yang benar
                    $pathWithYear = "PSB_{$tahunAjaran}/{$calonSantri->nomor_pendaftaran}";
                    $fileName = $key . '_' . time() . '.' . $file->extension();

                    // Hapus file lama jika ada
                    $dokumenLama = $calonSantri->dokumen()->where('jenis_dokumen', $key)->first();
                    if ($dokumenLama) {
                        Storage::disk('arsip_dokumen')->delete($dokumenLama->path_file);
                    }

                    // Simpan file menggunakan disk 'arsip_dokumen'
                    $filePath = $file->storeAs($pathWithYear, $fileName, 'arsip_dokumen');
                    
                    // Simpan info file ke database
                    $calonSantri->dokumen()->updateOrCreate(
                        ['jenis_dokumen' => $key],
                        [
                            'nama_file' => $file->getClientOriginalName(),
                            'path_file' => $filePath
                        ]
                    );
                }
            }

            DB::commit();

            return redirect()->route('santri.index')->with('status', 'Data santri berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data santri: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * // -->> GANTI SELURUH ISI METHOD INI <<--
     */
    public function destroy(CalonSantri $calonSantri)
{
    Log::info('Masuk ke fungsi destroy untuk ID: ' . $calonSantri->id);

    if (auth()->user()->role !== 'admin') {
        return redirect()->route('santri.index')->with('error', 'Tidak diizinkan menghapus');
    }

    $directoryPath = null;
    if ($calonSantri->created_at) {
        $tahunAjaran = $calonSantri->created_at->format('Y');
        $directoryPath = "PSB_{$tahunAjaran}/{$calonSantri->nomor_pendaftaran}";
    }

    DB::beginTransaction();
    try {
        $calonSantri->dokumen()->delete();
        $calonSantri->wali()->delete();
        $calonSantri->delete();
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal menghapus data santri dari database: ' . $e->getMessage());
        return redirect()->route('santri.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
    }

    try {
        if ($directoryPath && Storage::disk('arsip_dokumen')->exists($directoryPath)) {
            Storage::disk('arsip_dokumen')->deleteDirectory($directoryPath);
        }
    } catch (\Exception $e) {
        Log::warning('Gagal menghapus folder: ' . $e->getMessage());
    }

    return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
}


    /**
     * Handle export to Excel
     */
     public function export()
    {
        // Menggunakan pengecekan langsung yang sudah terbukti berhasil
        if (auth()->user()->role !== 'admin') {
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        return Excel::download(new SantriExport, 'data-santri-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Handle the download request for a document.
     * // -->> TAMBAHKAN SELURUH METHOD INI <<--
     */
    public function downloadDokumen(DokumenSantri $dokumen)
    {
        // Pastikan file benar-benar ada di disk 'arsip_dokumen'
        if (!Storage::disk('arsip_dokumen')->exists($dokumen->path_file)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Ambil path lengkap ke file
        $path = Storage::disk('arsip_dokumen')->path($dokumen->path_file);

        // Kirim file sebagai respons download ke browser
        return response()->file($path);
    }

    public function simpanHasilTes(Request $request, CalonSantri $santri)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'nilai_alquran' => 'nullable|in:A,B,C,D',
            'nilai_arab' => 'nullable|in:A,B,C,D',
            'nilai_inggris' => 'nullable|in:A,B,C,D',
            'nilai_matematika' => 'nullable|in:A,B,C,D',
            'nilai_interview' => 'nullable|in:A,B,C,D',
            'catatan' => 'nullable|string',
            'nama_penguji' => 'required|string|max:100',
        ]);

        $existing = $santri->hasilTes;

            if ($existing) {
                $existing->update($validated);
            } else {
                $validated['calon_santri_id'] = $santri->id;
                HasilTes::create($validated);
            }


        // ✅ Setelah tersimpan, generate PDF
        $santri = $santri->fresh('hasilTes'); // refresh relasi biar data terbaru ke-load

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.hasil_tes', compact('santri'));

        $pdfPath = "hasil_tes/hasil-tes-{$santri->nomor_pendaftaran}.pdf";
        \Illuminate\Support\Facades\Storage::disk('public')->put($pdfPath, $pdf->output());

        return redirect()->route('santri.show', $santri->id)->with('success', 'Hasil tes dan PDF berhasil disimpan.');
    }

    public function downloadPDF(CalonSantri $santri)
    {
        $pdfPath = "hasil_tes/hasil-tes-{$santri->nomor_pendaftaran}.pdf";

        if (!Storage::disk('public')->exists($pdfPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(storage_path("app/public/{$pdfPath}"));
    }

}
