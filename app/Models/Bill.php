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

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

}
