<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class akun extends Model
{
    protected $table = 'akuns';
    protected $fillable = [
        'name_akun',
        'category',
        'optional'
    ];
}
