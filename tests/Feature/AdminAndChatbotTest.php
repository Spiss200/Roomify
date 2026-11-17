<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAndChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Admin Console');
    }

    public function test_chatbot_returns_hotels_near_usu_spatially(): void
    {
        $response = $this->postJson('/api/ai-chat', [
            'message' => 'hotel daerah USU'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'reply',
            'hotels',
            'location'
        ]);
        $reply = $response->json('reply');
        $this->assertStringContainsString('USU', $reply);
        $this->assertNotEmpty($response->json('hotels'));

        // Verify location structure and maps url
        $location = $response->json('location');
        $this->assertNotNull($location);
        $this->assertStringContainsString('USU', $location['name']);
        $this->assertArrayHasKey('maps_url', $location);
        $this->assertArrayHasKey('directions_url', $location);

        // Verify hotel cards have maps/directions URLs
        $firstHotel = $response->json('hotels.0');
        $this->assertArrayHasKey('maps_url', $firstHotel);
        $this->assertArrayHasKey('directions_url', $firstHotel);
    }

    public function test_chatbot_returns_cambridge_hotel_with_location_and_maps(): void
    {
        $response = $this->postJson('/api/ai-chat', [
            'message' => 'hotel yang cambridge itu'
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('Cambridge', $response->json('reply'));
        $this->assertNotEmpty($response->json('hotels'));
        $firstHotel = $response->json('hotels.0');
        $this->assertStringContainsString('Cambridge', $firstHotel['nama']);
        $this->assertNotNull($firstHotel['directions_url']);
    }

    public function test_payment_show_page_renders_with_theme_and_clock_bar(): void
    {
        $response = $this->withSession([
            'booking' => [
                'hotel_id' => '1',
                'hotel_name' => 'Cambridge Hotel Medan',
                'room_type' => 'Deluxe Room',
                'room_price' => 750000,
                'checkin' => '2026-11-20',
                'checkout' => '2026-11-21',
                'nights' => 1,
                'guests' => 2,
                'name' => 'Budi Pamungkas',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
                'no_ktp' => '1271012345678901',
                'subtotal' => 750000,
            ]
        ])->get(route('payment.show'));

        $response->assertStatus(200);
        $response->assertSee('Transfer Bank BCA');
        $response->assertSee('PT Roomify Indonesia');
        $response->assertSee('WAKTU SISTEM');
        $response->assertSee('14:00 WIB');
        $response->assertSee('12:00 WIB');
    }

    public function test_chatbot_resolves_local_abbreviations_and_slang(): void
    {
        // 1. Test abbreviation "gatsu" -> Gatot Subroto
        $resGatsu = $this->postJson('/api/ai-chat', ['message' => 'hotel dkt gatsu']);
        $resGatsu->assertStatus(200);
        $this->assertStringContainsString('Gatot Subroto', $resGatsu->json('reply'));

        // 2. Test abbreviation "poci" -> Podomoro
        $resPoci = $this->postJson('/api/ai-chat', ['message' => 'hotel dkt poci']);
        $resPoci->assertStatus(200);
        $this->assertStringContainsString('Podomoro', $resPoci->json('reply'));

        // 3. Test abbreviation "jw" -> JW Marriott
        $resJw = $this->postJson('/api/ai-chat', ['message' => 'hotel jw']);
        $resJw->assertStatus(200);
        $this->assertStringContainsString('JW Marriott', $resJw->json('reply'));
    }
}
