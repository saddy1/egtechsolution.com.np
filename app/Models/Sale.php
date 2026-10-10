<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'bill_no','billing_name','pan','address','remarks',
        'subtotal','total_cost','profit','bill_date_bs',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function items() { return $this->hasMany(SaleItem::class); }
}