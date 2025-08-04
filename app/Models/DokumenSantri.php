<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenSantri extends Model
{
    use HasFactory;

    protected $table = 'dokumen_santri';

    protected $fillable = [
        'calon_santri_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
    ];

    // Relasi balik ke tabel calon_santri
    public function calonSantri()
    {
        return $this->belongsTo(CalonSantri::class);
    }
}
