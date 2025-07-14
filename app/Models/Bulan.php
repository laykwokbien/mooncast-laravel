<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulan extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    public $timestamps = true;
    public function getKelasAttribute(){
        $semester = $this->semester;
        $kelas = match (true){
            $semester >= 5 => 'Kelas 12',
            $semester >= 3 => 'Kelas 11',
            $semester >= 1 => 'Kelas 10',
            default => 'Belum Terdata'
        };
        return $kelas;
    }
    
    public function SiswaData(){
        return $this->hasOne(siswa::class, 'nis', 'nis');
    }

}
