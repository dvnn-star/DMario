<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'customer_name',
        'customer_contact',
        'status',
        'table_id',
        'reservation_time',
        'number_of_guests',
        'special_requests',
    ];
    public function table(): BelongsTo
    {
        return $this->belongsTo(\App\Models\table::class, 'table_id');
    }
}
