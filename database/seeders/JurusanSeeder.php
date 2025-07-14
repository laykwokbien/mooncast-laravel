<?php

namespace Database\Seeders;

use App\Models\jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusans = [
            'RPL',
            'DKV',
            'MP',
            'AK',
            'PSPTV',
            'LP',
            'BD',
            'TKJ',
        ];

        foreach($jurusans as $jurusan){
            jurusan::create([
                'nama_jurusan' => $jurusan,
            ]);
        }
    }
}
