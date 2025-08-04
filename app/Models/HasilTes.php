<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTes extends Model
{
    use HasFactory;
    protected $table = 'hasil_tes';
    protected $fillable = [
        'calon_santri_id', 'catatan', 'nilai_alquran', 'nilai_arab',
        'nilai_inggris', 'nilai_matematika', 'nilai_interview', 'nama_penguji'
    ];
}