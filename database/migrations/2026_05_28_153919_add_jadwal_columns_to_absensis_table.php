<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {

            $table->string('hari')->nullable();

            $table->enum('jenis_hari', [
                'Masuk',
                'Libur'
            ])->default('Masuk');

            $table->enum('keterangan_absen', [
                'Hadir',
                'Telat',
                'Izin',
                'Sakit',
                'Tidak Absen',
                'Libur'
            ])->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {

            $table->dropColumn([
                'hari',
                'jenis_hari',
                'keterangan_absen'
            ]);

        });
    }
};