<?php


namespace App\Imports;


use App\Models\Customer;
use App\Models\ImportedLaundry;
use Maatwebsite\Excel\Concerns\ToModel;

class LaundryImport implements ToModel
{
    public function model(array $row)
    {

        return new ImportedLaundry([
            'imported_customer_manual_id' => $row[1],
            'amount' => $row[7],
        ]);
    }
}
