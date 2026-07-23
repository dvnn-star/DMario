<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemFactory> */
    use HasFactory;

    protected $fillable = [
        'image_path',
        'name',
        'description',
        'price',
        'type',
        'is_available',
        'is_recommended',
    ];

    public function Category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('menu_items_with_categories');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('menu_items_with_categories');
        });
    }
}
