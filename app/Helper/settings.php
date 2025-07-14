<?php

namespace App\Helper;

use DB;

class Settings{
    static function get_settings($key){
        return DB::table('settings')->where('key', $key)->value('value') ?? null;
    }

    static function getScheduleSettings(): array{
        $auto = DB::table('settings')->where('key', 'auto_train')->value('value') ?? null;
        $date = DB::table('settings')->where('key', 'train_month')->value('value') ?? null;

        return ['auto' => (bool)$auto, 'date' => (int)$date];
    }
    
    static function updateScheduleSettings($enable, $month): void{
        DB::table('settings')->where('key', 'auto_train')->update(['value' => $enable]);
        DB::table('settings')->where('key', 'train_month')->update(['value' => $month]);
    }
}