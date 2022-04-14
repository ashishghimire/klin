<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Expense::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Id',
            'TXN No.',
            'Category',
            'Details',
            'Amount',
            'User Id',
            'Created Date',
            'Updated Date',
        ];
    }
}
