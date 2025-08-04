<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliSantri extends Model
{
    use HasFactory;

    protected $table = 'wali_santri';

    protected $fillable = [
        'calon_santri_id',
        'nama_wali',
        'pekerjaan',
        'nomor_whatsapp',
        'alamat_wali',
    ];

    // Relasi balik ke tabel calon_santri
    public function calonSantri()
    {
        return $this->belongsTo(CalonSantri::class);
    }
}
