<?php

namespace App\Console\Commands;

use App\ML\RandomForestRegressor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class mltest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);
        $encoder = unserialize(Storage::get('app/models/encoding.mdl'));
        
        $X = [
            'bulan' => 8,
            'suhu_tubuh' =>36.2,
            'mood' => 'Sensitif',
            'nyeri' => 'Tidak',
            'aktivitas_fisik' => 'Rendah',
            'stress' => 'Rendah',
            'obat' => 'Tidak',
        ];


        foreach ($encoder as $col => $encode){
            $X[$col] = $encode[$X[$col]];
        }

        $this->info('Encoded Complete');
        $pred = [];
        $models = unserialize(Storage::get('app/models/RandomForest.mdl'));
        for ($h = 1; $h < count($models) + 1; $h++){
            $X_temp = $X;
            $target_month = (($X_temp['bulan'] - 1 - $h) % 12) + 1;
            unset($X_temp['bulan']);
            $X_temp = array_values($X_temp);
            $X_temp = array_merge($X_temp, [$h, $target_month, sin(2 * pi() * $target_month / 12), cos(2 * pi() * $target_month / 12)]);
            $model = new RandomForestRegressor();
            $model = $model->import($models[$h]);
            $pred[] = $model->predict([$X_temp]);

            $this->info("Prediction for horizon $h complete");
        }
        dd($pred);

        return response()->json();
    }
}
