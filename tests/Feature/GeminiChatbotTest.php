<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiChatbotTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_gemini_concierge_hydrates_verified_hotels_and_location(): void
    {
        config([
            'services.gemini.enabled' => true,
            'services.gemini.api_key' => 'AQ.test-key',
            'services.gemini.model' => 'gemini-3-flash-preview',
            'services.gemini.fallback_models' => ['gemini-3.1-flash-lite-preview'],
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'reply' => 'Berikut pilihan hotel terbaik dekat **USU** dengan harga ramah kantong.',
                                'hotel_ids' => ['hotel-002', 'fake-hallucinated-999'],
                                'location_key' => 'usu',
                                'action_type' => 'search',
                                'action_label' => 'Lihat Hotel Sekitar USU',
                                'action_param' => 'usu',
                                'suggestions' => ['Hotel bintang 5', 'Jam check-in'],
                            ]),
                        ]],
                    ],
                ]],
            ], 200),
        ]);

        $response = $this->postJson('/api/ai-chat', [
            'message' => 'Carikan hotel bagus dekat USU dibawah 900rb',
            'history' => [
                ['role' => 'user', 'text' => 'Halo'],
                ['role' => 'model', 'text' => 'Halo! Ada yang bisa saya bantu?'],
            ],
            'context' => [
                'page_path' => '/search',
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('engine', 'gemini')
            ->assertJsonPath('model', 'gemini-3-flash-preview')
            ->assertJsonPath('location.name', 'Universitas Sumatera Utara (USU) & Padang Bulan')
            ->assertJsonPath('action.label', 'Lihat Hotel Sekitar USU')
            ->assertJsonCount(1, 'hotels')
            ->assertJsonPath('hotels.0.id', 'hotel-002')
            ->assertJsonPath('hotels.0.nama', 'Adimulia Hotel Medan');

        Http::assertSent(function (Request $request): bool {
            return $request->hasHeader('x-goog-api-key', 'AQ.test-key')
                && str_contains($request->url(), 'gemini-3-flash-preview:generateContent')
                && str_contains($request->body(), 'candidate_hotels');
        });
    }

    public function test_gemini_concierge_falls_back_to_secondary_model_on_503(): void
    {
        config([
            'services.gemini.enabled' => true,
            'services.gemini.api_key' => 'AQ.test-key',
            'services.gemini.model' => 'gemini-3-flash-preview',
            'services.gemini.fallback_models' => ['gemini-3.1-flash-lite-preview'],
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent' => Http::response([
                'error' => ['code' => 503, 'message' => 'High demand'],
            ], 503),
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'reply' => 'Rekomendasi dari model cadangan untuk **JW Marriott Hotel Medan**.',
                                'hotel_ids' => ['hotel-014'],
                                'location_key' => 'podomoro',
                                'action_type' => 'hotel',
                                'action_label' => 'Detail JW Marriott',
                                'action_param' => 'hotel-014',
                                'suggestions' => ['Cek pesanan saya'],
                            ]),
                        ]],
                    ],
                ]],
            ], 200),
        ]);

        $response = $this->postJson('/api/ai-chat', [
            'message' => 'Rekomendasi hotel mewah dekat Podomoro',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('engine', 'gemini')
            ->assertJsonPath('model', 'gemini-3.1-flash-lite-preview')
            ->assertJsonPath('hotels.0.id', 'hotel-014')
            ->assertJsonPath('action.url', url('/hotel/hotel-014'));
    }

    public function test_chatbot_falls_back_to_local_engine_when_all_gemini_models_fail(): void
    {
        config([
            'services.gemini.enabled' => true,
            'services.gemini.api_key' => 'AQ.test-key',
            'services.gemini.model' => 'gemini-3-flash-preview',
            'services.gemini.fallback_models' => [],
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::failedConnection(),
        ]);

        $response = $this->postJson('/api/ai-chat', [
            'message' => 'hotel dekat USU dibawah 500rb',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('engine', 'local')
            ->assertJsonPath('location.name', 'Universitas Sumatera Utara (USU) & Padang Bulan');

        $this->assertNotEmpty($response->json('hotels'));
    }

    public function test_booking_code_lookup_bypasses_gemini_and_reads_database(): void
    {
        config([
            'services.gemini.enabled' => true,
            'services.gemini.api_key' => 'AQ.test-key',
        ]);

        Http::preventStrayRequests();
        Http::fake();

        $user = User::factory()->create();
        Booking::create([
            'booking_code' => 'RMF-20261125-TEST',
            'user_id' => $user->id,
            'hotel_id' => 'hotel-014',
            'hotel_name' => 'JW Marriott Hotel Medan',
            'room_type' => 'Deluxe Room',
            'room_price' => 1500000,
            'checkin_date' => '2026-12-01',
            'checkin_time' => '14:00 WIB',
            'checkout_date' => '2026-12-02',
            'checkout_time' => '12:00 WIB',
            'nights' => 1,
            'guests' => 2,
            'guest_name' => 'Andi Wijaya',
            'guest_email' => 'andi@example.com',
            'guest_phone' => '08123456789',
            'no_ktp' => '1271012345678901',
            'subtotal' => 1500000,
            'tax' => 165000,
            'total_price' => 1665000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
        ]);

        $response = $this->postJson('/api/ai-chat', [
            'message' => 'Cek kode RMF-20261125-TEST',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('engine', 'local')
            ->assertSee('JW Marriott Hotel Medan')
            ->assertSee('RMF-20261125-TEST');

        Http::assertNothingSent();
    }
}
