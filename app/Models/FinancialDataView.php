<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialDataView extends Model
{
    protected $table = 'financial_data_view';
    protected $primaryKey = null; // View tidak memiliki primary key, jadi nonaktifkan
    public $incrementing = false; // Nonaktifkan incrementing
    protected $keyType = 'string'; // Nonaktifkan key type, jika diperlukan
    protected $fillable = [
        'order_number',
        'order_status',
        'product_name',
        'total_payment',
        'order_created_at',
        'product_sku',
        'marketplace'
    ];
}
