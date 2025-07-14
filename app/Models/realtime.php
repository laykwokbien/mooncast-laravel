<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class realtime extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    
    public function siswa(){
        return $this->belongsTo(siswa::class, 'nis', 'nis');
    }
    public function getTanggalHaidAttribute(){
        $date = new Carbon($this->tanggal_mulai);
        return $date->format("d M Y");
    }
    public function getKelasAttribute(){
        $semester = $this->siswa->isJurusan->nama_jurusan;

        $kelas = match (true) {
            $semester >= 5 => 'Kelas 12',
            $semester >= 3 => 'Kelas 11',
            $semester >= 1 => 'Kelas 10',
            default => 'Belum Terdata',
        };

        return $kelas;
    }
    public function getJurusanAttribute(){
        $jurusan = $this->siswa->isJurusan->nama_jurusan;

        return $jurusan;
    }
}
