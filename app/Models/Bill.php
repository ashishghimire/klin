<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'service_details' => 'array',
    ];

    protected $fillable = ['customer_id', 'amount', 'user_id', 'estimate_no', 'service_details', 'paid_amount', 'payment_mode'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
