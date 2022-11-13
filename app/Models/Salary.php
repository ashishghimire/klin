<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nilambar\NepaliDate\NepaliDate;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'expense_id', 'type', 'details'
    ];

    public function getNepaliDateAttribute()
    {
        $englishDate = new Carbon($this->attributes['created_at']);
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateObj = new NepaliDate();
        $nepaliDate = $nepaliDateObj->convertAdToBs($year, $month, $day);
        $date = $nepaliDate['year'] . '-' . $nepaliDate['month'] . '-' . $nepaliDate['day'];
        return $date;
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
