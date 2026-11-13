<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Search & Autocomplete
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/suggest', [SearchController::class, 'suggest'])->name('suggest');
Route::post('/api/ai-chat', [ChatbotController::class, 'respond'])->name('ai.chat');

// Detail Hotel
Route::get('/hotel/{id}', [HotelController::class, 'show'])->name('hotel.detail');

// Booking Flow
Route::get('/hotel/{id}/book', [BookingController::class, 'showForm'])->name('booking.form');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::post('/hotel/{id}/book', [BookingController::class, 'store']);

// Payment Flow
Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment', [PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');

// User Dashboard & Profile (Laravel Breeze)
Route::get('/dashboard', function () {
    $bookings = auth()->check() ? auth()->user()->bookings : collect();
    return view('dashboard', compact('bookings'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
