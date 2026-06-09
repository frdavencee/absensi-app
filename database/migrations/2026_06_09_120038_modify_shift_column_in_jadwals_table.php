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
        Schema::table('jadwals', function (Blueprint $table) {
            $table->enum('shift', ['Pagi', 'Siang', 'Malam'])->nullable()->change();
            $table->time('jam_masuk')->nullable()->change();
            $table->time('jam_pulang')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->enum('shift', ['Pagi', 'Siang', 'Malam'])->nullable(false)->change();
            $table->time('jam_masuk')->nullable(false)->default('08:00:00')->change();
            $table->time('jam_pulang')->nullable(false)->default('17:00:00')->change();
        });
    }
};
