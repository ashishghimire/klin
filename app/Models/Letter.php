<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = ['ref_no', 'to', 'address', 'subject', 'body', 'signed_by', 'designation', 'nepali_date', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

