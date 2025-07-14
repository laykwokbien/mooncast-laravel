<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProximoPeriod extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function isSiswa(){
        return $this->belongsTo(siswa::class, 'nis', 'nis');
    }

    public function getJurusanAttribute(){
        $kelas = $this->isSiswa->isJurusan->nama_jurusan;
        return $kelas;
    }
}
