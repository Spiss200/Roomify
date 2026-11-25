<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiConciergeService
{
    /**
     * Determine whether Gemini integration is enabled and configured.
     */
    public function isAvailable(): bool
    {
        $enabled = (bool) config('services.gemini.enabled', false);
        $apiKey = trim((string) config('services.gemini.api_key', ''));

        return $enabled && $apiKey !== '';
    }

    /**
     * Generate a grounded structured response from Gemini using Roomify context.
     *
     * @param  array<int, array{role: string, text: string}>  $history
     * @param  array<string, mixed>  $groundingContext
     * @return array{data: array<string, mixed>, model: string}|null
     */
    public function generateReply(string $userMessage, array $history, array $groundingContext): ?array
    {
        if (! $this->isAvailable()) {
            return null;
        }

        $models = $this->modelPriorityList();
        $payload = $this->buildPayload($userMessage, $history, $groundingContext);

        foreach ($models as $model) {
            $result = $this->callModel($model, $payload);
            if ($result !== null) {
                return [
                    'data' => $result,
                    'model' => $model,
                ];
            }
        }

        return null;
    }

    /**
     * Build ordered, deduplicated list of primary and fallback Gemini models.
     *
     * @return array<int, string>
     */
    protected function modelPriorityList(): array
    {
        $primary = trim((string) config('services.gemini.model', 'gemini-3-flash-preview'));
        $fallbacks = config('services.gemini.fallback_models', []);

        if (! is_array($fallbacks)) {
            $fallbacks = [];
        }

        $all = array_merge([$primary], $fallbacks);

        return array_values(array_unique(array_filter(array_map(
            static fn ($m): string => trim((string) $m),
            $all
        ))));
    }

    /**
     * Build the Gemini generateContent request payload with structured JSON schema.
     *
     * @param  array<int, array{role: string, text: string}>  $history
     * @param  array<string, mixed>  $groundingContext
     * @return array<string, mixed>
     */
    protected function buildPayload(string $userMessage, array $history, array $groundingContext): array
    {
        $contents = [];

        foreach (array_slice($history, -8) as $turn) {
            $role = ($turn['role'] ?? 'user') === 'model' ? 'model' : 'user';
            $text = trim((string) ($turn['text'] ?? ''));
            if ($text === '') {
                continue;
            }
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => mb_substr($text, 0, 1200)]],
            ];
        }

        $contextJson = json_encode($groundingContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $augmentedPrompt = "KONTEKS REAL-TIME ROOMIFY (GUNAKAN SEBAGAI SUMBER KEBENARAN TUNGGAL):\n"
            .$contextJson
            ."\n\nPERTANYAAN PENGGUNA:\n"
            .$userMessage;

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $augmentedPrompt]],
        ];

        return [
            'system_instruction' => [
                'parts' => [
                    ['text' => $this->systemInstruction()],
                ],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.35,
                'topP' => 0.9,
                'maxOutputTokens' => 2048,
                'thinkingConfig' => [
                    'thinkingBudget' => 0,
                ],
                'responseMimeType' => 'application/json',
                'responseSchema' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'reply' => ['type' => 'STRING'],
                        'hotel_ids' => [
                            'type' => 'ARRAY',
                            'items' => ['type' => 'STRING'],
                        ],
                        'location_key' => ['type' => 'STRING'],
                        'action_type' => [
                            'type' => 'STRING',
                            'enum' => ['none', 'search', 'hotel', 'dashboard', 'landmark'],
                        ],
                        'action_label' => ['type' => 'STRING'],
                        'action_param' => ['type' => 'STRING'],
                        'suggestions' => [
                            'type' => 'ARRAY',
                            'items' => ['type' => 'STRING'],
                        ],
                    ],
                    'required' => ['reply', 'hotel_ids', 'action_type', 'suggestions'],
                ],
            ],
        ];
    }

    /**
     * System instruction defining Roomify AI Concierge's persona, domain rules, and grounding policy.
     */
    protected function systemInstruction(): string
    {
        return <<<'PROMPT'
Kamu adalah Roomify AI Concierge, asisten cerdas hotel dan wisata Kota Medan untuk platform pemesanan hotel semantik Roomify (312 hotel & 24 landmark ikonik di Medan).

ATURAN KETAT ANTI-HALUSINASI:
1. HANYA rekomendasikan hotel yang ada di dalam JSON `candidate_hotels` atau `active_hotel` pada konteks yang diberikan. DILARANG mengarang nama hotel, harga, rating, alamat, atau ID hotel.
2. Masukkan ID hotel yang kamu rekomendasikan (maksimal 3-4 hotel paling relevan) ke array `hotel_ids` agar UI menampilkan kartu hotel interaktif (foto, harga, jarak, Rute Google Maps, Detail Kamar).
3. Jika pertanyaan berpusat pada suatu kawasan/landmark yang ada di `matched_location`, isi `location_key` persis sesuai `matched_location.key`. Jika tidak ada, kosongkan string `location_key` ("").
4. Jangan pernah mengarang status pesanan atau data NIK/KTP. Jika user menanyakan status pesanan dan `user_bookings` kosong atau user belum login, arahkan dengan ramah untuk login atau mengetik kode booking `RMF-...`.
5. Gunakan data kebijakan resmi Roomify berikut saat ditanya:
   - Waktu Check-In resmi: pukul 14:00 WIB (14.00 WIB).
   - Waktu Check-Out resmi: pukul 12:00 WIB (12.00 WIB).
   - Identitas wajib: e-KTP fisik 16 digit (NIK dienkripsi aman) atau Paspor.
   - Pembayaran resmi: QRIS Instan (BCA, Mandiri, BRI, BNI, GoPay, OVO, Dana, ShopeePay), Virtual Account (BCA, Mandiri, BRI, BNI), dan Kartu Kredit/Debit (Visa, Mastercard, JCB). E-Voucher terbit otomatis setelah pembayaran lunas.
   - Navigasi & Peta: Tersedia petunjuk rute belokan demi belokan (Google Maps + OSRM) di halaman Cari Hotel dan Detail Hotel, serta Web Semantik SPARQL Playground di `/semantik`.

GAYA KOMUNIKASI & KECERDASAN:
- Jawab dengan Bahasa Indonesia yang hangat, natural, cerdas, dan terstruktur rapi (atau ikuti bahasa user jika user bertanya dalam Bahasa Inggris).
- Gunakan Markdown ringan yang didukung UI: **tebal**, *miring*, `kode`, dan poin `•` atau nomor `1.`. Jangan gunakan tabel Markdown atau HTML mentah.
- Jika user meminta perbandingan hotel, itinerary wisata Medan + rekomendasi hotel strategis, filter kombinasi (misal dekat USU + ada kolam renang + di bawah 500rb), atau pertanyaan lanjutan dari chat sebelumnya, analisis konteks dengan cermat dan berikan alasan rekomendasi yang spesifik (harga, jarak, rating, fasilitas).
- Untuk `action_type`:
  - `"hotel"` jika fokus pada 1 hotel spesifik (`action_param` = hotel_id).
  - `"landmark"` jika fokus pada 1 landmark (`action_param` = landmark_id dari `matched_location.landmark_id` bila ada).
  - `"search"` jika menyarankan eksplorasi daftar hotel (`action_param` = kata kunci pencarian singkat, misal `"usu"` atau `"bintang 5"`).
  - `"dashboard"` jika terkait cek riwayat pesanan user.
  - `"none"` jika tidak butuh tombol aksi khusus.
- Berikan 3-4 `suggestions` singkat (maks 5 kata per chip) yang relevan sebagai pertanyaan lanjutan.
PROMPT;
    }

    /**
     * Send generateContent request for a single Gemini model and parse structured JSON.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>|null
     */
    protected function callModel(string $model, array $payload): ?array
    {
        $baseUrl = rtrim((string) config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');
        $apiKey = (string) config('services.gemini.api_key');
        $timeout = max(5, (int) config('services.gemini.timeout', 15));
        $url = "{$baseUrl}/models/{$model}:generateContent";

        try {
            $options = [
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
            ];
            $caBundle = $this->resolveCaBundle();
            if ($caBundle !== null) {
                $options['verify'] = $caBundle;
            }

            $request = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-goog-api-key' => $apiKey,
            ])->withOptions($options)->connectTimeout(8)->timeout($timeout);

            /** @var Response $response */
            $response = $request->post($url, $payload);

            if ($response->failed()) {
                Log::warning('Gemini API request failed', [
                    'model' => $model,
                    'status' => $response->status(),
                    'error' => $response->json('error.message') ?? mb_substr($response->body(), 0, 200),
                ]);

                return null;
            }

            $parsed = $this->extractStructuredJson($response->json());
            if ($parsed === null) {
                Log::warning('Gemini API returned unparseable structured JSON', [
                    'model' => $model,
                    'finish_reason' => $response->json('candidates.0.finishReason'),
                    'snippet' => mb_substr($response->body(), 0, 250),
                ]);
            }

            return $parsed;
        } catch (ConnectionException $e) {
            Log::warning('Gemini API connection error', [
                'model' => $model,
                'message' => $e->getMessage(),
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::warning('Gemini API unexpected error', [
                'model' => $model,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Extract and decode the structured JSON response from Gemini candidates.
     *
     * @return array<string, mixed>|null
     */
    protected function extractStructuredJson(mixed $body): ?array
    {
        if (! is_array($body)) {
            return null;
        }

        $parts = $body['candidates'][0]['content']['parts'] ?? null;
        if (! is_array($parts)) {
            return null;
        }

        $rawText = '';
        foreach ($parts as $part) {
            if (! is_array($part)) {
                continue;
            }
            if (($part['thought'] ?? false) === true) {
                continue;
            }
            if (isset($part['text']) && is_string($part['text'])) {
                $rawText = trim($part['text']);
                if ($rawText !== '') {
                    break;
                }
            }
        }

        if ($rawText === '') {
            return null;
        }

        // Strip markdown fences if a model wraps JSON despite responseMimeType
        if (str_starts_with($rawText, '```')) {
            $rawText = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $rawText) ?? $rawText;
        }

        $decoded = json_decode(trim($rawText), true);
        if (! is_array($decoded) || empty($decoded['reply']) || ! is_string($decoded['reply'])) {
            return null;
        }

        return $decoded;
    }

    /**
     * Resolve a valid CA bundle on Windows environments where php.ini lacks curl.cainfo.
     */
    protected function resolveCaBundle(): ?string
    {
        $configured = config('services.gemini.ca_bundle');
        if (is_string($configured) && $configured !== '' && is_file($configured)) {
            return $configured;
        }

        $iniCaInfo = trim((string) ini_get('curl.cainfo'));
        $iniCaFile = trim((string) ini_get('openssl.cafile'));
        if ($iniCaInfo !== '' || $iniCaFile !== '') {
            return null;
        }

        if (PHP_OS_FAMILY !== 'Windows') {
            return null;
        }

        $candidates = [
            'C:\\php-8.4\\extras\\ssl\\cacert.pem',
            'C:\\Program Files\\Git\\mingw64\\etc\\ssl\\certs\\ca-bundle.crt',
            'C:\\Program Files (x86)\\Git\\mingw64\\etc\\ssl\\certs\\ca-bundle.crt',
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
