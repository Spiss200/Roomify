<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('welcome');
});

// Arahkan /search ke SearchController
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Detail Hotel tetap di HotelController
Route::get('/hotel/{id}', [HotelController::class, 'show'])->name('hotel.detail');