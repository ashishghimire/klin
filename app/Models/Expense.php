<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'user_id', 'txn_no', 'details', 'category'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
