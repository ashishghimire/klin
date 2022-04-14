<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Customer::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Customer Id',
            'Name',
            'Address',
            'Phone No.',
            'Reward Points',
            'Created Date',
            'Updated Date',
            'Deleted Date',
        ];
    }
}
