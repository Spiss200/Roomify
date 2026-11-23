<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LandmarkEksternalController;
use App\Http\Controllers\LandmarkController;
use App\Http\Controllers\SemantikController;
use App\Services\FusekiClient;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function (FusekiClient $fuseki) {
    $landmarks = $fuseki->select("
        PREFIX roomify: <https://roomify.example/ontology#>
        SELECT ?id ?nama ?lat ?lon ?kat ?foto ?rating ?ulasan WHERE {
          ?lm a roomify:Landmark ;
              roomify:name ?nama ;
              roomify:latitude ?lat ;
              roomify:longitude ?lon ;
              roomify:category ?kat .
          OPTIONAL { ?lm roomify:photoUrl ?foto }
          OPTIONAL { ?lm roomify:rating ?rating }
          OPTIONAL { ?lm roomify:reviewCount ?ulasan }
          BIND(STRAFTER(STR(?lm), '#') AS ?id)
        } ORDER BY DESC(?ulasan)");

    if (empty($landmarks)) {
        $landmarks = array_map(function ($lm) {
            return [
                'id'     => $lm['id'],
                'nama'   => $lm['name'],
                'lat'    => (string)$lm['lat'],
                'lon'    => (string)$lm['lon'],
                'kat'    => $lm['category'] ?? 'Landmark',
                'alamat' => $lm['address'] ?? '',
                'foto'   => $lm['image'] ?? '/images/placeholder.svg',
                'rating' => isset($lm['rating']) ? (string)$lm['rating'] : '4.5',
                'ulasan' => isset($lm['reviews']) ? (string)$lm['reviews'] : '100',
            ];
        }, FusekiClient::LANDMARKS);
    }

    return view('welcome', compact('landmarks'));
})->name('home');

// Landmark Detail Page
Route::get('/landmark/{id}', [LandmarkController::class, 'show'])->name('landmark.show');

// Semantic Web & SPARQL Playground
Route::get('/semantik', [SemantikController::class, 'index'])->name('semantik.index');
Route::post('/api/semantik/query', [SemantikController::class, 'executeQuery'])->name('semantik.query');
Route::get('/semantik/download/{file}', [SemantikController::class, 'downloadFile'])->name('semantik.download');

// Search & Autocomplete
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/suggest', [SearchController::class, 'suggest'])->name('suggest');
Route::post('/api/ai-chat', [ChatbotController::class, 'respond'])->name('ai.chat');

// Detail Hotel
Route::get('/hotel/{id}', [HotelController::class, 'show'])->name('hotel.detail');
Route::get('/api/landmark-eksternal/{id}', [LandmarkEksternalController::class, 'show'])->name('api.landmark-eksternal');

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

// Admin Panel Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/users/{user}/toggle-role', [AdminController::class, 'toggleUserRole'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::patch('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.status');
    Route::delete('/bookings/{booking}', [AdminController::class, 'deleteBooking'])->name('bookings.delete');
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.delete');
});

require __DIR__ . '/auth.php';
