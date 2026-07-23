<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'table_id',
        'total_price',
        'status',
        'payment_method',
    ];

    public function orderDetails()
    {
        return $this->hasMany(orderDetails::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(table::class);
    }
}
