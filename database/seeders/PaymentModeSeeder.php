<?php

namespace Database\Seeders;

use App\Models\PaymentMode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (PaymentMode::exists()) return;

        DB::table('payment_modes')->insert([
            [
                'name' => 'cash',
            ],
            [
                'name' => 'esewa',
            ],
            [
                'name' => 'khalti',
            ],
            [
                'name' => 'reward points',
            ],
            [
                'name' => 'cheque',
            ],
            [
                'name' => 'fonepay',
            ],
            [
                'name' => 'bank deposit',
            ],
            [
                'name' => 'credited/adjusted',
            ],
            [
                'name' => 'bank transfer',
            ],
        ]);
    }
}
