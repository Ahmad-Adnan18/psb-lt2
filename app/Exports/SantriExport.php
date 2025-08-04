<?php
// FILE: app/Exports/SantriExport.php
// Buka file ini dan GANTI SELURUH ISINYA dengan kode di bawah ini.


namespace App\Exports;

use App\Models\CalonSantri;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Str;

class SantriExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua data yang dibutuhkan dengan eager loading
        return CalonSantri::with(['wali', 'dokumen', 'panitia'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Ini adalah header untuk kolom Excel
        return [
            'Nomor Pendaftaran', 'Nama Lengkap', 'NISN', 'Tempat Lahir', 'Tanggal Lahir',
            'Jenis Kelamin', 'Asal Sekolah', 'Alamat Santri', 'Nama Wali', 'Pekerjaan Wali',
            'Nomor WhatsApp', 'Alamat Wali', 'Status Pendaftaran', 'Diinput oleh', 'Tanggal Input',
            'Link Akta Kelahiran', 'Link Kartu Keluarga', 'Link Foto Formal', 'Link Raport', 'Link KTP Wali',
        ];
    }

    /**
     * @param CalonSantri $santri
     */
    public function map($santri): array
    {
        // Mengubah setiap objek santri menjadi baris data di Excel
        $dokumenLinks = [];
        $jenisDokumenList = ['akta_kelahiran', 'kartu_keluarga', 'foto_formal', 'raport', 'ktp_wali'];

        foreach ($jenisDokumenList as $jenis) {
            $dokumen = $santri->dokumen->firstWhere('jenis_dokumen', $jenis);
            // Pastikan URL yang dihasilkan adalah URL absolut yang bisa diakses
            $dokumenLinks[] = $dokumen ? route('dokumen.download', $dokumen->id) : 'Tidak Ada';
        }

        return [
            $santri->nomor_pendaftaran,
            $santri->nama_lengkap,
            $santri->nisn,
            $santri->tempat_lahir,
            $santri->tanggal_lahir,
            $santri->jenis_kelamin,
            $santri->asal_sekolah,
            $santri->alamat,
            $santri->wali->nama_wali ?? '-',
            $santri->wali->pekerjaan ?? '-',
            $santri->wali->nomor_whatsapp ?? '-',
            $santri->wali->alamat_wali ?? '-',
            str_replace('_', ' ', Str::title($santri->status_pendaftaran)),
            $santri->panitia->name ?? '-',
            $santri->created_at->format('d-m-Y H:i'),
            ...$dokumenLinks // Menggabungkan semua link dokumen ke dalam baris
        ];
    }
}