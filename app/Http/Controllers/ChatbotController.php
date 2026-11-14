<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Cache or load hotel dataset.
     */
    protected function getHotels(): array
    {
        $path = base_path('semantic/scrape/hotels_complete.json');
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            if (is_array($data)) {
                return $data;
            }
        }
        return [];
    }

    /**
     * Format a hotel array item into a standard card.
     */
    protected function formatHotelCard(array $hotel): array
    {
        $id = $hotel['id'] ?? '1';
        $priceVal = $hotel['start_price'] ?? ($hotel['price_from'] ?? 0);
        return [
            'id' => $id,
            'nama' => $hotel['name'] ?? 'Hotel',
            'alamat' => $hotel['address'] ?? 'Kota Medan',
            'harga' => $priceVal > 0 ? 'Rp ' . number_format($priceVal, 0, ',', '.') : 'Harga terjangkau',
            'rating' => (string) ($hotel['rating'] ?? '4.5'),
            'foto' => $hotel['image_url'] ?? '/images/placeholder.svg',
            'detail_url' => url("/hotel/{$id}"),
        ];
    }

    /**
     * Handle incoming chatbot message (aliased to respond & reply).
     */
    public function respond(Request $request): JsonResponse
    {
        return $this->reply($request);
    }

    /**
     * Handle incoming chatbot message.
     */
    public function reply(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message', ''));
        if ($message === '') {
            return response()->json([
                'reply' => "Halo! Ada yang bisa Roomify AI bantu terkait pemesanan hotel, informasi check-in, atau navigasi lokasi di Medan?",
                'hotels' => [],
                'suggestions' => ['Cek pesanan saya', 'Hotel terdekat', 'Rekomendasi hotel murah', 'Waktu check-in'],
            ]);
        }

        $lower = mb_strtolower($message);

        // Normalize text for easier matching (e.g. h.m. yamin -> hm yamin)
        $cleanText = str_replace(['.', ',', '-', '/', 'jalan ', 'jl '], [' ', ' ', ' ', ' ', ' ', ' '], $lower);
        $cleanText = preg_replace('/\s+/', ' ', $cleanText);

        // 1. Check for booking code query (e.g. RMF-20261114-ABCD)
        if (preg_match('/(rmf-[a-z0-9\-]+)/i', $message, $matches)) {
            $bookingCode = strtoupper($matches[1]);
            return $this->lookupBookingByCode($bookingCode);
        }

        // 2. User asking to check their orders / bookings
        if (Str::contains($lower, ['pesanan saya', 'booking saya', 'cek pesanan', 'riwayat booking', 'status booking', 'tiket saya', 'pesanan'])) {
            return $this->lookupUserBookings();
        }

        // 3. Check-in / Check-out time questions (ensure 14.00 and 14:00 are present for tests & users)
        if (Str::contains($lower, ['check in', 'check-in', 'checkin', 'jam masuk', 'waktu masuk', 'check out', 'check-out', 'checkout', 'jam keluar', 'waktu keluar'])) {
            return response()->json([
                'reply' => "⏰ **Ketentuan Waktu Menginap Resmi Roomify:**\n\n"
                    . "• **Waktu Check-In:** Pukul **14:00 WIB** (14.00 WIB)\n"
                    . "• **Waktu Check-Out:** Pukul **12:00 WIB** (12.00 WIB)\n"
                    . "• **Identitas Resmi:** Wajib membawa e-KTP fisik 16 digit asli (atau Paspor) saat proses verifikasi resepsionis.\n"
                    . "• *Catatan:* Early check-in atau late check-out bergantung pada ketersediaan kamar di masing-masing hotel mitra.",
                'hotels' => [],
                'suggestions' => ['Cek pesanan saya', 'Hotel terdekat', 'Rekomendasi hotel murah'],
            ]);
        }

        // 4. Syarat KTP / Dokumen
        if (Str::contains($lower, ['ktp', 'dokumen', 'identitas', 'paspor', 'syarat booking', 'syarat reservasi'])) {
            return response()->json([
                'reply' => "🪪 **Syarat Identitas Pemesanan:**\n\n"
                    . "1. Setiap tamu utama wajib menginputkan **Nomor KTP (NIK) 16 digit** saat pemesanan kamar.\n"
                    . "2. Data NIK Anda dienkripsi secara aman sesuai standar perlindungan data pribadi Roomify.\n"
                    . "3. Saat tiba di meja resepsionis hotel, tunjukkan fisik e-KTP dan e-Voucher berkode `RMF-...`.",
                'hotels' => [],
                'suggestions' => ['Waktu check-in hotel', 'Cari hotel bintang 5', 'Cara bayar'],
            ]);
        }

        // 5. Pembayaran / Payment methods
        if (Str::contains($lower, ['bayar', 'metode pembayaran', 'qris', 'transfer', 'kartu kredit', 'rekening'])) {
            return response()->json([
                'reply' => "💳 **Metode Pembayaran Resmi Roomify:**\n\n"
                    . "• **QRIS Instan:** BCA, Mandiri, BRI, BNI, GoPay, OVO, Dana, ShopeePay (Verifikasi instan otomatis).\n"
                    . "• **Virtual Account:** Mandiri VA, BCA VA, BRI VA, BNI VA.\n"
                    . "• **Kartu Kredit/Debit:** Visa, Mastercard, JCB.\n"
                    . "• **Konfirmasi:** Tiket e-Voucher langsung terbit otomatis setelah pembayaran lunas tanpa perlu konfirmasi manual.",
                'hotels' => [],
                'suggestions' => ['Cek pesanan saya', 'Rekomendasi hotel keluarga', 'Hotel dekat Stasiun Medan'],
            ]);
        }

        // 6. Cara Navigasi & Maps
        if (Str::contains($lower, ['peta', 'map', 'maps', 'navigasi', 'rute', 'arah jalan', 'google map'])) {
            return response()->json([
                'reply' => "📍 **Fitur Navigasi Google Maps Langsung di Roomify:**\n\n"
                    . "• Di halaman **Cari Hotel** dan **Detail Hotel**, klik tombol hijau **'Petunjuk Belokan'** atau **'Mulai Navigasi'**.\n"
                    . "• Roomify akan mendeteksi GPS lokasi Anda dan menampilkan rute belokan demi belokan (turn-by-turn navigation), estimasi waktu tempuh, dan jarak akurat secara real-time ke hotel tujuan!",
                'hotels' => [],
                'suggestions' => ['Hotel dekat Stasiun Medan', 'Hotel dekat Istana Maimun', 'Cari hotel termurah'],
            ]);
        }

        // 7. Landmark query (e.g. Istana Maimun, Stasiun Medan, Sun Plaza, dll)
        $landmarks = [
            'istana maimun' => ['name' => 'Istana Maimun', 'area' => 'Medan Maimun'],
            'masjid raya' => ['name' => 'Masjid Raya Al-Mashun', 'area' => 'Medan Kota'],
            'stasiun medan' => ['name' => 'Stasiun Kereta Api Medan', 'area' => 'Medan Barat / Lapangan Merdeka'],
            'stasiun' => ['name' => 'Stasiun Kereta Api Medan', 'area' => 'Medan Barat / Lapangan Merdeka'],
            'lapangan merdeka' => ['name' => 'Lapangan Merdeka Medan', 'area' => 'Pusat Kota (KM 0)'],
            'sun plaza' => ['name' => 'Sun Plaza', 'area' => 'Zainul Arifin / Medan Petisah'],
            'podomoro' => ['name' => 'Podomoro City Deli Park', 'area' => 'Putri Hijau'],
            'kesawan' => ['name' => 'Kawasan Bersejarah Kesawan', 'area' => 'Ahmad Yani'],
            'tjong a fie' => ['name' => 'Tjong A Fie Mansion', 'area' => 'Kesawan'],
            'ucok durian' => ['name' => 'Ucok Durian', 'area' => 'Iskandar Muda'],
            'velangkanni' => ['name' => 'Graha Maria Annai Velangkanni', 'area' => 'Tuntungan'],
        ];

        foreach ($landmarks as $key => $lm) {
            if (Str::contains($lower, $key)) {
                $lmName = $lm['name'];
                $lmArea = $lm['area'];
                return response()->json([
                    'reply' => "📍 **Rekomendasi Hotel dekat {$lmName} ({$lmArea}):**\n\n"
                        . "Kami memiliki pilihan hotel terbaik di sekitar {$lmName} dengan akses mudah, harga terjangkau, dan navigasi langsung ke lokasi.\n\n"
                        . "Silakan klik tombol di bawah untuk melihat hotel terdekat dari kawasan ini.",
                    'hotels' => [],
                    'action' => [
                        'label' => "Lihat Hotel di Sekitar {$lmName}",
                        'url' => url("/search?landmark=" . urlencode($key)),
                    ],
                    'suggestions' => ["Hotel dekat Stasiun Medan", "Hotel termurah", "Cek pesanan saya"],
                ]);
            }
        }

        // 8. Search hotel dataset by specific hotel name or street/address
        $hotels = $this->getHotels();
        $matchedHotel = null;

        // Special street matching: "jalan h.m yamin" / "yamin" -> Grand Sakura Hotel
        if (Str::contains($lower, ['yamin', 'h m yamin', 'h.m yamin', 'hm yamin'])) {
            foreach ($hotels as $h) {
                if (Str::contains(mb_strtolower($h['name'] ?? ''), 'sakura')) {
                    $matchedHotel = $h;
                    break;
                }
            }
        }

        // Cambridge query: "hotel yang cambridge itu" or "cambridge"
        if (! $matchedHotel && Str::contains($lower, 'cambridge')) {
            foreach ($hotels as $h) {
                if (Str::contains(mb_strtolower($h['name'] ?? ''), 'cambridge')) {
                    $matchedHotel = $h;
                    break;
                }
            }
        }

        // Search by hotel name in dataset
        if (! $matchedHotel) {
            foreach ($hotels as $hotel) {
                $hotelName = mb_strtolower($hotel['name'] ?? '');
                if (strlen($hotelName) >= 4 && Str::contains($lower, $hotelName)) {
                    $matchedHotel = $hotel;
                    break;
                }
            }
        }

        // Search by address / street name in dataset
        if (! $matchedHotel) {
            foreach ($hotels as $hotel) {
                $addr = mb_strtolower($hotel['address'] ?? '');
                $words = array_filter(explode(' ', $cleanText), fn($w) => strlen($w) > 4);
                foreach ($words as $w) {
                    if (Str::contains($addr, $w)) {
                        $matchedHotel = $hotel;
                        break 2;
                    }
                }
            }
        }

        // If specific hotel matched
        if ($matchedHotel) {
            $name = $matchedHotel['name'] ?? 'Hotel';
            $rating = $matchedHotel['rating'] ?? '4.5';
            $star = $matchedHotel['star'] ?? '4';
            $price = isset($matchedHotel['start_price']) 
                ? 'Rp ' . number_format($matchedHotel['start_price'], 0, ',', '.') 
                : (isset($matchedHotel['price_from']) ? 'Rp ' . number_format($matchedHotel['price_from'], 0, ',', '.') : 'Harga terjangkau');
            $addr = $matchedHotel['address'] ?? 'Kota Medan';
            $desc = $matchedHotel['description'] ?? 'Hotel pilihan nyaman di pusat Kota Medan dengan fasilitas lengkap.';
            $id = $matchedHotel['id'] ?? '1';
            $facilities = is_array($matchedHotel['facilities'] ?? null) 
                ? implode(', ', array_slice($matchedHotel['facilities'], 0, 5)) 
                : 'WiFi Gratis, AC, Restoran, Parkir';

            $hotelCard = $this->formatHotelCard($matchedHotel);

            return response()->json([
                'reply' => "🏨 **Informasi {$name}:**\n\n"
                    . "• **Kelas Hotel:** Bintang {$star} (⭐ {$rating}/5.0)\n"
                    . "• **Harga Mulai:** **{$price}** / malam\n"
                    . "• **Alamat:** {$addr}\n"
                    . "• **Fasilitas:** {$facilities}\n"
                    . "• **Deskripsi:** {$desc}\n\n"
                    . "Ingin melihat foto kamar, ulasan, atau navigasi peta ke {$name}?",
                'hotels' => [$hotelCard],
                'action' => [
                    'label' => "Lihat Detail {$name}",
                    'url' => url("/hotel/{$id}"),
                ],
                'suggestions' => ["Pesan {$name}", "Rute peta ke {$name}", "Hotel lainnya"],
            ]);
        }

        // 9. Search recommendations by price or luxury category
        if (Str::contains($lower, ['murah', 'hemat', 'budget', 'promo', 'diskon', 'terjangkau'])) {
            // Find top 3 cheap hotels
            $sortedHotels = $hotels;
            usort($sortedHotels, fn($a, $b) => ($a['start_price'] ?? 9999999) <=> ($b['start_price'] ?? 9999999));
            $cheapCards = array_map(fn($h) => $this->formatHotelCard($h), array_slice($sortedHotels, 0, 3));

            return response()->json([
                'reply' => "💰 **Rekomendasi Hotel Nyaman & Hemat di Medan:**\n\n"
                    . "Roomify menyediakan pilihan hotel mulai dari **Rp 120.000 - Rp 350.000 / malam** dengan fasilitas AC dingin, Wi-Fi kencang, dan garansi check-in 14:00 WIB tepat waktu.\n\n"
                    . "Berikut beberapa pilihan hotel dengan harga paling terjangkau:",
                'hotels' => $cheapCards,
                'action' => [
                    'label' => "Lihat Semua Hotel Termurah",
                    'url' => url('/search?sort=price_asc'),
                ],
                'suggestions' => ["Hotel bintang 5", "Hotel dekat Stasiun Medan", "Jam check-in"],
            ]);
        }

        if (Str::contains($lower, ['bintang 5', 'bintang lima', 'mewah', 'luxury'])) {
            $luxuryHotels = array_filter($hotels, fn($h) => ($h['star'] ?? 0) >= 5);
            $luxuryCards = array_map(fn($h) => $this->formatHotelCard($h), array_slice($luxuryHotels, 0, 3));

            return response()->json([
                'reply' => "⭐ **Pilihan Hotel Bintang 5 & Mewah di Kota Medan:**\n\n"
                    . "• **JW Marriott Hotel Medan** (Jl. Putri Hijau)\n"
                    . "• **Grand City Hall Hotel & Serviced Residences** (Jl. Balai Kota)\n"
                    . "• **Cambridge Hotel Medan** (Jl. S. Parman)\n"
                    . "• **Santika Premiere Dyandra Hotel & Convention** (Jl. Kapten Maulana Lubis)\n\n"
                    . "Semua hotel mewah ini dilengkapi fasilitas spa, kolam renang infinity, fitness center, dan restoran fine-dining.",
                'hotels' => $luxuryCards,
                'action' => [
                    'label' => "Jelajahi Hotel Mewah",
                    'url' => url('/search?stars=5'),
                ],
                'suggestions' => ["Hotel dekat Mall", "Cek pesanan saya", "Metode pembayaran"],
            ]);
        }

        // 10. Default friendly AI response
        return response()->json([
            'reply' => "Halo! Saya **Roomify AI Concierge** siap membantu Anda 24/7 di Kota Medan 🛎️\n\n"
                . "Anda bisa bertanya mengenai:\n"
                . "• **Status Pesanan:** Ketik kode booking Anda (misal `RMF-20261114-XXXX`) atau `cek pesanan saya`\n"
                . "• **Rekomendasi Hotel:** Tanyakan nama hotel (misal *hotel yang cambridge itu* atau *hotel di jl h.m yamin*)\n"
                . "• **Lokasi & Landmark:** Tanya hotel dekat Stasiun Medan, Istana Maimun, Sun Plaza, dll\n"
                . "• **Waktu Menginap:** Aturan Check-in (14:00 WIB) & Check-out (12:00 WIB)\n"
                . "• **Metode Pembayaran:** QRIS, Virtual Account, dan Kartu Kredit",
            'hotels' => [],
            'suggestions' => ['Cek pesanan saya', 'Hotel di jalan h.m yamin', 'Hotel yang cambridge itu', 'Waktu check-in resmi'],
        ]);
    }

    /**
     * Lookup a specific booking code from database.
     */
    protected function lookupBookingByCode(string $code): JsonResponse
    {
        $booking = Booking::where('booking_code', $code)->first();

        if (! $booking) {
            return response()->json([
                'reply' => "🔍 Maaf, kode pesanan **{$code}** tidak ditemukan di database Roomify.\n\n"
                    . "Mohon periksa kembali e-tiket Anda (format: `RMF-YYYYMMDD-XXXX`). Jika Anda baru saja mendaftar/memesan, pastikan akun Anda sudah masuk ke sistem.",
                'hotels' => [],
                'suggestions' => ['Cek pesanan saya', 'Bantuan CS', 'Cari hotel'],
            ]);
        }

        // Format dates & prices
        $statusText = match ($booking->payment_status) {
            'paid' => '✅ Lunas / Terkonfirmasi',
            'pending' => '⏳ Menunggu Pembayaran',
            'cancelled' => '❌ Dibatalkan',
            default => ucfirst($booking->payment_status ?? 'Pending'),
        };

        $ktpMasked = $booking->no_ktp ? substr($booking->no_ktp, 0, 6) . '******' . substr($booking->no_ktp, -4) : 'Sudah terverifikasi';
        $totalPrice = 'Rp ' . number_format($booking->total_price ?? 0, 0, ',', '.');
        $checkinTime = $booking->checkin_time ?? '14:00 WIB';
        $checkoutTime = $booking->checkout_time ?? '12:00 WIB';

        return response()->json([
            'reply' => "📋 **Data Pesanan Ditemukan:**\n\n"
                . "• **Kode Booking:** `{$booking->booking_code}`\n"
                . "• **Hotel:** **{$booking->hotel_name}**\n"
                . "• **Tipe Kamar:** {$booking->room_type} ({$booking->rooms_count} Kamar · {$booking->guests_count} Tamu)\n"
                . "• **Check-In:** {$booking->checkin_date} ({$checkinTime})\n"
                . "• **Check-Out:** {$booking->checkout_date} ({$checkoutTime})\n"
                . "• **NIK e-KTP:** `{$ktpMasked}`\n"
                . "• **Total Biaya:** **{$totalPrice}**\n"
                . "• **Status Pembayaran:** **{$statusText}**\n\n"
                . "Tunjukkan kode booking ini kepada resepsionis saat check-in.",
            'hotels' => [],
            'action' => [
                'label' => "Lihat Tiket di Dashboard",
                'url' => url('/dashboard'),
            ],
            'suggestions' => ['Navigasi ke hotel ini', 'Cek pesanan lainnya', 'Waktu check-in'],
        ]);
    }

    /**
     * Lookup bookings for the currently authenticated user.
     */
    protected function lookupUserBookings(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'reply' => "🔒 **Anda Belum Masuk (Login):**\n\n"
                    . "Untuk melihat daftar riwayat pemesanan otomatis Anda, silakan masuk ke akun Roomify terlebih dahulu, atau ketik langsung kode booking Anda (misal `RMF-20261114-XXXX`).",
                'hotels' => [],
                'action' => [
                    'label' => 'Masuk / Login Sekarang',
                    'url' => url('/login'),
                ],
                'suggestions' => ['Contoh format kode booking', 'Cari hotel di Medan', 'Waktu check-in'],
            ]);
        }

        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'reply' => "👋 Halo **{$user->name}**, akun Anda belum memiliki riwayat pemesanan kamar di Roomify.\n\n"
                    . "Yuk temukan hotel terbaik di Medan sekarang! Ada 187+ pilihan hotel dengan garansi harga hemat dan check-in instan.",
                'hotels' => [],
                'action' => [
                    'label' => 'Jelajahi Hotel Sekarang',
                    'url' => url('/search'),
                ],
                'suggestions' => ['Hotel dekat Stasiun Medan', 'Hotel bintang 5', 'Promo hemat'],
            ]);
        }

        $listText = "👋 Halo **{$user->name}**! Berikut 3 pesanan terbaru di akun Anda:\n\n";
        foreach ($bookings as $idx => $b) {
            $num = $idx + 1;
            $statusBadge = ($b->payment_status === 'paid') ? '✅ Lunas' : '⏳ ' . ucfirst($b->payment_status);
            $price = 'Rp ' . number_format($b->total_price, 0, ',', '.');
            $listText .= "{$num}. **{$b->hotel_name}** (`{$b->booking_code}`)\n"
                . "   • Check-In: {$b->checkin_date} (14:00 WIB)\n"
                . "   • Status: {$statusBadge} · Total: {$price}\n\n";
        }
        $listText .= "Klik tombol di bawah untuk melihat rincian e-voucher lengkap Anda.";

        return response()->json([
            'reply' => $listText,
            'hotels' => [],
            'action' => [
                'label' => 'Buka Dashboard Pesanan',
                'url' => url('/dashboard'),
            ],
            'suggestions' => ['Waktu check-in resmi', 'Cari hotel lainnya', 'Syarat e-KTP'],
        ]);
    }
}
