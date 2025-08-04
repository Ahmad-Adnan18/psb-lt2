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
        Schema::create('wali_santri', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke tabel calon_santri
            $table->foreignId('calon_santri_id')->unique()->constrained('calon_santri')->onDelete('cascade');
            
            $table->string('nama_wali');
            $table->string('pekerjaan');
            $table->string('nomor_whatsapp');
            $table->text('alamat_wali')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_santri');
    }
};
