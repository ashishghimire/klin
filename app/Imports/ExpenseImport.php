<?php

namespace App\Imports;

use App\Models\Expense;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Nilambar\NepaliDate\NepaliDate;

class ExpenseImport implements ToModel
{

    /**
     * @var NepaliDate
     */
    protected $nepaliDate;

    public function __construct(NepaliDate $nepaliDate)
    {
        $this->nepaliDate = $nepaliDate;
    }

    public function model(array $row)
    {
        $dateArray = explode(".", trim($row[6]));
        $year = !empty($dateArray[0]) ? $dateArray[0] : '2079';
        $month = !empty($dateArray[1]) ? $dateArray[1] : '01';
        $day = !empty($dateArray[2]) ? $dateArray[2] : '01';

        $englishDate = $this->nepaliDate->convertBsToAd($year, $month, $day);
        $date = Carbon::parse(implode("-", $englishDate))->endOfDay()->toDateTimeString();

        $expense = new Expense();
        $expense->timestamps = false;
        $expense->txn_no = $row[5];
        $expense->category = $row[1];
        $expense->details = $row[2];
        $expense->amount = (float)$row[3];
        $expense->user_id = auth()->user()->id;
        $expense->mode = $row[4];
        $expense->payee = $row[7];
        $expense->receiver = $row[8];
        $expense->nepali_date = str_replace('.', '-', $row[6]);
        $expense->created_at = $date;
        $expense->updated_at = $date;
        $expense->save();

        return $expense;

    }

}
