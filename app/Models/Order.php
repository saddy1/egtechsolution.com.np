<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id','name', 'phone', 'email', 'address', 'city', 'country', 'zip', 'total', 'order_status', 'payment_method',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
