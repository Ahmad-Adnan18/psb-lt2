<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dokumen_santri', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel calon_santri
            $table->foreignId('calon_santri_id')->constrained('calon_santri')->onDelete('cascade');

            $table->enum('jenis_dokumen', ['akta_kelahiran', 'kartu_keluarga', 'foto_formal', 'raport', 'ktp_wali']);
            $table->string('nama_file');
            $table->string('path_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_santri');
    }
};