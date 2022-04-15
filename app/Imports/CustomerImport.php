<?php


namespace App\Imports;


use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class CustomerImport implements ToCollection
{

    public $data = [];

    public function collection(Collection $rows)
    {
        $data = [];

//        $i = 0;
//        foreach ($rows as $row) {
//            if ($i > 0) {
//                $data['name'] = $row[1];
//                $data['address'] = $row[2];
//                $data['phone'] = $row[4];
//                $data['customer_id'] = $row[5];
//            }
//
//            $i++;
//
//            array_push($this->data, $data);
//        }
    }
}
