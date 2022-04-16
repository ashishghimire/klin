<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'phone', 'manual_id', 'reward_points', 'created_at', 'updated_at'
    ];

    public $timestamps = false;
}
