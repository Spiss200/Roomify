<?php

namespace Tests\Feature;

use Tests\TestCase;

class SearchFeatureTest extends TestCase
{
    public function test_filter_bintang_5_hanya_hotel_bintang_5(): void
    {
        $hotels = $this->get('/search?rating=5')->assertStatus(200)->viewData('hotels');
        $this->assertNotEmpty($hotels);
        foreach ($hotels as $h) {
            $this->assertGreaterThanOrEqual(5, (int) $h['bintang']);
        }
    }

    public function test_filter_harga_maksimal(): void
    {
        $hotels = $this->get('/search?price_max=200000')->assertStatus(200)->viewData('hotels');
        $this->assertNotEmpty($hotels);
        foreach ($hotels as $h) {
            $this->assertLessThanOrEqual(200000, $h['harga']);
        }
    }

    public function test_filter_harga_minimal(): void
    {
        $hotels = $this->get('/search?price_min=500000')->assertStatus(200)->viewData('hotels');
        $this->assertNotEmpty($hotels);
        foreach ($hotels as $h) {
            $this->assertGreaterThanOrEqual(500000, $h['harga']);
        }
    }

    public function test_suggest_mengembalikan_nama_hotel(): void
    {
        $this->getJson('/suggest?q=sak')->assertStatus(200)->assertJsonFragment(['Grand Sakura Hotel']);
    }

    public function test_suggest_terlalu_pendek_kosong(): void
    {
        $this->getJson('/suggest?q=sa')->assertStatus(200)->assertExactJson([]);
    }

    public function test_chatbot_jadwal_checkin(): void
    {
        $this->postJson('/api/ai-chat', ['message' => 'jam check in'])
            ->assertStatus(200)
            ->assertJsonPath('hotels', [])
            ->assertSee('14.00');
    }

    public function test_chatbot_hotel_murah_mengembalikan_kartu(): void
    {
        $res = $this->postJson('/api/ai-chat', ['message' => 'hotel murah'])->assertStatus(200);
        $this->assertNotEmpty($res->json('hotels'));
    }

    public function test_chatbot_pesan_kosong_tidak_error(): void
    {
        $this->postJson('/api/ai-chat', ['message' => ''])
            ->assertStatus(200)
            ->assertJsonStructure(['reply', 'hotels']);
    }
}