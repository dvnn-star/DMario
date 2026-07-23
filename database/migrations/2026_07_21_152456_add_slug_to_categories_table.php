<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat kolom HANYA JIKA belum ada di tabel
        if (! Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }

        // 2. Generate slug untuk data lama yang belum punya slug
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            DB::table('categories')
                ->where('id', $category->id)
                ->update([
                    'slug' => Str::slug($category->name).'-'.$category->id,
                ]);
        }

        // 3. Pasang constraint unique
        Schema::table('categories', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
