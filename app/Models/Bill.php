<?php

namespace App\Models;

//use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Nilambar\NepaliDate\NepaliDate;

class Bill extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'service_details' => 'array',
    ];

    protected $fillable = ['customer_id', 'amount', 'user_id', 'service_details', 'paid_amount', 'payment_mode', 'payment_status', 'nepali_date', 'note'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
