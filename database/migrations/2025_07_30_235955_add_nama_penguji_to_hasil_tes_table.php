<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('hasil_tes', 'nama_penguji')) {
            Schema::table('hasil_tes', function (Blueprint $table) {
                $table->string('nama_penguji')->after('nilai_interview');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('hasil_tes', 'nama_penguji')) {
            Schema::table('hasil_tes', function (Blueprint $table) {
                $table->dropColumn('nama_penguji');
            });
        }
    }
};
