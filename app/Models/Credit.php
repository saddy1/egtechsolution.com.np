<?php

// app/Models/Credit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'party_name','contact','address','details',
        'principal_amount','outstanding_amount',
        'credit_date_bs','due_date_bs',
        'status','notes',
    ];

    protected $casts = [
        'principal_amount'   => 'decimal:2',
        'outstanding_amount' => 'decimal:2',
        // no AD date casts anymore
    ];

    public function payments() {
        return $this->hasMany(CreditPayment::class)->orderBy('paid_date_bs','desc')->orderBy('id','desc');
    }

    public function scopeOpen($q)  { return $q->where('status','open'); }
    public function scopeClosed($q){ return $q->where('status','closed'); }
}
