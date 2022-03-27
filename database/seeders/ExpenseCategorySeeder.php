<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (ExpenseCategory::exists()) return;

        DB::table('expense_categories')->insert([
            [
                'name' => 'electricity',
            ],
            [
                'name' => 'detergent',
            ],
            [
                'name' => 'rent',
            ],
            [
                'name' => 'petrol'
            ],
            [
                'name' => 'misc'
            ],

        ]);
    }
}
