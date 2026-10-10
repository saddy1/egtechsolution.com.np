<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id','name','phone','email','address','city','country','zip',
        'subtotal','total','payment_method','order_status','placed_at',
        'source','external_reference',
    ];

    protected $casts = [
        'subtotal'  => 'decimal:2',
        'total'     => 'decimal:2',
        'placed_at' => 'datetime',
    ];

    // Order-level statuses
    public const STATUS_PENDING   = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_SHIPPED   = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Derive and save aggregated status from items */
    public function refreshStatus(): void
    {
        $this->loadMissing('items:id,order_id,status');

        $statuses = $this->items->pluck('status')->unique()->values();

        // All delivered → delivered
        if ($statuses->count() === 1 && $statuses->first() === OrderItem::STATUS_DELIVERED) {
            $this->order_status = self::STATUS_DELIVERED;
        }
        // All shipped|delivered (none pending/verified) → shipped
        elseif ($statuses->every(fn($s) => in_array($s, [OrderItem::STATUS_SHIPPED, OrderItem::STATUS_DELIVERED], true))) {
            $this->order_status = self::STATUS_SHIPPED;
        }
        // If any verified (no pending) → confirmed
        elseif ($statuses->contains(OrderItem::STATUS_VERIFIED) && !$statuses->contains(OrderItem::STATUS_PENDING)) {
            $this->order_status = self::STATUS_CONFIRMED;
        }
        // If all cancelled → cancelled (optional rule)
        elseif ($statuses->count() === 1 && $statuses->first() === OrderItem::STATUS_CANCELLED) {
            $this->order_status = self::STATUS_CANCELLED;
        }
        else {
            // default fallback while some items still pending
            $this->order_status = self::STATUS_PENDING;
        }

        $this->save();
    }

    /* Scopes for quick filtering in admin */
    public function scopeForCustomer($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }
}
