<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class withdrawal_tokopedia extends Model
{
    use HasFactory;
    protected $table ='withdrawal_tokopedias';
    protected $fillable = [
        'type',
        'reference_id',
        'request_time_utc',
        'amount',
        'status',
        'success_time_utc',
        'bank_account',
    ];

    protected $casts = [
        'request_time_utc' => 'datetime',
        'success_time_utc' => 'datetime',
        'amount' => 'float',
    ];
}
