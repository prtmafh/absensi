<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('izin', function (Blueprint $table) {
            $table->id('id_izin');
            $table->unsignedBigInteger('karyawan_id');
            $table->date('tanggal_izin');
            $table->enum('jenis_izin', ['sakit', 'izin', 'cuti', 'lainnya']);
            $table->text('keterangan')->nullable();
            $table->string('lampiran')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id_karyawan')
                ->on('karyawan')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
