<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CalonSantri extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'calon_santri';

    protected $fillable = [
        'nomor_pendaftaran',
        'nama_lengkap',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'asal_sekolah',
        'nomor_hp_santri',
        'status_pendaftaran',
        'user_id',
    ];

    public function hasilTes()
{
    return $this->hasOne(HasilTes::class);
}

    // Relasi ke tabel users (siapa yang menginput)
    public function panitia()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi one-to-one ke tabel wali_santri
    public function wali()
    {
        return $this->hasOne(WaliSantri::class);
    }

    // Relasi one-to-many ke tabel dokumen_santri
    public function dokumen()
    {
        return $this->hasMany(DokumenSantri::class);
    }

    /**
     * Route notifications for the custom channel.
     * Ini memberitahu Laravel di mana menemukan "nomor telepon" untuk notifikasi.
     */
    public function routeNotificationForWhatsApp()
    {
        return $this->wali->nomor_whatsapp ?? null;
    }

    public function routeNotificationForFonnte($notification)
    {
        // Pastikan relasi wali sudah dimuat
        $this->loadMissing('wali');
        return $this->wali->nomor_whatsapp;
    }
}
