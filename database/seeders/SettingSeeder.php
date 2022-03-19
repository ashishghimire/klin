<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Setting::exists()) return;

        DB::table('settings')->insert([
            [
                'rewards_key' => 0.0,
            ],
        ]);
    }
}
