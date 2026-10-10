<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id','product_id','product_name',
        'quantity','selling_price','cost_price',
        'line_total','line_cost_total','line_profit','item_remarks',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'line_cost_total' => 'decimal:2',
        'line_profit' => 'decimal:2',
    ];

    public function sale()    { return $this->belongsTo(Sale::class); }
    public function product() { return $this->belongsTo(Product::class); }
}