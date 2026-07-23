<?php

use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderStatusController;
use App\Http\Controllers\Dashboard\MenuController;
use App\Http\Controllers\LandingPageController;
use App\Http\Middleware\EnsureTableSelected;
use Filament\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::controller(LandingPageController::class)->group(function () {
    Route::get('/','index')->name('landingpage');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/reservation', 'reservation')->name('reservation');
    Route::post('/reservation', 'store')->name('reservation.store');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/menu/table/{identifier}', 'ShowMenuQr')->name('menu.table');
});


Route::middleware([EnsureTableSelected::class])->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/order/status', OrderStatusController::class)->name('order.status');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/clear-cache', function () {
        Artisan::call('optimize:clear');

        NotificationsNotification::make()
            ->title('Cache Berhasil Dibersihkan!')
            ->success()
            ->send();

        return back();
    })->name('filament.admin.clear-cache');
});