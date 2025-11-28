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
        Schema::create('jenis_gaji', function (Blueprint $table) {
            $table->id('id_jenis_gaji');
            $table->string('sistem_gaji', 100); // Ubah dari enum ke string
            $table->decimal('upah', 12, 2);
            // $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_gaji');
    }
};
