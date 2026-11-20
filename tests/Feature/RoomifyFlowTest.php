<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoomifyFlowTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;
    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Roomify');
        $response->assertSee('Istana Maimun');
        $response->assertSee('Tjong A Fie');
        $response->assertSee('Masjid Raya');
        $response->assertSee('Maha Vihara Maitreya');
    }

    public function test_search_page_renders_with_hotels_and_maps(): void
    {
        $response = $this->get('/search');
        $response->assertStatus(200);
        $response->assertSee('Roomify');
        $response->assertSee('maps.googleapis.com');
        $response->assertSee('maps.googleapis.com');
    }

    public function test_search_page_filters_by_landmark(): void
    {
        $response = $this->get('/search?landmark=Istana+Maimun');
        $response->assertStatus(200);
    }

    public function test_hotel_detail_renders_with_google_maps_and_rooms(): void
    {
        $response = $this->get('/hotel/hotel-001');
        $response->assertStatus(200);
        $response->assertSee("d'primahotel Kualanamu Medan");
        $response->assertSee('maps.googleapis.com');
        $response->assertSee('hotel-map');
    }

    public function test_booking_form_renders_with_hotel_data(): void
    {
        $response = $this->get('/hotel/hotel-001/book');
        $response->assertStatus(200);
        $response->assertSee("d'primahotel Kualanamu Medan");
        $response->assertSee('Data Pemesan');
    }

    public function test_dashboard_renders_for_authenticated_user(): void
    {
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Halo, ' . $user->name);
        $response->assertSee('Hotel Terdaftar');
        $response->assertSee('Landmark Ikonik');
    }

    public function test_booking_and_payment_flow(): void
    {
        $bookingData = [
            'hotel_id'         => 'hotel-001',
            'hotel_name'       => "d'primahotel Kualanamu Medan",
            'room_type'        => 'Deluxe',
            'room_price'       => 668512,
            'checkin'          => date('Y-m-d', strtotime('+2 days')),
            'checkout'         => date('Y-m-d', strtotime('+4 days')),
            'guests'           => 2,
            'name'             => 'Budi Santoso',
            'email'            => 'budi@example.com',
            'phone'            => '081234567890',
            'no_ktp'           => '1271012345678901',
            'special_requests' => 'Dekat lift',
        ];

        // 1. Submit booking
        $postBooking = $this->post(route('booking.store'), $bookingData);
        $postBooking->assertRedirect(route('payment.show'));

        // 2. View payment page
        $getPayment = $this->get(route('payment.show'));
        $getPayment->assertStatus(200);
        $getPayment->assertSee('Ringkasan Pesanan');
        $getPayment->assertSee("d'primahotel Kualanamu Medan");

        // 3. Process payment
        $postPayment = $this->post(route('payment.process'), [
            'payment_method' => 'gopay',
        ]);
        $postPayment->assertStatus(200);
        $postPayment->assertSee('Pembayaran Berhasil');
        $postPayment->assertSee('RMF-');

        // 4. Verify persisted in SQLite database (with encrypted NIK)
        $this->assertDatabaseHas('bookings', [
            'hotel_id'   => 'hotel-001',
            'guest_name' => 'Budi Santoso',
        ]);
        $savedBooking = Booking::where('hotel_id', 'hotel-001')->first();
        $this->assertNotNull($savedBooking);
        $this->assertEquals('1271012345678901', $savedBooking->no_ktp);
    }
}
