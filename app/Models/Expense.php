<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'user_id', 'txn_no', 'details', 'category', 'nepali_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
