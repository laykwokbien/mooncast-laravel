<?php

namespace App\Console\Commands;

use Http;
use Illuminate\Console\Command;

class ModelStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking Model Status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $result = Http::get('http://localhost:5000/status')->json();

        $this->info("Status Traning Model: {$result['Traning']}");
    }
}
