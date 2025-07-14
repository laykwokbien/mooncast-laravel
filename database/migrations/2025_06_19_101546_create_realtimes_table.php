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
        Schema::create('realtimes', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->nullable();
            $table->foreign('nis')->references('nis')->on('siswas')->nullOnDelete();
            $table->integer('umur');
            $table->integer('semester');
            $table->date('tanggal_mulai');
            $table->integer('durasi_siklus');
            $table->integer('durasi_haid');
            $table->float('suhu_tubuh');
            $table->enum('aktivitas_fisik', array('Tidak','Ringan', 'Sedang', 'Berat'));
            $table->enum('stress', array('Tidak','Rendah', 'Sedang', 'Tinggi'));
            $table->enum('masalah_kesehatan', array('Ya', 'Tidak'));
            $table->enum('rokok', array('Ya', 'Tidak'));
            $table->enum('obat', array('Ya', 'Tidak'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realtimes');
    }
};
