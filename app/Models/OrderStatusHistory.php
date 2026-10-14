<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by',
        'note',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getOldStatusLabelAttribute(): ?string
    {
        return $this->old_status ? (Order::STATUSES[$this->old_status] ?? ucfirst($this->old_status)) : null;
    }

    public function getNewStatusLabelAttribute(): string
    {
        return Order::STATUSES[$this->new_status] ?? ucfirst($this->new_status);
    }
}
