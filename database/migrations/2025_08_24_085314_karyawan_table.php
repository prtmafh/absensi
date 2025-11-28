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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->unsignedBigInteger('jabatan_id');
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('no_hp', 20)->nullable();
            $table->date('tgl_masuk');
            $table->unsignedBigInteger('jenis_gaji_id');
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
            $table->integer('kuota_izin')->default(12);
            $table->string('foto', 256)->nullable();
            $table->timestamps();

            $table->foreign('jenis_gaji_id')->references('id_jenis_gaji')->on('jenis_gaji')->onDelete('cascade');
            $table->foreign('jabatan_id')->references('id_jabatan')->on('jabatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
