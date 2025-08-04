<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_santri_id')->constrained('calon_santri')->onDelete('cascade');
            $table->string('nilai_alquran', 2)->nullable();
            $table->string('nilai_arab', 2)->nullable();
            $table->string('nilai_inggris', 2)->nullable();
            $table->string('nilai_matematika', 2)->nullable();
            $table->string('nilai_interview', 2)->nullable();
            $table->text('catatan')->nullable();
             $table->string('nama_penguji')->after('interview');
            $table->timestamps();
        });
    }
    // ... down() method
};