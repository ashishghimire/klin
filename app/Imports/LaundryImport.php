<?php


namespace App\Imports;


use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class LaundryImport implements ToModel
{
    public function model(array $row)
    {
        dd($row);
//        return new Customer([
//            'name' => $row[0],
//            'email' => $row[1],
//            'password' => Hash::make($row[2]),
//        ]);
    }
}
