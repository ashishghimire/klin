<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return request()->session()->get('expenses')->sortByDesc('nepali_date');

    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'TXN No.',
            'Amount',
            'Payment Mode',
            'Category',
            'Details',
            'Added By',
        ];
    }

    /**
     * @param  mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->nepali_date,
            $row->txn_no,
            $row->amount,
            $row->mode,
            $row->category,
            $row->details,
            $row->user->name,
        ];
    }
}
