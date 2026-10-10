<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id','supplier_name',
        'product_id','product_name',
        'quantity',
        'rate_excl_vat','rate_incl_vat','total',
        'bill_no','purchased_at',
    ];

    protected $casts = [
        'purchased_at' => 'date',
        'rate_excl_vat' => 'decimal:2',
        'rate_incl_vat' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function supplier(){ return $this->belongsTo(Supplier::class); }
    public function product(){ return $this->belongsTo(Product::class); }
}
