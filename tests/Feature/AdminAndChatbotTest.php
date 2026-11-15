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
            'hotels'
        ]);
        $reply = $response->json('reply');
        $this->assertStringContainsString('USU', $reply);
        $this->assertNotEmpty($response->json('hotels'));
    }
}
