<?php

namespace App\Exports;

use App\Models\Bill;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomeExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Bill::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Id',
            'Customer Id',
            'Estimate No.',
            'Service Details',
            'Amount',
            'Paid Amount',
            'Payment Status',
            'Payment Mode',
            'Laundry Status',
            'User Id',
            'Created At',
            'Updated At',
            'Deleted At',
        ];
    }
}
