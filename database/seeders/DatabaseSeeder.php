<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            PaymentModeSeeder::class,
            SettingSeeder::class,
            ExpenseCategorySeeder::class,
        ]);
    }
}
