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
        Schema::create('gaji', function (Blueprint $table) {
            $table->id('id_gaji');
            $table->unsignedBigInteger('karyawan_id');
            $table->integer('periode_bulan');
            $table->integer('periode_tahun');
            $table->integer('total_hari_kerja')->nullable();
            $table->decimal('total_lembur', 12, 2)->default(0);
            $table->decimal('potongan', 12, 2)->default(0);
            $table->decimal('total_gaji', 12, 2);
            $table->date('tgl_dibayar')->nullable();
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji');
    }
};
