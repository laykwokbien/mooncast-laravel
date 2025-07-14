<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jurusan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function Siswas()
    {
        return $this->hasMany(siswa::class, 'jurusan', 'id');
    }
}
