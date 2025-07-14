<?php

namespace App\Console\Commands;

use Cache;
use Illuminate\Console\Command;
use Storage;

class CacheModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:cache-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache Model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', -1);
        $rf_models = unserialize(Storage::get('app/models/RandomForest.mdl'));
        $encoding_models = unserialize(Storage::get('app/models/encoding.mdl'));

        Cache::forever('rf_models', $rf_models);
        Cache::forever('encoder_models', $encoding_models);

        $this->info('Cache Model Complete');
    }
}
