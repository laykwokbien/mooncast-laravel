<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prediction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function isSiswa(){
        return $this->hasOne(siswa::class, 'nis',  'nis');
    }
}
