<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\siswa::create([
                'nis' => 12, 
                'nama_siswa' => 'Not Found',
                'id_akun' => 1,
                'tanggal_lahir' => now(),
                'tahun_masuk' => 2025,
                'alamat' => 'SMKN 1 BWS',
                'tahun_lulus' => 2025,
            ]);

        \App\Models\Bulan::factory()->count(5000)->create();
        
        $settings = [
                [
                    'key' => 'auto_train',
                    'value' => '0',
                ],
                [
                    'key' => 'train_month',
                    'value' => '8'
                ]
            ];

        foreach ($settings as $setting){
            DB::table('settings')->insert($setting);
        }

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
