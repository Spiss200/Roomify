<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\FusekiClient;
use App\Services\GeminiConciergeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function __construct(
        protected FusekiClient $fuseki,
        protected GeminiConciergeService $gemini,
    ) {}

    /**
     * Geographic database of Medan areas, campuses, landmarks and streets.
     */
    protected const KNOWN_LOCATIONS = [
        'usu' => [
            'name' => 'Universitas Sumatera Utara (USU) & Padang Bulan',
            'lat' => 3.5651,
            'lng' => 98.6572,
            'desc' => 'Kawasan Kampus USU, Jl. Dr. Mansyur, dan Padang Bulan',
        ],
        'padang bulan' => [
            'name' => 'Padang Bulan & Sekitar USU',
            'lat' => 3.5651,
            'lng' => 98.6572,
            'desc' => 'Kawasan Padang Bulan, Medan Baru',
        ],
        'dr mansyur' => [
            'name' => 'Jalan Dr. Mansyur (USU)',
            'lat' => 3.5651,
            'lng' => 98.6572,
            'desc' => 'Pusat kuliner mahasiswa dan gerbang utama USU',
        ],
        'unimed' => [
            'name' => 'Universitas Negeri Medan (UNIMED)',
            'lat' => 3.5932,
            'lng' => 98.7188,
            'desc' => 'Kawasan Jl. Willem Iskandar & Kampus UNIMED',
        ],
        'kualanamu' => [
            'name' => 'Bandara Internasional Kualanamu (KNO)',
            'lat' => 3.5995,
            'lng' => 98.8330,
            'desc' => 'Akses transit bandara & kereta bandara',
        ],
        'stasiun' => [
            'name' => 'Stasiun Kereta Api Medan (KM 0)',
            'lat' => 3.5928,
            'lng' => 98.6748,
            'desc' => 'Pusat kota Medan, Lapangan Merdeka & Kereta Bandara',
        ],
        'lapangan merdeka' => [
            'name' => 'Lapangan Merdeka (Titik Nol Medan)',
            'lat' => 3.5928,
            'lng' => 98.6748,
            'desc' => 'Pusat kota Medan bersejarah',
            'landmark_id' => 'lm-merdeka',
        ],
        'ringroad' => [
            'name' => 'Ring Road / Jl. Gagak Hitam',
            'lat' => 3.5685,
            'lng' => 98.6250,
            'desc' => 'Kawasan Ring Road Medan Sunggal / Selayang',
        ],
        'setiabudi' => [
            'name' => 'Jalan Setia Budi',
            'lat' => 3.5510,
            'lng' => 98.6420,
            'desc' => 'Kawasan kuliner & hunian Setia Budi',
        ],
        'setia budi' => [
            'name' => 'Jalan Setia Budi',
            'lat' => 3.5510,
            'lng' => 98.6420,
            'desc' => 'Kawasan kuliner & hunian Setia Budi',
        ],
        'manhattan' => [
            'name' => 'Manhattan Times Square & Gatot Subroto',
            'lat' => 3.5890,
            'lng' => 98.6360,
            'desc' => 'Pusat perbelanjaan Jl. Gatot Subroto Medan',
            'landmark_id' => 'lm-manhattan',
        ],
        'gatot subroto' => [
            'name' => 'Jalan Gatot Subroto Medan',
            'lat' => 3.5890,
            'lng' => 98.6360,
            'desc' => 'Akses arteri utama Medan - Binjai',
        ],
        'cambridge city square' => [
            'name' => 'Cambridge City Square & Jl. S. Parman',
            'lat' => 3.5855,
            'lng' => 98.6650,
            'desc' => 'Kawasan bisnis Medan Petisah / Medan Baru',
        ],
        'sun plaza' => [
            'name' => 'Sun Plaza Medan & Jl. KH Zainul Arifin',
            'lat' => 3.5857,
            'lng' => 98.6722,
            'desc' => 'Pusat perbelanjaan utama & Kampung Keling / Madras',
            'landmark_id' => 'lm-sunplaza',
        ],
        'podomoro' => [
            'name' => 'Podomoro City Deli Park & Jl. Putri Hijau',
            'lat' => 3.5990,
            'lng' => 98.6745,
            'desc' => 'Pusat gaya hidup & hotel mewah Jl. Putri Hijau',
        ],
        'istana maimun' => [
            'name' => 'Istana Maimun',
            'lat' => 3.5752,
            'lng' => 98.6836,
            'desc' => 'Ikon warisan Kesultanan Deli di Medan Maimun',
            'landmark_id' => 'lm-maimun',
        ],
        'masjid raya' => [
            'name' => 'Masjid Raya Al-Mashun',
            'lat' => 3.5746,
            'lng' => 98.6875,
            'desc' => 'Masjid bersejarah ikonik Kota Medan',
            'landmark_id' => 'lm-mashun',
        ],
        'kesawan' => [
            'name' => 'Kawasan Bersejarah Kesawan & Tjong A Fie',
            'lat' => 3.5905,
            'lng' => 98.6782,
            'desc' => 'Pusat kota tua Kesawan & Jl. Ahmad Yani',
            'landmark_id' => 'lm-tjong',
        ],
        'tjong a fie' => [
            'name' => 'Tjong A Fie Mansion',
            'lat' => 3.5905,
            'lng' => 98.6782,
            'desc' => 'Rumah cagar budaya di Kesawan',
            'landmark_id' => 'lm-tjong',
        ],
        'ucok durian' => [
            'name' => 'Ucok Durian Medan (Jl. KH Wahid Hasyim)',
            'lat' => 3.5768,
            'lng' => 98.6575,
            'desc' => 'Destinasi kuliner durian paling populer di Medan',
        ],
        'velangkanni' => [
            'name' => 'Graha Maria Annai Velangkanni',
            'lat' => 3.5492,
            'lng' => 98.5992,
            'desc' => 'Gereja arsitektur Indo-Mughal di Medan Tuntungan',
            'landmark_id' => 'lm-velangkanni',
        ],
        'cemara asri' => [
            'name' => 'Komp. Cemara Asri & Maha Vihara Maitreya',
            'lat' => 3.6300,
            'lng' => 98.7050,
            'desc' => 'Vihara terbesar dan pusat kuliner Cemara',
            'landmark_id' => 'lm-vihara',
        ],
        'yamin' => [
            'name' => 'Jalan Profesor H.M. Yamin',
            'lat' => 3.5960,
            'lng' => 98.6850,
            'desc' => 'Kawasan Perintis Kemerdekaan & Stasiun Medan',
        ],
        'h m yamin' => [
            'name' => 'Jalan Profesor H.M. Yamin',
            'lat' => 3.5960,
            'lng' => 98.6850,
            'desc' => 'Kawasan Perintis Kemerdekaan & Grand Sakura Hotel',
        ],
        'hm yamin' => [
            'name' => 'Jalan Profesor H.M. Yamin',
            'lat' => 3.5960,
            'lng' => 98.6850,
            'desc' => 'Kawasan Perintis Kemerdekaan & Grand Sakura Hotel',
        ],
    ];

    /**
     * Dictionary mapping for abbreviations, acronyms, and slang.
     */
    protected const ABBREVIATION_MAP = [
        'usu' => 'usu',
        'unimed' => 'unimed',
        'umsu' => 'umsu',
        'kno' => 'kualanamu',
        'bandara' => 'kualanamu',
        'stas' => 'stasiun medan',
        'stasiun' => 'stasiun medan',
        'rr' => 'ringroad',
        'ring road' => 'ringroad',
        'gatsu' => 'gatot subroto',
        'setbud' => 'setia budi',
        'setbudi' => 'setia budi',
        'pb' => 'padang bulan',
        'mw' => 'merdeka walk',
        'poci' => 'podomoro',
        'deli park' => 'podomoro',
        'delipark' => 'podomoro',
        'sp' => 'sun plaza',
        'mts' => 'manhattan',
        'cp' => 'centre point',
        'centrepoint' => 'centre point',
        'adam malik' => 'adam malik',
        'rs adam malik' => 'adam malik',
        'murni teguh' => 'murni teguh',
        'columbia' => 'columbia',
        'hm yamin' => 'yamin',
        'h.m. yamin' => 'yamin',
        'h m yamin' => 'yamin',
        'prof yamin' => 'yamin',
        'maimun' => 'istana maimun',
        'jw' => 'jw marriott',
        'marriott' => 'jw marriott',
        'gch' => 'grand city hall',
        'city hall' => 'grand city hall',
        'santika' => 'santika premiere',
        'cembrij' => 'cambridge',
        'kembrid' => 'cambridge',
        'sakura' => 'grand sakura',
        'b5' => 'bintang 5',
        'b4' => 'bintang 4',
        'b3' => 'bintang 3',
        'bintang lima' => 'bintang 5',
        'bintang empat' => 'bintang 4',
        'bintang tiga' => 'bintang 3',
        'ci' => 'check-in',
        'co' => 'check-out',
        'in' => 'check-in',
        'out' => 'check-out',
        'rek' => 'rekening',
        'norek' => 'rekening',
        'tf' => 'transfer',
        'dkt' => 'dekat',
        'yg' => 'yang',
        'jl' => 'jalan',
        'jln' => 'jalan',
        'dr' => 'dari',
        'sm' => 'sama',
        'krg' => 'kurang',
        'bgs' => 'bagus',
        'pool' => 'kolam renang',
        'breakfast' => 'sarapan',
        'fitness' => 'gym',
    ];

    /**
     * Handle incoming chatbot message (aliased to respond & reply).
     */
    public function respond(Request $request): JsonResponse
    {
        return $this->reply($request);
    }

    /**
     * Handle incoming chatbot message with grounded Gemini AI and smart local fallback.
     */
    public function reply(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:1500'],
            'history' => ['nullable', 'array', 'max:12'],
            'history.*.role' => ['required_with:history', 'string', 'in:user,model'],
            'history.*.text' => ['required_with:history', 'string', 'max:1500'],
            'context' => ['nullable', 'array'],
            'context.hotel_id' => ['nullable', 'string', 'max:100'],
            'context.landmark_id' => ['nullable', 'string', 'max:100'],
            'context.page_path' => ['nullable', 'string', 'max:255'],
            'context.user_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'context.user_lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $message = trim((string) ($validated['message'] ?? ''));
        $history = is_array($validated['history'] ?? null) ? $validated['history'] : [];
        $context = is_array($validated['context'] ?? null) ? $validated['context'] : [];

        if ($message === '') {
            return response()->json([
                'reply' => 'Halo! Saya **Roomify AI Concierge** siap membantu Anda menemukan hotel terdekat, cek jadwal check-in, membandingkan fasilitas, atau melacak kode pesanan di Medan.',
                'hotels' => [],
                'suggestions' => ['Hotel dekat USU', 'Hotel di jalan h.m yamin', 'Cek pesanan saya', 'Hotel bintang 5'],
                'engine' => 'local',
            ]);
        }

        $lower = mb_strtolower($message);
        $expanded = $this->expandAbbreviations($message);

        // 1. Exact DB lookup for booking code (RMF-YYYYMMDD-XXXX) - never delegated to LLM
        if (preg_match('/(rmf-[a-z0-9\-]+)/i', $message, $matches)) {
            $bookingCode = strtoupper($matches[1]);

            return $this->lookupBookingByCode($bookingCode);
        }

        // 2. Explicit user booking history check
        if ($this->isBookingHistoryIntent($lower, $expanded)) {
            return $this->lookupUserBookings();
        }

        $hotels = $this->getHotels();
        $locations = $this->getAllLocations();
        $hotelsById = [];
        foreach ($hotels as $hotel) {
            if (! empty($hotel['id'])) {
                $hotelsById[$hotel['id']] = $hotel;
            }
        }

        $signals = $this->extractQuerySignals($message, $lower, $expanded, $history, $context, $hotels, $locations);

        // 3. Primary Path: Grounded Gemini AI Concierge
        if ($this->gemini->isAvailable()) {
            $grounding = $this->buildGroundingContext($hotels, $hotelsById, $signals, $context);
            $geminiResult = $this->gemini->generateReply($message, $history, $grounding);

            if ($geminiResult !== null) {
                return $this->hydrateGeminiResponse(
                    $geminiResult['data'],
                    $geminiResult['model'],
                    $hotelsById,
                    $locations,
                    $grounding
                );
            }
        }

        // 4. Deterministic Smart Fallback Path (zero-downtime & test-deterministic)
        return $this->respondLocally($lower, $expanded, $hotels, $locations, $signals);
    }

    /**
     * Determine if the user explicitly asks to inspect their personal bookings.
     */
    protected function isBookingHistoryIntent(string $lower, string $expanded): bool
    {
        return Str::contains($lower, [
            'pesanan saya',
            'booking saya',
            'cek pesanan',
            'riwayat booking',
            'status booking',
            'tiket saya',
        ]) || Str::contains($expanded, ['pesanan saya', 'tiket saya'])
            || trim($lower) === 'pesanan';
    }

    /**
     * Normalize and expand abbreviations / acronyms / slang into standardized terms using single-pass tokenization.
     */
    protected function expandAbbreviations(string $text): string
    {
        $normalized = mb_strtolower($text);
        $clean = preg_replace('/[.,\-_]/', ' ', $normalized) ?? $normalized;
        $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $expandedWords = [];
        $i = 0;
        $count = count($words);

        while ($i < $count) {
            if ($i + 1 < $count) {
                $phrase = $words[$i].' '.$words[$i + 1];
                if (isset(self::ABBREVIATION_MAP[$phrase])) {
                    $expandedWords[] = self::ABBREVIATION_MAP[$phrase];
                    $i += 2;

                    continue;
                }
            }

            $word = $words[$i];
            $expandedWords[] = self::ABBREVIATION_MAP[$word] ?? $word;
            $i++;
        }

        return trim(implode(' ', $expandedWords));
    }

    /**
     * Merge curated Medan hotspots with all 24+ Fuseki landmarks.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getAllLocations(): array
    {
        $locations = [];

        foreach (self::KNOWN_LOCATIONS as $key => $loc) {
            $locations[$key] = $loc + ['key' => $key];
        }

        foreach (FusekiClient::LANDMARKS as $lm) {
            $nameLower = mb_strtolower($lm['name']);
            $alreadyCovered = false;
            foreach ($locations as $existing) {
                if (Str::contains(mb_strtolower($existing['name']), $nameLower) || ($existing['landmark_id'] ?? null) === $lm['id']) {
                    $alreadyCovered = true;
                    break;
                }
            }

            if (! $alreadyCovered) {
                $locations[$nameLower] = [
                    'key' => $nameLower,
                    'name' => $lm['name'],
                    'lat' => (float) $lm['lat'],
                    'lng' => (float) $lm['lon'],
                    'desc' => ($lm['category'] ?? 'Landmark').' · '.($lm['address'] ?? 'Kota Medan'),
                    'landmark_id' => $lm['id'],
                ];
            }
        }

        return $locations;
    }

    /**
     * Load enriched hotel dataset via SPARQL Triplestore (FusekiClient) with JSON fallback.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getHotels(): array
    {
        return Cache::remember('roomify_chatbot_hotels_v2', 600, function (): array {
            $sparql = 'PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?id ?nama ?alamat ?harga ?rating ?star ?foto ?lat ?lon ?desc WHERE {
  ?h a roomify:Hotel ;
     roomify:name ?nama ;
     roomify:address ?alamat .
  OPTIONAL { ?h roomify:startPrice ?harga }
  OPTIONAL { ?h roomify:rating ?rating }
  OPTIONAL { ?h roomify:starRating ?star }
  OPTIONAL { ?h roomify:description ?desc }
  OPTIONAL { ?h roomify:latitude ?lat }
  OPTIONAL { ?h roomify:longitude ?lon }
  OPTIONAL { ?h roomify:hasPhoto ?p . ?p roomify:photoUrl ?foto }
  BIND(STRAFTER(STR(?h), "#") AS ?id)
} ORDER BY ASC(?nama)';

            $rows = $this->fuseki->select($sparql);
            if (! empty($rows)) {
                $facilitiesMap = [];
                $facRows = $this->fuseki->select(
                    'PREFIX roomify: <https://roomify.example/ontology#>
SELECT ?id (GROUP_CONCAT(DISTINCT ?n; separator="|") AS ?v) WHERE {
  ?h roomify:hasFacility ?f . ?f roomify:name ?n .
  BIND(STRAFTER(STR(?h), "#") AS ?id)
} GROUP BY ?id'
                );

                foreach ($facRows as $fr) {
                    if (! empty($fr['id'])) {
                        $facilitiesMap[$fr['id']] = array_values(array_filter(explode('|', (string) ($fr['v'] ?? ''))));
                    }
                }

                return array_map(function (array $r) use ($facilitiesMap): array {
                    $id = (string) ($r['id'] ?? '');

                    return [
                        'id' => $id,
                        'name' => $r['nama'] ?? 'Hotel',
                        'address' => $r['alamat'] ?? 'Kota Medan',
                        'start_price' => ! empty($r['harga']) ? (int) $r['harga'] : null,
                        'rating' => ! empty($r['rating']) ? (float) $r['rating'] : 4.5,
                        'star' => ! empty($r['star']) ? (int) $r['star'] : 3,
                        'image_url' => $r['foto'] ?? '/images/placeholder.svg',
                        'latitude' => ! empty($r['lat']) ? (float) $r['lat'] : null,
                        'longitude' => ! empty($r['lon']) ? (float) $r['lon'] : null,
                        'description' => $r['desc'] ?? null,
                        'facilities' => $facilitiesMap[$id] ?? [],
                    ];
                }, $rows);
            }

            $path = base_path('semantic/scrape/hotels_complete.json');
            if (File::exists($path)) {
                $data = json_decode(File::get($path), true);
                if (is_array($data)) {
                    return $data;
                }
            }

            return [];
        });
    }

    /**
     * Extract filters, location references, active page context, and matched hotels from input + history.
     *
     * @param  array<int, array{role: string, text: string}>  $history
     * @param  array<string, mixed>  $context
     * @param  array<int, array<string, mixed>>  $hotels
     * @param  array<string, array<string, mixed>>  $locations
     * @return array<string, mixed>
     */
    protected function extractQuerySignals(
        string $rawMessage,
        string $lower,
        string $expanded,
        array $history,
        array $context,
        array $hotels,
        array $locations
    ): array {
        $maxPrice = null;
        $minPrice = null;
        $minStar = null;
        $facilities = [];
        $sortBy = null;

        // Parse budget ceilings like "dibawah 500rb", "maks 400 ribu", "< 350k", "budget 300.000"
        if (preg_match('/(?:di\s*bawah|dibawah|kurang\s*dari|maks(?:imal)?|max|under|budget|<=?)\s*(?:rp\.?\s*)?([0-9]+(?:[.,][0-9]+)?)\s*(rb|ribu|k|jt|juta)?/iu', $lower, $pm)) {
            $maxPrice = $this->parseIndonesianMoney($pm[1], $pm[2] ?? '');
        }
        if (preg_match('/(?:di\s*atas|diatas|lebih\s*dari|min(?:imal)?|>=?)\s*(?:rp\.?\s*)?([0-9]+(?:[.,][0-9]+)?)\s*(rb|ribu|k|jt|juta)?/iu', $lower, $pmin)) {
            $minPrice = $this->parseIndonesianMoney($pmin[1], $pmin[2] ?? '');
        }

        if (Str::contains($lower, ['murah', 'hemat', 'budget', 'promo', 'diskon', 'terjangkau', 'termurah'])) {
            $sortBy = 'price_asc';
        } elseif (Str::contains($lower, ['rating tertinggi', 'terbaik', 'best', 'paling bagus', 'favorit']) || Str::contains($expanded, ['paling bagus'])) {
            $sortBy = 'rating_desc';
        }

        if (Str::contains($lower, ['bintang 5', 'bintang lima', 'mewah', 'luxury']) || Str::contains($expanded, ['bintang 5', 'luxury'])) {
            $minStar = 5;
        } elseif (Str::contains($lower, ['bintang 4', 'bintang empat']) || Str::contains($expanded, ['bintang 4'])) {
            $minStar = 4;
        } elseif (Str::contains($lower, ['bintang 3', 'bintang tiga']) || Str::contains($expanded, ['bintang 3'])) {
            $minStar = 3;
        }

        $facilityKeywords = [
            'kolam renang' => ['kolam renang', 'kolam', 'swimming pool', 'pool'],
            'sarapan' => ['sarapan', 'breakfast'],
            'gym' => ['gym', 'fitness'],
            'wifi' => ['wifi', 'wi-fi', 'internet'],
            'parkir' => ['parkir', 'parking'],
            'ac' => ['ac', 'air conditioner'],
            'spa' => ['spa'],
            'restoran' => ['restoran', 'restaurant'],
        ];
        foreach ($facilityKeywords as $canonical => $aliases) {
            if (Str::contains($lower, $aliases) || Str::contains($expanded, $aliases)) {
                $facilities[] = $canonical;
            }
        }

        // Match explicit location in current query first
        $matchedLocation = $this->matchLocation($lower, $expanded, $locations);

        // If user is on a landmark page and asks a contextual question ("hotel dekat sini")
        if (! $matchedLocation && ! empty($context['landmark_id'])) {
            foreach ($locations as $loc) {
                if (($loc['landmark_id'] ?? null) === $context['landmark_id']) {
                    $matchedLocation = $loc;
                    break;
                }
            }
        }

        // If user asks follow-up ("yang lebih murah dari situ?", "dekat situ") recover location from recent user turns
        if (! $matchedLocation && Str::contains($lower, ['situ', 'sana', 'sini', 'tadi', 'sekitarnya', 'tersebut'])) {
            foreach (array_reverse($history) as $turn) {
                if (($turn['role'] ?? '') !== 'user') {
                    continue;
                }
                $prevText = (string) ($turn['text'] ?? '');
                $prevLoc = $this->matchLocation(mb_strtolower($prevText), $this->expandAbbreviations($prevText), $locations);
                if ($prevLoc !== null) {
                    $matchedLocation = $prevLoc;
                    break;
                }
            }
        }

        // GPS user location if explicitly asking for "dekat saya" / "terdekat"
        $userGps = null;
        if (isset($context['user_lat'], $context['user_lng']) && is_numeric($context['user_lat']) && is_numeric($context['user_lng'])) {
            $userGps = [
                'key' => 'user_gps',
                'name' => 'Lokasi GPS Anda Saat Ini',
                'lat' => (float) $context['user_lat'],
                'lng' => (float) $context['user_lng'],
                'desc' => 'Koordinat lokasi Anda saat ini di Kota Medan',
                'is_gps' => true,
            ];
            if (! $matchedLocation && Str::contains($lower, ['dekat saya', 'lokasi saya', 'sekitar saya', 'gps', 'terdekat'])) {
                $matchedLocation = $userGps;
            }
        }

        $activeHotel = null;
        if (! empty($context['hotel_id'])) {
            foreach ($hotels as $h) {
                if (($h['id'] ?? '') === $context['hotel_id']) {
                    $activeHotel = $h;
                    break;
                }
            }
        }

        $matchedHotels = $this->matchHotelsInText($lower, $expanded, $hotels);
        if ($activeHotel !== null && empty($matchedHotels) && Str::contains($lower, ['hotel ini', 'kamar', 'fasilitas', 'alamat', 'harga', 'check', 'review', 'bandingkan'])) {
            $matchedHotels[] = $activeHotel;
        }

        return [
            'raw' => $rawMessage,
            'max_price' => $maxPrice,
            'min_price' => $minPrice,
            'min_star' => $minStar,
            'facilities' => array_values(array_unique($facilities)),
            'sort_by' => $sortBy,
            'matched_location' => $matchedLocation,
            'user_gps' => $userGps,
            'active_hotel' => $activeHotel,
            'matched_hotels' => $matchedHotels,
        ];
    }

    /**
     * Parse Indonesian monetary shorthand into integer IDR.
     */
    protected function parseIndonesianMoney(string $numberPart, string $unit): int
    {
        $clean = str_replace(['.', ','], ['', '.'], $numberPart);
        $val = (float) $clean;
        $u = mb_strtolower($unit);

        if ($u === 'jt' || $u === 'juta') {
            return (int) round($val * 1_000_000);
        }

        if ($u === 'rb' || $u === 'ribu' || $u === 'k') {
            return (int) round($val * 1_000);
        }

        // Handle shorthand like "500.000" (already stripped dot above -> 500000) vs "500"
        if (str_contains($numberPart, '.') && $val < 1000) {
            return (int) round((float) str_replace('.', '', $numberPart));
        }

        return (int) round($val);
    }

    /**
     * Match a location or landmark from raw/expanded query text.
     *
     * @param  array<string, array<string, mixed>>  $locations
     * @return array<string, mixed>|null
     */
    protected function matchLocation(string $lower, string $expanded, array $locations): ?array
    {
        foreach ($locations as $locKey => $locData) {
            if (Str::contains($lower, $locKey) || Str::contains($expanded, $locKey)) {
                return $locData;
            }
        }

        return null;
    }

    /**
     * Match one or more specific hotels mentioned in the user message.
     *
     * @param  array<int, array<string, mixed>>  $hotels
     * @return array<int, array<string, mixed>>
     */
    protected function matchHotelsInText(string $lower, string $expanded, array $hotels): array
    {
        $matches = [];
        $seenIds = [];

        $addMatch = static function (array $hotel) use (&$matches, &$seenIds): void {
            $id = (string) ($hotel['id'] ?? '');
            if ($id !== '' && ! isset($seenIds[$id])) {
                $seenIds[$id] = true;
                $matches[] = $hotel;
            }
        };

        if (Str::contains($lower, 'cambridge') || Str::contains($expanded, 'cambridge')) {
            foreach ($hotels as $h) {
                if (Str::contains(mb_strtolower($h['name'] ?? ''), 'cambridge')) {
                    $addMatch($h);
                    break;
                }
            }
        }

        // Exact/substring match against full hotel names
        foreach ($hotels as $hotel) {
            $hotelName = mb_strtolower($hotel['name'] ?? '');
            if (mb_strlen($hotelName) >= 4 && (Str::contains($lower, $hotelName) || Str::contains($expanded, $hotelName))) {
                $addMatch($hotel);
            }
            if (count($matches) >= 3) {
                return $matches;
            }
        }

        // Clean filler words and match partial hotel name (e.g. "santika premiere", "jw marriott", "aryaduta")
        if (empty($matches)) {
            $cleanTerms = trim(preg_replace('/\b(hotel|penginapan|inap|yang|itu|di|daerah|kawasan|dekat|sekitar|bandingkan|sama|dan|dengan|berapa|harga)\b/i', '', $expanded) ?? '');
            $cleanTerms = trim(preg_replace('/\s+/', ' ', $cleanTerms) ?? '');
            if (mb_strlen($cleanTerms) >= 4) {
                foreach ($hotels as $hotel) {
                    $hotelName = mb_strtolower($hotel['name'] ?? '');
                    if (Str::contains($hotelName, $cleanTerms)) {
                        $addMatch($hotel);
                        break;
                    }
                }
            }
        }

        // Street / address match if user explicitly mentions a street name not in KNOWN_LOCATIONS
        if (empty($matches)) {
            $words = array_filter(
                explode(' ', str_replace(['.', ',', '-', '/'], ' ', $expanded)),
                static fn (string $w): bool => mb_strlen($w) > 4 && ! in_array($w, ['hotel', 'medan', 'dekat', 'murah', 'bagus', 'kamar', 'harga', 'bintang', 'kolam', 'renang', 'sarapan'], true)
            );
            foreach ($hotels as $hotel) {
                $addr = mb_strtolower($hotel['address'] ?? '');
                foreach ($words as $w) {
                    if (Str::contains($addr, $w)) {
                        $addMatch($hotel);
                        break 2;
                    }
                }
            }
        }

        if (empty($matches) && mb_strlen($expanded) >= 4) {
            $fuzzy = $this->findHotelByFuzzy($expanded, $hotels, 70);
            if ($fuzzy !== null) {
                $addMatch($fuzzy);
            }
        }

        return $matches;
    }

    /**
     * Fuzzy matching helper for hotel names.
     *
     * @param  array<int, array<string, mixed>>  $hotels
     * @return array<string, mixed>|null
     */
    protected function findHotelByFuzzy(string $query, array $hotels, int $minPercent = 65): ?array
    {
        $bestMatch = null;
        $highest = 0.0;

        foreach ($hotels as $hotel) {
            $name = mb_strtolower($hotel['name'] ?? '');
            similar_text($query, $name, $percent);
            if ($percent > $highest && $percent >= $minPercent) {
                $highest = $percent;
                $bestMatch = $hotel;
            }
        }

        return $bestMatch;
    }

    /**
     * Build grounded RAG context for Gemini with top candidate hotels, landmarks, and user bookings.
     *
     * @param  array<int, array<string, mixed>>  $hotels
     * @param  array<string, array<string, mixed>>  $hotelsById
     * @param  array<string, mixed>  $signals
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    protected function buildGroundingContext(
        array $hotels,
        array $hotelsById,
        array $signals,
        array $context
    ): array {
        $candidates = $this->rankCandidateHotels($hotels, $signals, 22);

        $compactCandidates = array_map(function (array $item): array {
            $h = $item['hotel'];
            $entry = [
                'id' => $h['id'],
                'name' => $h['name'],
                'address' => $h['address'],
                'star' => (int) ($h['star'] ?? 3),
                'rating' => (float) ($h['rating'] ?? 4.5),
                'start_price_idr' => $h['start_price'] ?? ($h['price_from'] ?? null),
                'facilities' => array_slice($h['facilities'] ?? [], 0, 8),
            ];

            if ($item['distance_km'] !== null) {
                $entry['distance_km'] = round($item['distance_km'], 2);
            }

            if (! empty($h['description'])) {
                $entry['summary'] = Str::limit((string) $h['description'], 160);
            }

            return $entry;
        }, $candidates);

        $activeHotelSummary = null;
        if (! empty($signals['active_hotel'])) {
            $ah = $signals['active_hotel'];
            $activeHotelSummary = [
                'id' => $ah['id'],
                'name' => $ah['name'],
                'address' => $ah['address'],
                'star' => (int) ($ah['star'] ?? 3),
                'rating' => (float) ($ah['rating'] ?? 4.5),
                'start_price_idr' => $ah['start_price'] ?? ($ah['price_from'] ?? null),
                'facilities' => $ah['facilities'] ?? [],
                'description' => $ah['description'] ?? null,
            ];
        }

        $matchedLocationSummary = null;
        if (! empty($signals['matched_location'])) {
            $loc = $signals['matched_location'];
            $matchedLocationSummary = [
                'key' => $loc['key'],
                'name' => $loc['name'],
                'desc' => $loc['desc'] ?? '',
                'landmark_id' => $loc['landmark_id'] ?? null,
                'lat' => $loc['lat'],
                'lng' => $loc['lng'],
            ];
        }

        $userBookings = [];
        if (Auth::check()) {
            $bookings = Booking::where('user_id', Auth::id())
                ->orderByDesc('id')
                ->take(3)
                ->get();

            foreach ($bookings as $b) {
                $userBookings[] = [
                    'booking_code' => $b->booking_code,
                    'hotel_name' => $b->hotel_name,
                    'room_type' => $b->room_type,
                    'checkin_date' => (string) $b->checkin_date,
                    'checkout_date' => (string) $b->checkout_date,
                    'total_price_idr' => $b->total_price,
                    'payment_status' => $b->payment_status,
                ];
            }
        }

        return [
            'catalog_stats' => [
                'total_hotels_in_medan' => count($hotels),
                'total_landmarks' => count(FusekiClient::LANDMARKS),
            ],
            'current_page' => $context['page_path'] ?? null,
            'active_hotel' => $activeHotelSummary,
            'matched_location' => $matchedLocationSummary,
            'detected_filters' => [
                'max_price_idr' => $signals['max_price'],
                'min_price_idr' => $signals['min_price'],
                'min_star' => $signals['min_star'],
                'facilities' => $signals['facilities'],
                'sort_by' => $signals['sort_by'],
            ],
            'user_logged_in' => Auth::check(),
            'user_bookings' => $userBookings,
            'candidate_hotels' => $compactCandidates,
        ];
    }

    /**
     * Filter and rank hotels according to parsed query signals and proximity.
     *
     * @param  array<int, array<string, mixed>>  $hotels
     * @param  array<string, mixed>  $signals
     * @return array<int, array{hotel: array<string, mixed>, distance_km: ?float, score: float}>
     */
    protected function rankCandidateHotels(array $hotels, array $signals, int $limit = 22): array
    {
        $loc = $signals['matched_location'];
        $locLat = $loc ? (float) $loc['lat'] : null;
        $locLng = $loc ? (float) $loc['lng'] : null;

        $matchedIds = [];
        foreach ($signals['matched_hotels'] ?? [] as $mh) {
            if (! empty($mh['id'])) {
                $matchedIds[$mh['id']] = true;
            }
        }
        if (! empty($signals['active_hotel']['id'])) {
            $matchedIds[$signals['active_hotel']['id']] = true;
        }

        $ranked = [];
        foreach ($hotels as $hotel) {
            $price = $hotel['start_price'] ?? ($hotel['price_from'] ?? null);
            $star = (int) ($hotel['star'] ?? 0);
            $rating = (float) ($hotel['rating'] ?? 4.0);
            $facLower = array_map('mb_strtolower', $hotel['facilities'] ?? []);

            // Apply strict filters when explicitly requested
            if ($signals['max_price'] !== null && ($price === null || $price <= 0 || $price > $signals['max_price'])) {
                continue;
            }
            if ($signals['min_price'] !== null && ($price === null || $price < $signals['min_price'])) {
                continue;
            }
            if ($signals['min_star'] !== null && $star < $signals['min_star']) {
                continue;
            }
            if (! empty($signals['facilities'])) {
                $matchesAllFac = true;
                foreach ($signals['facilities'] as $reqFac) {
                    $found = false;
                    foreach ($facLower as $hf) {
                        if (Str::contains($hf, $reqFac) || Str::contains($reqFac, $hf)) {
                            $found = true;
                            break;
                        }
                    }
                    if (! $found) {
                        $matchesAllFac = false;
                        break;
                    }
                }
                if (! $matchesAllFac) {
                    continue;
                }
            }

            $distKm = null;
            if ($locLat !== null && $locLng !== null && ! empty($hotel['latitude']) && ! empty($hotel['longitude'])) {
                $distKm = $this->haversineKm($locLat, $locLng, (float) $hotel['latitude'], (float) $hotel['longitude']);
            }

            $score = $rating * 10.0;
            if (isset($matchedIds[$hotel['id'] ?? ''])) {
                $score += 1000.0;
            }
            if ($distKm !== null) {
                $score += max(0.0, 120.0 - ($distKm * 12.0));
            }
            if ($signals['sort_by'] === 'price_asc') {
                $score += ($price !== null && $price > 0) ? max(0.0, 100.0 - ($price / 20000.0)) : -50.0;
            } elseif ($signals['sort_by'] === 'rating_desc') {
                $score += $rating * 25.0 + $star * 5.0;
            } elseif ($price !== null && $price > 0) {
                $score += 10.0;
            }

            $ranked[] = [
                'hotel' => $hotel,
                'distance_km' => $distKm,
                'score' => $score,
            ];
        }

        // If strict combination yielded zero hotels, relax price/facility filters and fall back to proximity/rating
        if (empty($ranked)) {
            foreach ($hotels as $hotel) {
                $distKm = ($locLat !== null && $locLng !== null && ! empty($hotel['latitude']) && ! empty($hotel['longitude']))
                    ? $this->haversineKm($locLat, $locLng, (float) $hotel['latitude'], (float) $hotel['longitude'])
                    : null;
                $score = (float) ($hotel['rating'] ?? 4.0) * 10.0 + ($distKm !== null ? max(0.0, 120.0 - ($distKm * 12.0)) : 0.0);
                if (isset($matchedIds[$hotel['id'] ?? ''])) {
                    $score += 1000.0;
                }
                $ranked[] = [
                    'hotel' => $hotel,
                    'distance_km' => $distKm,
                    'score' => $score,
                ];
            }
        }

        usort($ranked, static fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        return array_slice($ranked, 0, $limit);
    }

    /**
     * Hydrate Gemini's structured JSON output with verified hotel cards, map URLs, and safe actions.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, array<string, mixed>>  $hotelsById
     * @param  array<string, array<string, mixed>>  $locations
     * @param  array<string, mixed>  $grounding
     */
    protected function hydrateGeminiResponse(
        array $data,
        string $model,
        array $hotelsById,
        array $locations,
        array $grounding
    ): JsonResponse {
        $locationData = null;
        $locKey = mb_strtolower(trim((string) ($data['location_key'] ?? '')));
        $matchedLoc = $grounding['matched_location'] ?? null;

        $resolvedLoc = null;
        if ($locKey !== '' && isset($locations[$locKey])) {
            $resolvedLoc = $locations[$locKey];
        } elseif (is_array($matchedLoc) && ($locKey === '' || $locKey === ($matchedLoc['key'] ?? ''))) {
            if (! empty($data['hotel_ids'])) {
                $resolvedLoc = $matchedLoc;
            }
        }

        if (is_array($resolvedLoc) && isset($resolvedLoc['lat'], $resolvedLoc['lng']) && empty($resolvedLoc['is_gps'])) {
            $locLat = (float) $resolvedLoc['lat'];
            $locLng = (float) $resolvedLoc['lng'];
            $locName = (string) $resolvedLoc['name'];
            $searchTerm = (string) ($resolvedLoc['key'] ?? $locName);

            $locationData = [
                'name' => $locName,
                'desc' => (string) ($resolvedLoc['desc'] ?? 'Titik penting di Kota Medan'),
                'lat' => $locLat,
                'lng' => $locLng,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query='.urlencode($locName.' Medan'),
                'directions_url' => "https://www.google.com/maps/dir/?api=1&destination={$locLat},{$locLng}",
                'search_url' => url('/search?q='.urlencode($searchTerm)),
            ];
        }

        $cards = [];
        $seenHotelIds = [];
        $refLat = $resolvedLoc['lat'] ?? ($matchedLoc['lat'] ?? null);
        $refLng = $resolvedLoc['lng'] ?? ($matchedLoc['lng'] ?? null);

        foreach ((array) ($data['hotel_ids'] ?? []) as $rawId) {
            $id = trim((string) $rawId);
            if ($id === '' || isset($seenHotelIds[$id]) || ! isset($hotelsById[$id])) {
                continue;
            }
            $seenHotelIds[$id] = true;
            $hotel = $hotelsById[$id];

            $distKm = null;
            if ($refLat !== null && $refLng !== null && ! empty($hotel['latitude']) && ! empty($hotel['longitude'])) {
                $distKm = $this->haversineKm((float) $refLat, (float) $refLng, (float) $hotel['latitude'], (float) $hotel['longitude']);
            }

            $cards[] = $this->formatHotelCard($hotel, $distKm);
            if (count($cards) >= 4) {
                break;
            }
        }

        $action = $this->resolveSafeAction(
            (string) ($data['action_type'] ?? 'none'),
            (string) ($data['action_label'] ?? ''),
            (string) ($data['action_param'] ?? ''),
            $hotelsById
        );

        $suggestions = array_values(array_slice(array_filter(array_map(
            static fn ($s): string => Str::limit(trim((string) $s), 45, ''),
            (array) ($data['suggestions'] ?? [])
        )), 0, 4));

        if (empty($suggestions)) {
            $suggestions = ['Hotel dekat USU', 'Hotel bintang 5', 'Cek pesanan saya'];
        }

        return response()->json([
            'reply' => trim((string) $data['reply']),
            'location' => $locationData,
            'hotels' => $cards,
            'action' => $action,
            'suggestions' => $suggestions,
            'engine' => 'gemini',
            'model' => $model,
        ]);
    }

    /**
     * Map structured action directives to verified internal Roomify URLs.
     *
     * @param  array<string, array<string, mixed>>  $hotelsById
     * @return array{label: string, url: string}|null
     */
    protected function resolveSafeAction(string $type, string $label, string $param, array $hotelsById): ?array
    {
        $cleanLabel = trim($label);

        return match ($type) {
            'hotel' => isset($hotelsById[$param]) ? [
                'label' => $cleanLabel !== '' ? $cleanLabel : 'Lihat Detail '.$hotelsById[$param]['name'],
                'url' => url("/hotel/{$param}"),
            ] : null,
            'landmark' => $param !== '' ? [
                'label' => $cleanLabel !== '' ? $cleanLabel : 'Lihat Landmark & Hotel Sekitar',
                'url' => url('/landmark/'.urlencode($param)),
            ] : [
                'label' => $cleanLabel !== '' ? $cleanLabel : 'Jelajahi Landmark Medan',
                'url' => url('/landmark'),
            ],
            'dashboard' => [
                'label' => $cleanLabel !== '' ? $cleanLabel : 'Buka Dashboard Pesanan',
                'url' => url('/dashboard'),
            ],
            'search' => [
                'label' => $cleanLabel !== '' ? $cleanLabel : 'Jelajahi Hotel di Halaman Pencarian',
                'url' => $param !== '' ? url('/search?q='.urlencode($param)) : url('/search'),
            ],
            default => null,
        };
    }

    /**
     * Deterministic smart local response engine (fallback when Gemini is disabled or unavailable).
     *
     * @param  array<int, array<string, mixed>>  $hotels
     * @param  array<string, array<string, mixed>>  $locations
     * @param  array<string, mixed>  $signals
     */
    protected function respondLocally(
        string $lower,
        string $expanded,
        array $hotels,
        array $locations,
        array $signals
    ): JsonResponse {
        // 1. Check-in / Check-out time questions (preserve 14.00 and 14:00 for tests & users)
        if (Str::contains($lower, ['check in', 'check-in', 'checkin', 'jam masuk', 'waktu masuk', 'check out', 'check-out', 'checkout', 'jam keluar', 'waktu keluar']) || Str::contains($expanded, ['check-in', 'check-out'])) {
            return response()->json([
                'reply' => "⏰ **Ketentuan Waktu Menginap Resmi Roomify:**\n\n"
                    ."• **Waktu Check-In:** Pukul **14:00 WIB** (14.00 WIB)\n"
                    ."• **Waktu Check-Out:** Pukul **12:00 WIB** (12.00 WIB)\n"
                    ."• **Identitas Resmi:** Wajib membawa e-KTP fisik 16 digit asli (atau Paspor) saat proses verifikasi resepsionis.\n"
                    .'• *Catatan:* Early check-in atau late check-out bergantung pada ketersediaan kamar di masing-masing hotel mitra.',
                'hotels' => [],
                'suggestions' => ['Cek pesanan saya', 'Hotel dekat USU', 'Rekomendasi hotel murah'],
                'engine' => 'local',
            ]);
        }

        // 2. Syarat KTP / Dokumen
        if (Str::contains($lower, ['ktp', 'dokumen', 'identitas', 'paspor', 'syarat booking', 'syarat reservasi']) || Str::contains($expanded, ['ktp', 'identitas'])) {
            return response()->json([
                'reply' => "🪪 **Syarat Identitas Pemesanan:**\n\n"
                    ."1. Setiap tamu utama wajib menginputkan **Nomor KTP (NIK) 16 digit** saat pemesanan kamar.\n"
                    ."2. Data NIK Anda dienkripsi secara aman sesuai standar perlindungan data pribadi Roomify.\n"
                    .'3. Saat tiba di meja resepsionis hotel, tunjukkan fisik e-KTP dan e-Voucher berkode `RMF-...`.',
                'hotels' => [],
                'suggestions' => ['Waktu check-in hotel', 'Cari hotel bintang 5', 'Cara bayar'],
                'engine' => 'local',
            ]);
        }

        // 3. Pembayaran / Payment methods
        if (Str::contains($lower, ['bayar', 'metode pembayaran', 'qris', 'transfer', 'kartu kredit', 'rekening']) || Str::contains($expanded, ['rekening', 'transfer'])) {
            return response()->json([
                'reply' => "💳 **Metode Pembayaran Resmi Roomify:**\n\n"
                    ."• **QRIS Instan:** BCA, Mandiri, BRI, BNI, GoPay, OVO, Dana, ShopeePay (Verifikasi instan otomatis).\n"
                    ."• **Virtual Account:** Mandiri VA, BCA VA, BRI VA, BNI VA.\n"
                    ."• **Kartu Kredit/Debit:** Visa, Mastercard, JCB.\n"
                    .'• **Konfirmasi:** Tiket e-Voucher langsung terbit otomatis setelah pembayaran lunas tanpa perlu konfirmasi manual.',
                'hotels' => [],
                'suggestions' => ['Cek pesanan saya', 'Hotel dekat USU', 'Hotel dekat Stasiun Medan'],
                'engine' => 'local',
            ]);
        }

        // 4. Cara Navigasi & Maps
        if (Str::contains($lower, ['peta', 'map', 'maps', 'navigasi', 'rute', 'arah jalan', 'google map'])) {
            return response()->json([
                'reply' => "📍 **Fitur Navigasi Google Maps Langsung di Roomify:**\n\n"
                    ."• Di halaman **Cari Hotel** dan **Detail Hotel**, klik tombol hijau **'Petunjuk Belokan'** atau **'Mulai Navigasi'**.\n"
                    .'• Roomify akan mendeteksi GPS lokasi Anda dan menampilkan rute belokan demi belokan (turn-by-turn navigation), estimasi waktu tempuh, dan jarak akurat secara real-time ke hotel tujuan!',
                'hotels' => [],
                'suggestions' => ['Hotel dekat USU', 'Hotel dekat Stasiun Medan', 'Cari hotel termurah'],
                'engine' => 'local',
            ]);
        }

        // 5. Spatial / Proximity Intelligence (handles combined filters like price/star/facility near location too!)
        $matchedLoc = $signals['matched_location'] ?? $this->matchLocation($lower, $expanded, $locations);
        if ($matchedLoc !== null) {
            $locLat = (float) $matchedLoc['lat'];
            $locLng = (float) $matchedLoc['lng'];
            $locName = (string) $matchedLoc['name'];
            $locKey = (string) ($matchedLoc['key'] ?? $locName);

            $locationData = empty($matchedLoc['is_gps']) ? [
                'name' => $locName,
                'desc' => (string) ($matchedLoc['desc'] ?? 'Titik penting di Kota Medan'),
                'lat' => $locLat,
                'lng' => $locLng,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query='.urlencode($locName.' Medan'),
                'directions_url' => "https://www.google.com/maps/dir/?api=1&destination={$locLat},{$locLng}",
                'search_url' => url('/search?q='.urlencode($locKey)),
            ] : null;

            $ranked = $this->rankCandidateHotels($hotels, $signals, 15);
            usort($ranked, static fn (array $a, array $b): int => ($a['distance_km'] ?? 9999.0) <=> ($b['distance_km'] ?? 9999.0));
            $topNearby = array_slice($ranked, 0, 3);
            $cards = array_map(fn (array $item): array => $this->formatHotelCard($item['hotel'], $item['distance_km']), $topNearby);

            $replyList = "📍 **Rekomendasi Hotel Terdekat dari {$locName}:**\n\n";
            foreach ($topNearby as $idx => $item) {
                $num = $idx + 1;
                $h = $item['hotel'];
                $dist = $item['distance_km'] ?? 0.0;
                $distText = $dist < 1 ? round($dist * 1000).' m' : number_format($dist, 1, ',', '.').' km';
                $price = isset($h['start_price']) && $h['start_price'] > 0
                    ? 'Rp '.number_format((int) $h['start_price'], 0, ',', '.')
                    : 'Terjangkau';
                $replyList .= "{$num}. **{$h['name']}** (⭐ {$h['rating']})\n"
                    ."   • Jarak: **{$distText}** dari lokasi\n"
                    ."   • Alamat: {$h['address']}\n"
                    ."   • Mulai dari: **{$price}** / malam\n\n";
            }
            $replyList .= 'Gunakan tombol di bawah untuk melihat rute Google Maps atau membuka halaman pencarian.';

            return response()->json([
                'reply' => $replyList,
                'location' => $locationData,
                'hotels' => $cards,
                'action' => [
                    'label' => "Buka Peta Hotel Sekitar {$locName}",
                    'url' => url('/search?q='.urlencode($locKey)),
                ],
                'suggestions' => [
                    "Rute ke {$locName}",
                    "Hotel murah dekat {$locName}",
                    'Hotel bintang 5 di Medan',
                    'Cek pesanan saya',
                ],
                'engine' => 'local',
            ]);
        }

        // 6. Specific hotel match
        $matchedHotel = $signals['matched_hotels'][0] ?? $signals['active_hotel'] ?? null;
        if ($matchedHotel !== null) {
            $name = $matchedHotel['name'] ?? 'Hotel';
            $rating = $matchedHotel['rating'] ?? '4.5';
            $star = $matchedHotel['star'] ?? '4';
            $price = isset($matchedHotel['start_price']) && $matchedHotel['start_price'] > 0
                ? 'Rp '.number_format((int) $matchedHotel['start_price'], 0, ',', '.')
                : (isset($matchedHotel['price_from']) && $matchedHotel['price_from'] > 0
                    ? 'Rp '.number_format((int) $matchedHotel['price_from'], 0, ',', '.')
                    : 'Harga terjangkau');
            $addr = $matchedHotel['address'] ?? 'Kota Medan';
            $desc = $matchedHotel['description'] ?? 'Hotel pilihan nyaman di pusat Kota Medan dengan fasilitas lengkap.';
            $id = $matchedHotel['id'] ?? '1';
            $facilities = is_array($matchedHotel['facilities'] ?? null) && ! empty($matchedHotel['facilities'])
                ? implode(', ', array_slice($matchedHotel['facilities'], 0, 5))
                : 'WiFi Gratis, AC, Restoran, Parkir';

            $hotelCard = $this->formatHotelCard($matchedHotel);
            $hLat = isset($matchedHotel['latitude']) ? (float) $matchedHotel['latitude'] : null;
            $hLng = isset($matchedHotel['longitude']) ? (float) $matchedHotel['longitude'] : null;

            $locationData = ($hLat && $hLng) ? [
                'name' => $name,
                'desc' => $addr,
                'lat' => $hLat,
                'lng' => $hLng,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query='.urlencode($name.' '.$addr),
                'directions_url' => "https://www.google.com/maps/dir/?api=1&destination={$hLat},{$hLng}",
                'search_url' => url("/hotel/{$id}"),
            ] : null;

            return response()->json([
                'reply' => "🏨 **Informasi & Lokasi {$name}:**\n\n"
                    ."• **Kelas Hotel:** Bintang {$star} (⭐ {$rating}/5.0)\n"
                    ."• **Harga Mulai:** **{$price}** / malam\n"
                    ."• **Alamat:** {$addr}\n"
                    ."• **Fasilitas:** {$facilities}\n"
                    ."• **Deskripsi:** {$desc}\n\n"
                    .'Klik tombol rute peta untuk navigasi langsung di Google Maps atau telusuri detail kamar.',
                'location' => $locationData,
                'hotels' => [$hotelCard],
                'action' => [
                    'label' => "Lihat Detail {$name}",
                    'url' => url("/hotel/{$id}"),
                ],
                'suggestions' => [
                    "Pesan {$name}",
                    "Navigasi rute ke {$name}",
                    "Hotel lain dekat {$name}",
                    'Ketentuan check-in',
                ],
                'engine' => 'local',
            ]);
        }

        // 7. Budget / Price ceiling queries
        if (($signals['sort_by'] === 'price_asc') || ($signals['max_price'] !== null)) {
            $ranked = $this->rankCandidateHotels($hotels, $signals, 10);
            usort($ranked, static fn (array $a, array $b): int => ($a['hotel']['start_price'] ?? 9999999) <=> ($b['hotel']['start_price'] ?? 9999999));
            $cheapCards = array_map(fn (array $item): array => $this->formatHotelCard($item['hotel']), array_slice($ranked, 0, 3));

            return response()->json([
                'reply' => "💰 **Rekomendasi Hotel Nyaman & Hemat di Medan:**\n\n"
                    ."Roomify menyediakan pilihan hotel mulai dari **Rp 120.000 - Rp 350.000 / malam** dengan fasilitas AC dingin, Wi-Fi kencang, dan garansi check-in 14:00 WIB tepat waktu.\n\n"
                    .'Berikut beberapa pilihan hotel dengan harga paling terjangkau:',
                'hotels' => $cheapCards,
                'action' => [
                    'label' => 'Lihat Semua Hotel Termurah',
                    'url' => url('/search?sort=price_asc'),
                ],
                'suggestions' => ['Hotel bintang 5', 'Hotel dekat Stasiun Medan', 'Jam check-in'],
                'engine' => 'local',
            ]);
        }

        // 8. Luxury / Star queries
        if (($signals['min_star'] !== null && $signals['min_star'] >= 4) || Str::contains($lower, ['bintang 5', 'bintang lima', 'mewah', 'luxury'])) {
            $minStar = $signals['min_star'] ?? 5;
            $starHotels = array_values(array_filter($hotels, static fn (array $h): bool => ($h['star'] ?? 0) >= $minStar));
            $starCards = array_map(fn (array $h): array => $this->formatHotelCard($h), array_slice($starHotels, 0, 3));

            return response()->json([
                'reply' => "⭐ **Pilihan Hotel Bintang {$minStar} & Mewah di Kota Medan:**\n\n"
                    ."• **JW Marriott Hotel Medan** (Jl. Putri Hijau)\n"
                    ."• **Grand City Hall Hotel & Serviced Residences** (Jl. Balai Kota)\n"
                    ."• **Cambridge Hotel Medan** (Jl. S. Parman)\n"
                    ."• **Santika Premiere Dyandra Hotel & Convention** (Jl. Kapten Maulana Lubis)\n\n"
                    .'Semua hotel mewah ini dilengkapi fasilitas spa, kolam renang infinity, fitness center, dan restoran fine-dining.',
                'hotels' => $starCards,
                'action' => [
                    'label' => 'Jelajahi Hotel Mewah',
                    'url' => url('/search?star='.$minStar),
                ],
                'suggestions' => ['Hotel dekat Sun Plaza', 'Cek pesanan saya', 'Metode pembayaran'],
                'engine' => 'local',
            ]);
        }

        // 9. Facility-only query (e.g. "hotel ada kolam renang")
        if (! empty($signals['facilities'])) {
            $ranked = $this->rankCandidateHotels($hotels, $signals, 3);
            $cards = array_map(fn (array $item): array => $this->formatHotelCard($item['hotel']), $ranked);
            $facList = implode(', ', array_map('ucwords', $signals['facilities']));

            return response()->json([
                'reply' => "✨ **Rekomendasi Hotel dengan Fasilitas {$facList}:**\n\n"
                    .'Berikut hotel pilihan di Medan yang menyediakan fasilitas tersebut:',
                'hotels' => $cards,
                'action' => [
                    'label' => 'Cari Hotel Lainnya',
                    'url' => url('/search'),
                ],
                'suggestions' => ['Hotel murah', 'Hotel dekat USU', 'Hotel bintang 5'],
                'engine' => 'local',
            ]);
        }

        // 10. Default friendly AI response
        return response()->json([
            'reply' => "Halo! Saya **Roomify AI Concierge** siap membantu Anda 24/7 di Kota Medan 🛎️\n\n"
                ."Anda bisa bertanya mengenai:\n"
                ."• **Lokasi & Kampus:** Tanya hotel daerah USU, Stasiun Medan, Ringroad, Bandara, dll\n"
                ."• **Nama Hotel:** Tanyakan nama hotel (misal *hotel yang cambridge itu* atau *hotel di jl h.m yamin*)\n"
                ."• **Filter Cerdas:** Misal *hotel dekat USU dibawah 500rb* atau *hotel ada kolam renang*\n"
                ."• **Status Pesanan:** Ketik kode booking Anda (misal `RMF-20261114-XXXX`) atau `cek pesanan saya`\n"
                ."• **Waktu Menginap:** Aturan Check-in (14:00 WIB) & Check-out (12:00 WIB)\n"
                .'• **Metode Pembayaran:** QRIS, Virtual Account, dan Kartu Kredit',
            'hotels' => [],
            'suggestions' => ['Hotel daerah USU', 'Hotel di jalan h.m yamin', 'Hotel yang cambridge itu', 'Cek pesanan saya'],
            'engine' => 'local',
        ]);
    }

    /**
     * Calculate Haversine distance between two coordinates in kilometers.
     */
    protected function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Format a hotel array item into a standard card.
     *
     * @param  array<string, mixed>  $hotel
     * @return array<string, mixed>
     */
    protected function formatHotelCard(array $hotel, ?float $distanceKm = null): array
    {
        $id = $hotel['id'] ?? '1';
        $priceVal = (int) ($hotel['start_price'] ?? ($hotel['price_from'] ?? 0));
        $name = (string) ($hotel['name'] ?? 'Hotel');
        $addr = (string) ($hotel['address'] ?? 'Kota Medan');
        $lat = isset($hotel['latitude']) ? (float) $hotel['latitude'] : null;
        $lng = isset($hotel['longitude']) ? (float) $hotel['longitude'] : null;

        $mapsQuery = urlencode($name.' '.$addr);
        $mapsUrl = "https://www.google.com/maps/search/?api=1&query={$mapsQuery}";
        $directionsUrl = ($lat && $lng)
            ? "https://www.google.com/maps/dir/?api=1&destination={$lat},{$lng}"
            : $mapsUrl;

        $card = [
            'id' => $id,
            'nama' => $name,
            'alamat' => $addr,
            'harga' => $priceVal > 0 ? 'Rp '.number_format($priceVal, 0, ',', '.') : 'Harga terjangkau',
            'rating' => (string) ($hotel['rating'] ?? '4.5'),
            'star' => (int) ($hotel['star'] ?? 3),
            'foto' => $hotel['image_url'] ?? '/images/placeholder.svg',
            'detail_url' => url("/hotel/{$id}"),
            'lat' => $lat,
            'lng' => $lng,
            'maps_url' => $mapsUrl,
            'directions_url' => $directionsUrl,
            'search_url' => url('/search?q='.urlencode($name)),
        ];

        if ($distanceKm !== null) {
            $card['jarak'] = $distanceKm < 1
                ? round($distanceKm * 1000).' meter'
                : number_format($distanceKm, 1, ',', '.').' km';
        }

        return $card;
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
                    .'Mohon periksa kembali e-tiket Anda (format: `RMF-YYYYMMDD-XXXX`). Jika Anda baru saja mendaftar/memesan, pastikan akun Anda sudah masuk ke sistem.',
                'hotels' => [],
                'suggestions' => ['Cek pesanan saya', 'Waktu check-in resmi', 'Cari hotel'],
                'engine' => 'local',
            ]);
        }

        $statusText = match ($booking->payment_status) {
            'paid' => '✅ Lunas / Terkonfirmasi',
            'pending' => '⏳ Menunggu Pembayaran',
            'cancelled' => '❌ Dibatalkan',
            default => ucfirst((string) ($booking->payment_status ?? 'Pending')),
        };

        $ktpMasked = $booking->no_ktp ? substr($booking->no_ktp, 0, 6).'******'.substr($booking->no_ktp, -4) : 'Sudah terverifikasi';
        $totalPrice = 'Rp '.number_format((int) ($booking->total_price ?? 0), 0, ',', '.');
        $checkinTime = $booking->checkin_time ?? '14:00 WIB';
        $checkoutTime = $booking->checkout_time ?? '12:00 WIB';
        $checkinDate = $booking->checkin_date?->format('Y-m-d') ?? (string) $booking->checkin_date;
        $checkoutDate = $booking->checkout_date?->format('Y-m-d') ?? (string) $booking->checkout_date;
        $roomsCount = (int) ($booking->rooms_count ?? 1);
        $guestsCount = (int) ($booking->guests ?? $booking->guests_count ?? 1);

        return response()->json([
            'reply' => "📋 **Data Pesanan Ditemukan:**\n\n"
                ."• **Kode Booking:** `{$booking->booking_code}`\n"
                ."• **Hotel:** **{$booking->hotel_name}**\n"
                ."• **Tipe Kamar:** {$booking->room_type} ({$roomsCount} Kamar · {$guestsCount} Tamu)\n"
                ."• **Check-In:** {$checkinDate} ({$checkinTime})\n"
                ."• **Check-Out:** {$checkoutDate} ({$checkoutTime})\n"
                ."• **NIK e-KTP:** `{$ktpMasked}`\n"
                ."• **Total Biaya:** **{$totalPrice}**\n"
                ."• **Status Pembayaran:** **{$statusText}**\n\n"
                .'Tunjukkan kode booking ini kepada resepsionis saat check-in.',
            'hotels' => [],
            'action' => [
                'label' => 'Lihat Tiket di Dashboard',
                'url' => url('/dashboard'),
            ],
            'suggestions' => ['Navigasi ke hotel ini', 'Cek pesanan lainnya', 'Waktu check-in'],
            'engine' => 'local',
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
                    .'Untuk melihat daftar riwayat pemesanan otomatis Anda, silakan masuk ke akun Roomify terlebih dahulu, atau ketik langsung kode booking Anda (misal `RMF-20261114-XXXX`).',
                'hotels' => [],
                'action' => [
                    'label' => 'Masuk / Login Sekarang',
                    'url' => url('/login'),
                ],
                'suggestions' => ['Hotel dekat USU', 'Cari hotel di Medan', 'Waktu check-in'],
                'engine' => 'local',
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
                    .'Yuk temukan hotel terbaik di Medan sekarang! Ada 312+ pilihan hotel dengan garansi harga hemat dan check-in instan.',
                'hotels' => [],
                'action' => [
                    'label' => 'Jelajahi Hotel Sekarang',
                    'url' => url('/search'),
                ],
                'suggestions' => ['Hotel dekat USU', 'Hotel dekat Stasiun Medan', 'Promo hemat'],
                'engine' => 'local',
            ]);
        }

        $listText = "👋 Halo **{$user->name}**! Berikut 3 pesanan terbaru di akun Anda:\n\n";
        foreach ($bookings as $idx => $b) {
            $num = $idx + 1;
            $statusBadge = ($b->payment_status === 'paid') ? '✅ Lunas' : '⏳ '.ucfirst((string) $b->payment_status);
            $price = 'Rp '.number_format((int) $b->total_price, 0, ',', '.');
            $checkinDate = $b->checkin_date?->format('Y-m-d') ?? (string) $b->checkin_date;
            $listText .= "{$num}. **{$b->hotel_name}** (`{$b->booking_code}`)\n"
                ."   • Check-In: {$checkinDate} (14:00 WIB)\n"
                ."   • Status: {$statusBadge} · Total: {$price}\n\n";
        }
        $listText .= 'Klik tombol di bawah untuk melihat rincian e-voucher lengkap Anda.';

        return response()->json([
            'reply' => $listText,
            'hotels' => [],
            'action' => [
                'label' => 'Buka Dashboard Pesanan',
                'url' => url('/dashboard'),
            ],
            'suggestions' => ['Waktu check-in resmi', 'Cari hotel lainnya', 'Syarat e-KTP'],
            'engine' => 'local',
        ]);
    }
}
