<?php
// app/Models/CreditPayment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditPayment extends Model
{
    protected $fillable = [
        'credit_id','amount','paid_date_bs','reference','remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        // no AD date cast
    ];

    public function credit() {
        return $this->belongsTo(Credit::class);
    }
}
