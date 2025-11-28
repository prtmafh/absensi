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
        Schema::create('absen', function (Blueprint $table) {
            $table->id('id_absen');
            $table->unsignedBigInteger('karyawan_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->enum('status', ['hadir', 'izin', 'tidak hadir', 'terlambat'])->default('hadir');
            $table->decimal('latitude', 10, 7)->nullable();   // contoh: -6.2345678
            $table->decimal('longitude', 10, 7)->nullable();  // contoh: 106.9876543
            $table->string('foto')->nullable(); // simpan path file foto di storage
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen');
    }
};
