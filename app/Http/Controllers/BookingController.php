<?php

namespace App\Http\Controllers;

use App\Services\FusekiClient;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        protected FusekiClient $fuseki,
    ) {}

    public function showForm(string $id, Request $request)
    {
        $p = 'PREFIX roomify: <https://roomify.example/ontology#>';

        $rows = $this->fuseki->select("$p
            SELECT ?nama ?alamat ?bintang ?harga ?foto WHERE {
              roomify:$id a roomify:Hotel ;
                roomify:name ?nama ;
                roomify:address ?alamat .
              OPTIONAL { roomify:$id roomify:startPrice ?harga }
              OPTIONAL { roomify:$id roomify:starRating ?bintang }
              OPTIONAL { roomify:$id roomify:hasPhoto ?ph . ?ph roomify:photoUrl ?foto }
            } LIMIT 1");

        if (! $rows) abort(404, 'Hotel tidak ditemukan');

        $h = $rows[0];

        $kamar = $this->fuseki->select("$p
            SELECT ?tipe ?harga ?kapasitas WHERE {
              roomify:$id roomify:hasRoom ?r .
              ?r roomify:roomType ?tipe ;
                 roomify:pricePerNight ?harga ;
                 roomify:maxGuests ?kapasitas .
            } ORDER BY ASC(?harga)");

        if (empty($kamar)) {
            return redirect()->route('hotel.detail', ['id' => $id])
                ->with('error', 'Data kamar belum tersedia untuk reservasi online.');
        }

        $hotel = [
            'nama'       => $h['nama'],
            'alamat'     => $h['alamat'],
            'starRating' => $h['bintang'] ?? null,
            'startPrice' => !empty($h['harga']) ? (int) $h['harga'] : 0,
            'foto'       => $h['foto'] ?? '/images/placeholder.svg',
            'kamar'      => $kamar,
        ];

        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');

        return view('booking.form', [
            'hotel'         => $hotel,
            'id'            => $id,
            'checkin'       => $request->input('checkin', $today),
            'checkout'      => $request->input('checkout', $tomorrow),
            'checkin_time'  => '14:00 WIB',
            'checkout_time' => '12:00 WIB',
            'guests'        => $request->input('guests', 1),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id'         => 'required|string',
            'room_type'        => 'required|string',
            'checkin'          => 'required|date',
            'checkout'         => 'required|date|after:checkin',
            'guests'           => 'required|integer|min:1',
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone'            => 'required|string|max:30',
            'no_ktp'           => 'required|string|min:16|max:20',
            'special_requests' => 'nullable|string|max:1000',
            'room_price'       => 'required|numeric|min:0',
            'hotel_name'       => 'required|string',
            'hotel_foto'       => 'nullable|string',
        ], [
            'no_ktp.required' => 'Nomor Identitas (KTP/NIK) wajib diisi untuk verifikasi reservasi.',
            'no_ktp.min'      => 'Nomor KTP harus terdiri dari minimal 16 digit.',
        ]);

        if ((float) $validated['room_price'] <= 0) {
            return redirect()->route('hotel.detail', ['id' => $validated['hotel_id']])
                ->with('error', 'Hotel ini belum memiliki tarif kamar yang valid untuk reservasi online.');
        }

        $kamar = $this->fuseki->select("
            PREFIX roomify: <https://roomify.example/ontology#>
            SELECT ?tipe WHERE {
              roomify:{$validated['hotel_id']} roomify:hasRoom ?r .
            } LIMIT 1");

        if (empty($kamar)) {
            return redirect()->route('hotel.detail', ['id' => $validated['hotel_id']])
                ->with('error', 'Data kamar belum tersedia untuk reservasi online.');
        }

        $checkin  = \Carbon\Carbon::parse($validated['checkin']);
        $checkout = \Carbon\Carbon::parse($validated['checkout']);
        $nights   = max(1, $checkin->diffInDays($checkout));
        $bookingCode = 'RMF-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        session(['booking' => array_merge($validated, [
            'booking_code'  => $bookingCode,
            'checkin_time'  => '14:00 WIB',
            'checkout_time' => '12:00 WIB',
            'nights'        => $nights,
            'subtotal'      => (int) $validated['room_price'] * $nights,
        ])]);

        return redirect()->route('payment.show');
    }

    public function confirm(Request $request)
    {
        $booking = session('booking');
        $invoice = session('invoice_id');

        session()->forget(['booking', 'invoice_id']);

        return view('payment.success', compact('booking', 'invoice'));
    }
}
