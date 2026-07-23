<?php

use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('caches menu items when accessed via landing page controller', function () {
    Cache::spy();
    
    // Create some items to render
    MenuItem::factory()->count(3)->create();
    
    // The first request should execute the closure and cache it
    $response = $this->get(route('menu'));
    
    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('landingpage/menu')
        ->has('menuItems', 3)
    );
    
    Cache::shouldHaveReceived('remember')->once()->with('menu_items_with_categories', \Mockery::any(), \Mockery::any());
});

it('clears menu_items_with_categories cache when a MenuItem is created', function () {
    Cache::put('menu_items_with_categories', 'dummy_data', now()->addDay());
    
    expect(Cache::has('menu_items_with_categories'))->toBeTrue();
    
    MenuItem::factory()->create();
    
    expect(Cache::has('menu_items_with_categories'))->toBeFalse();
});

it('clears menu_items_with_categories cache when a MenuItem is updated', function () {
    $menuItem = MenuItem::factory()->create();
    
    Cache::put('menu_items_with_categories', 'dummy_data', now()->addDay());
    expect(Cache::has('menu_items_with_categories'))->toBeTrue();
    
    $menuItem->update(['price' => 99999]);
    
    expect(Cache::has('menu_items_with_categories'))->toBeFalse();
});

it('clears menu_items_with_categories cache when a MenuItem is deleted', function () {
    $menuItem = MenuItem::factory()->create();
    
    Cache::put('menu_items_with_categories', 'dummy_data', now()->addDay());
    expect(Cache::has('menu_items_with_categories'))->toBeTrue();
    
    $menuItem->delete();
    
    expect(Cache::has('menu_items_with_categories'))->toBeFalse();
});
