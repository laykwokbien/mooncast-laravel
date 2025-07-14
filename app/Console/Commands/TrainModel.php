<?php

namespace App\Console\Commands;

use Http;
use Illuminate\Console\Command;

class TrainModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrain Model Manually';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Http::timeout(5)->get('http://localhost:5000/retrain');
        $this->info('Traning in Progress Please Wait');
    }
}
