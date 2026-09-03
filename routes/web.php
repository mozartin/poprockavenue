<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en');

Route::prefix('{locale}')
    ->where(['locale' => 'en|nl|uk|ru'])
    ->middleware('set.locale')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/the-band', [PageController::class, 'band'])->name('band');
        Route::get('/weddings', [PageController::class, 'event'])->defaults('event', 'weddings')->name('weddings');
        Route::get('/corporate-events', [PageController::class, 'event'])->defaults('event', 'corporate-events')->name('corporate');
        Route::get('/private-parties', [PageController::class, 'event'])->defaults('event', 'private-parties')->name('private-parties');
        Route::get('/christmas-new-year', [PageController::class, 'event'])->defaults('event', 'christmas-new-year')->name('christmas');
        Route::get('/repertoire', [PageController::class, 'repertoire'])->name('repertoire');
        Route::get('/media', [PageController::class, 'media'])->name('media');
        Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');
        Route::get('/contact', [PageController::class, 'contact'])->name('contact');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    });
