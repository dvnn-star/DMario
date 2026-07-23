<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class table extends Model
{
    /** @use HasFactory<\Database\Factories\TableFactory> */
    use HasFactory;
    protected $fillable = [
        'table_number',
        'qr_code_path',

        'status',
    ];
    protected static function booted()
    {
        static::creating(function($table) {
            // Otomatis isi UUID jika belum ada
            if (empty($table->identifier)) {
                $table->identifier = (string) Str::uuid();
            }
        });
            
    }
}
