<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Service::exists()) return;

        DB::table('services')->insert([
            [
                'name' => 'Super Express Wash',
                'rate' => 135,
                'unit' => 'kg',
            ],
            [
                'name' => 'Express Wash',
                'rate' => 115,
                'unit' => 'kg',
            ],
            [
                'name' => 'Normal Wash',
                'rate' => 99,
                'unit' => 'kg',
            ],
            [
                'name' => 'Pick and Drop',
                'rate' => 130,
                'unit' => 'kg',
            ],
            [
                'name' => 'Double Wash',
                'rate' => 159,
                'unit' => 'kg',
            ],
            [
                'name' => 'Blanket under 3 kg',
                'rate' => 350,
                'unit' => 'pc',
            ],
            [
                'name' => 'Blanket over 3 kg',
                'rate' => 500,
                'unit' => 'pc',
            ],
            [
                'name' => 'Shoe Wash',
                'rate' => 100,
                'unit' => 'pc',
            ],
            [
                'name' => 'Ironing',
                'rate' => 20,
                'unit' => 'pc',
            ],
            [
                'name' => 'Dettol Wash',
                'rate' => 10,
                'unit' => 'kg',
            ],
            [
                'name' => 'Express Wash for Blanket',
                'rate' => 50,
                'unit' => 'pc',
            ],
        ]);
    }
}
