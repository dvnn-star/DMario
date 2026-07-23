<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Mengubah tipe kolom dari varchar(255) ke boolean (tinyint(1))
            $table->boolean('is_recommended')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Rollback kembali ke string jika diperlukan
            $table->string('is_recommended', 255)->default('0')->change();
        });
    }
};
