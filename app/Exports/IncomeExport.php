<?php

namespace App\Exports;

use App\Models\Bill;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IncomeExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return request()->session()->get('bills')->sortByDesc('nepali_date');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Cash',
            'Khalti',
            'Esewa',
            'Reward Pay',
            'Total Paid',
            'Unpaid',
            'Total Sales (excluding reward pay)',
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
            $row->cash,
            $row->khalti,
            $row->esewa,
            $row->reward_pay,
            $row->paid,
            $row->unpaid,
            $row->total
        ];
    }
}
