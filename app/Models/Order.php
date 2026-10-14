<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PREPARING = 'preparing';
    public const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_PREPARING => 'Preparing',
        self::STATUS_READY_FOR_PICKUP => 'Ready for Pickup',
        self::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
        self::STATUS_DELIVERED => 'Delivered',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    public const ALLOWED_TRANSITIONS = [
        self::STATUS_PENDING => [self::STATUS_CONFIRMED, self::STATUS_CANCELLED],
        self::STATUS_CONFIRMED => [self::STATUS_PREPARING, self::STATUS_CANCELLED],
        self::STATUS_PREPARING => [self::STATUS_READY_FOR_PICKUP, self::STATUS_CANCELLED],
        self::STATUS_READY_FOR_PICKUP => [self::STATUS_OUT_FOR_DELIVERY, self::STATUS_CANCELLED],
        self::STATUS_OUT_FOR_DELIVERY => [self::STATUS_DELIVERED, self::STATUS_CANCELLED],
        self::STATUS_DELIVERED => [],
        self::STATUS_CANCELLED => [],
    ];

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'subtotal',
        'delivery_fee',
        'grand_total',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'estimated_delivery_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'estimated_delivery_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->order_status, [self::STATUS_PENDING, self::STATUS_CONFIRMED], true);
    }

    public function isValidStatusTransition(string $newStatus): bool
    {
        if ($this->order_status === $newStatus) {
            return true;
        }

        $allowed = self::ALLOWED_TRANSITIONS[$this->order_status] ?? [];
        return in_array($newStatus, $allowed, true);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->order_status] ?? ucfirst($this->order_status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->order_status) {
            self::STATUS_PENDING => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-950/50 dark:text-amber-300',
            self::STATUS_CONFIRMED => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-950/50 dark:text-blue-300',
            self::STATUS_PREPARING => 'bg-indigo-100 text-indigo-800 border-indigo-300 dark:bg-indigo-950/50 dark:text-indigo-300',
            self::STATUS_READY_FOR_PICKUP => 'bg-purple-100 text-purple-800 border-purple-300 dark:bg-purple-950/50 dark:text-purple-300',
            self::STATUS_OUT_FOR_DELIVERY => 'bg-orange-100 text-orange-800 border-orange-300 dark:bg-orange-950/50 dark:text-orange-300',
            self::STATUS_DELIVERED => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-300',
            self::STATUS_CANCELLED => 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-950/50 dark:text-rose-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
