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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->string('nama_siswa')->unique();
            $table->foreignId('id_akun')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('jurusan')->nullable()->constrained('jurusans')->nullOnDelete();
            $table->date('tanggal_lahir')->nullable();
            $table->integer('tahun_masuk');
            $table->string('alamat')->nullable();
            $table->integer('tahun_lulus')->default(0000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
