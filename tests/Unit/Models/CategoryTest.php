<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_automatically_generates_slug_on_creation(): void
    {
        $category = Category::create([
            'name' => 'Italian Pasta Pizza',
        ]);

        $this->assertEquals('italian-pasta-pizza', $category->slug);
    }

    public function test_category_updates_slug_when_name_changes(): void
    {
        $category = Category::create([
            'name' => 'Original Name',
        ]);

        $this->assertEquals('original-name', $category->slug);

        $category->update(['name' => 'Updated Category Name']);

        $this->assertEquals('updated-category-name', $category->fresh()->slug);
    }

    public function test_category_has_many_menu_items(): void
    {
        $category = Category::factory()->create();
        $item1 = MenuItem::factory()->create(['category_id' => $category->id]);
        $item2 = MenuItem::factory()->create(['category_id' => $category->id]);

        $this->assertCount(2, $category->MenuItem);
        $this->assertTrue($category->MenuItem->contains($item1));
        $this->assertTrue($category->MenuItem->contains($item2));
    }

    public function test_category_supports_soft_deletes(): void
    {
        $category = Category::factory()->create();
        $category->delete();

        $this->assertSoftDeleted($category);
    }
}
