<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedLaundry extends Model
{
    use HasFactory;

    protected $fillable = ['imported_customer_manual_id', 'amount'];

}
