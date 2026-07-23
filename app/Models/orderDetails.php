<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class orderDetails extends Model
{
    /** @use HasFactory<\Database\Factories\OrderDetailsFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'price',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
