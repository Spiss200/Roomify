<?php

namespace App\Http\Controllers;

use App\Services\FusekiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(protected FusekiClient $fuseki) {}

    public function respond(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message', ''));
        if ($message === '') {
            return response()->json([
                'reply' => 'Ada yang bisa saya bantu untuk mencari hotel di Kota Medan?',
                'hotels' => [],
            ]);
        }

        $lower = mb_strtolower($message);

        // 1. Check-in / Check-out policy question
        if (str_contains($lower, 'check in') || str_contains($lower, 'check-in') || str_contains($lower, 'checkout') || str_contains($lower, 'check out') || str_contains($lower, 'jam berapa')) {
            return response()->json([
                'reply' => "⏰ **Jadwal Check-in & Check-out Resmi Roomify:**\n\n• **Check-in:** Mulai pukul **14.00 WIB** (siang)\n• **Check-out:** Maksimal pukul **12.00 WIB** (siang)\n\nHarap siapkan KTP (16 digit) dan tunjukkan Kode Booking unik Anda yang ada di menu Akun saat tiba di resepsionis hotel.",
                'hotels' => [],
            ]);
        }

        // 2. KTP / Syarat booking question
        if (str_contains($lower, 'ktp') || str_contains($lower, 'syarat') || str_contains($lower, 'dokumen') || str_contains($lower, 'daftar')) {
            return response()->json([
                'reply' => "📋 **Ketentuan Pemesanan Hotel di Roomify:**\n\n1. Pemesan wajib mengisi **Nomor KTP (16 digit)** yang valid untuk verifikasi tamu.\n2. Setelah pembayaran berhasil, pesanan langsung terbit dengan kode unik resmi (contoh: `RMF-YYYYMMDD-XXXX`).\n3. Semua bukti transaksi dan e-tiket tersimpan aman di Dashboard Akun Anda.",
                'hotels' => [],
            ]);
        }

        // 3. Payment methods question
        if (str_contains($lower, 'bayar') || str_contains($lower, 'pembayaran') || str_contains($lower, 'qris') || str_contains($lower, 'transfer')) {
            return response()->json([
                'reply' => "💳 **Metode Pembayaran Tersedia:**\n\n• **QRIS Real-time:** Dukung BCA Mobile, GoPay, OVO, Dana, ShopeePay, LinkAja.\n• **Virtual Account Bank:** BCA, Mandiri, BRI, BNI.\n\nKonfirmasi pembayaran instan dan pesanan langsung aktif tanpa perlu upload bukti manual.",
                'hotels' => [],
            ]);
        }

        // 4. Hotel Search by landmark / keyword / location
        $dataset = $this->loadDataset();
        $matchedHotels = [];

        // Landmark check
        $detectedLandmark = null;
        foreach (FusekiClient::LANDMARKS as $lm) {
            if (str_contains($lower, strtolower($lm['name']))) {
                $detectedLandmark = $lm;
                break;
            }
        }

        if ($detectedLandmark) {
            // Find closest hotels to this landmark
            $withDist = [];
            foreach ($dataset as $h) {
                if (!empty($h['latitude']) && !empty($h['longitude'])) {
                    $d = $this->haversineKm(
                        (float)$detectedLandmark['lat'],
                        (float)$detectedLandmark['lon'],
                        (float)$h['latitude'],
                        (float)$h['longitude']
                    );
                    $withDist[] = array_merge($h, ['distance_km' => $d]);
                }
            }
            usort($withDist, fn($a, $b) => $a['distance_km'] <=> $b['distance_km']);
            $matchedHotels = array_slice($withDist, 0, 3);

            $reply = "Berikut rekomendasi hotel terdekat dari **{$detectedLandmark['name']}**:";
            return response()->json([
                'reply'  => $reply,
                'hotels' => $this->formatHotelCards($matchedHotels),
            ]);
        }

        // Specific hotel searches (e.g. Cambridge, Grand Sakura, JW Marriott, Adimulia)
        if (str_contains($lower, 'cambridge')) {
            $filtered = array_filter($dataset, fn($h) => str_contains(strtolower($h['name']), 'cambridge'));
            return response()->json([
                'reply'  => "Saya menemukan **Cambridge Hotel Medan** bintang 5 dengan akses langsung ke Cambridge City Square:",
                'hotels' => $this->formatHotelCards(array_slice($filtered, 0, 2)),
            ]);
        }

        if (str_contains($lower, 'yamin') || str_contains($lower, 'sakura')) {
            $filtered = array_filter($dataset, fn($h) => str_contains(strtolower($h['name'] . ' ' . ($h['address'] ?? '')), 'yamin'));
            return response()->json([
                'reply'  => "Berikut hotel di kawasan **Jalan Prof. H.M. Yamin**, termasuk **Grand Sakura Hotel**:",
                'hotels' => $this->formatHotelCards(array_slice($filtered, 0, 3)),
            ]);
        }

        // Luxury / 5-star check
        if (str_contains($lower, 'bintang 5') || str_contains($lower, 'mewah') || str_contains($lower, 'luxury')) {
            $filtered = array_filter($dataset, fn($h) => (int)($h['star'] ?? 0) >= 5);
            usort($filtered, fn($a, $b) => ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0));
            return response()->json([
                'reply'  => "Berikut pilihan hotel bintang 5 termewah dengan rating tertinggi di Kota Medan:",
                'hotels' => $this->formatHotelCards(array_slice($filtered, 0, 3)),
            ]);
        }

        // Cheap / budget check
        if (str_contains($lower, 'murah') || str_contains($lower, 'hemat') || str_contains($lower, 'budget') || str_contains($lower, '300') || str_contains($lower, '200')) {
            $filtered = array_filter($dataset, fn($h) => (int)($h['start_price'] ?? 0) > 0 && (int)($h['start_price'] ?? 0) <= 350000);
            usort($filtered, fn($a, $b) => ($a['start_price'] ?? 0) <=> ($b['start_price'] ?? 0));
            return response()->json([
                'reply'  => "Berikut hotel nyaman & terjangkau di bawah Rp 350.000/malam di Medan:",
                'hotels' => $this->formatHotelCards(array_slice($filtered, 0, 3)),
            ]);
        }

        // General search across names, addresses, and facilities
        $cleanWords = array_diff(
            preg_split('/[\s,]+/', $lower),
            ['hotel', 'penginapan', 'yang', 'ada', 'di', 'medan', 'kota', 'cari', 'carikan', 'tolong', 'apa', 'saja', 'bisa', 'dong', 'nih']
        );

        if (!empty($cleanWords)) {
            $filtered = array_filter($dataset, function ($h) use ($cleanWords) {
                $str = strtolower(($h['name'] ?? '') . ' ' . ($h['address'] ?? '') . ' ' . ($h['description'] ?? ''));
                foreach ($cleanWords as $w) {
                    if (str_contains($str, $w)) return true;
                }
                return false;
            });

            if (!empty($filtered)) {
                return response()->json([
                    'reply'  => "Saya menemukan beberapa hotel yang sesuai dengan pencarian Anda:",
                    'hotels' => $this->formatHotelCards(array_slice($filtered, 0, 3)),
                ]);
            }
        }

        // Fallback default recommendations
        $popular = array_slice($dataset, 0, 3);
        return response()->json([
            'reply' => "Saya siap membantu Anda menjelajahi 187 hotel di Medan. Anda bisa bertanya seperti:\n\n• *\"Hotel dekat Istana Maimun\"*\n• *\"Hotel yang Cambridge itu\"*\n• *\"Hotel di Jalan H.M. Yamin\"*\n• *\"Hotel murah di bawah 300 ribu\"*\n• *\"Jadwal check-in dan syarat KTP\"*\n\nBerikut beberapa hotel populer di Medan:",
            'hotels' => $this->formatHotelCards($popular),
        ]);
    }

    protected function formatHotelCards(array $hotels): array
    {
        $out = [];
        foreach ($hotels as $h) {
            $dist = isset($h['distance_km'])
                ? ($h['distance_km'] < 1 ? round($h['distance_km'] * 1000) . ' m' : number_format($h['distance_km'], 1, ',', '.') . ' km')
                : null;

            $out[] = [
                'id'       => $h['id'],
                'nama'     => $h['name'],
                'alamat'   => $h['address'] ?? 'Medan, Sumatera Utara',
                'harga'    => 'Rp ' . number_format((int)($h['start_price'] ?? 0), 0, ',', '.'),
                'star'     => (int)($h['star'] ?? 3),
                'rating'   => number_format((float)($h['rating'] ?? 4.5), 1),
                'foto'     => $h['image_url'] ?? '/images/placeholder.svg',
                'jarak'    => $dist,
                'detail_url' => route('hotel.detail', ['id' => $h['id']]),
            ];
        }
        return $out;
    }

    protected function loadDataset(): array
    {
        $path = base_path('semantic/scrape/hotels_complete.json');
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?: [];
        }
        return [];
    }

    protected function haversineKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $r = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
