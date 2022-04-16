<?php


namespace App\Imports;


use App\Models\ImportedCustomer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;


class CustomerImport implements ToModel
{

    public $data = [];

    public function model(array $row)
    {
        return new ImportedCustomer([
            'name' => $row[1],
            'address' => $row[2],
            'phone' => $row[4],
            'manual_id' => $row[5],
            'created_at' => Carbon::now()->toDateString(),
            'updated_at' => Carbon::now()->toDateString(),
        ]);
    }
}
