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
        Schema::create('calon_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('nama_lengkap');
            $table->string('nisn')->nullable();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat');
            $table->string('asal_sekolah');
            $table->string('nomor_hp_santri')->nullable();
            $table->enum('status_pendaftaran', ['menunggu_verifikasi', 'lulus_seleksi', 'belum_lulus'])->default('menunggu_verifikasi');
            
            // Foreign key ke tabel users untuk mencatat siapa yg input
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_santri');
    }
};
