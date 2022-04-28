<?php

namespace App\Models;

//use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Nilambar\NepaliDate\NepaliDate;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'address', 'phone', 'nepali_date', 'reward_points'
    ];

    public function bills(){
        return $this->hasMany(Bill::class);
    }

//    public function getNepaliDateAttribute()
//    {
//        $englishDate = new Carbon($this->attributes['created_at']);
//        $year = $englishDate->format('Y');
//        $month = $englishDate->format('m');
//        $day = $englishDate->format('d');
//        $nepaliDateObj = new NepaliDate();
//        $nepaliDate = $nepaliDateObj->convertAdToBs($year, $month, $day);
//        $date = $nepaliDate['year'] . '-' . $nepaliDate['month'] . '-' . $nepaliDate['day'];
//        return $date;
//    }
}
