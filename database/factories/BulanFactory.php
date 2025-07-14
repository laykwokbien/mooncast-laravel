<?php

namespace Database\Factories;

use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\bulan>
 */
class BulanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => 12,
            'umur' => $this->faker->numberBetween(15, 18),
            'semester' => $this->faker->numberBetween(1, 6),
            'tanggal_mulai' => $this->faker->dateTimeBetween('-12 month', 'now'),
            'durasi_siklus' => $this->faker->numberBetween(21, 35),
            'durasi_haid' => $this->faker->numberBetween(1, 15),
            'suhu_tubuh' => $this->faker->randomFloat(2, 35, 46.5),
            'aktivitas_fisik' => $this->faker->randomElement(['Tidak','Ringan', 'Sedang', 'Berat']),
            'stress' => $this->faker->randomElement(['Tidak','Rendah', 'Sedang', 'Tinggi']),
            'masalah_kesehatan' => $this->faker->randomElement(['Ya', 'Tidak']),
            'rokok' => $this->faker->randomElement(['Ya', 'Tidak']),
            'obat' => $this->faker->randomElement(['Ya', 'Tidak']),
        ];
    }
}
