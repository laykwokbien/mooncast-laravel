<?php

namespace App\Jobs;

use App\ML\RandomForestRegressor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PredictHandler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $h, $X, $saved_model;

    public function __construct(int $h, array $X, $saved_model)
    {
        $this->h = $h;
        $this->X = $X;
        $this->saved_model = $saved_model;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $X = $this->X;
        $target_month = (($X['bulan'] - 1 - $this->h) % 12) + 1;
        unset($X['bulan']);
        $X = array_values($X);
        $X = array_merge($X, [
            $this->h, 
            $target_month, 
            sin(2 * pi() * $target_month / 12), 
            cos(2 * pi() * $target_month / 12)
        ]);
        $model_temp = new RandomForestRegressor();
        $model_temp = $model_temp->import($this->saved_model);
        $result = $model_temp->predict([$X])[0];
        cache()->put("prediction_$this->h", [
            'durasi_siklus' => intval($result[0]),
            'durasi_menstruasi' => intval($result[1]),
        ], 300);
    }
}
