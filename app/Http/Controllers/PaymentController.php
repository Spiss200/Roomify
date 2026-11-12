<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show()
    {
        $booking = session('booking');

        if (! $booking) {
            return redirect()->route('search')->with('error', 'Tidak ada data pemesanan.');
        }

        return view('payment.show', compact('booking'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:bca,mandiri,bni,va,gopay,ovo',
        ]);

        $booking = session('booking');

        if (! $booking) {
            return redirect()->route('search')->with('error', 'Sesi pemesanan telah berakhir.');
        }

        $invoiceId = $booking['booking_code'] ?? ('INV-' . time());
        $tax       = (int) round($booking['subtotal'] * 0.10);
        $total     = $booking['subtotal'] + $tax;

        // Persist booking to Database
        $bookingModel = Booking::create([
            'booking_code'     => $booking['booking_code'] ?? ('RMF-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4))),
            'user_id'          => Auth::id(), // null if guest, or user_id if logged in
            'hotel_id'         => $booking['hotel_id'],
            'hotel_name'       => $booking['hotel_name'],
            'hotel_foto'       => $booking['hotel_foto'] ?? null,
            'room_type'        => $booking['room_type'],
            'room_price'       => (int) $booking['room_price'],
            'checkin_date'     => $booking['checkin'],
            'checkin_time'     => $booking['checkin_time'] ?? '14:00 WIB',
            'checkout_date'    => $booking['checkout'],
            'checkout_time'    => $booking['checkout_time'] ?? '12:00 WIB',
            'nights'           => (int) $booking['nights'],
            'guests'           => (int) $booking['guests'],
            'guest_name'       => $booking['name'],
            'guest_email'      => $booking['email'],
            'guest_phone'      => $booking['phone'],
            'no_ktp'           => $booking['no_ktp'] ?? '1271012345678901',
            'special_requests' => $booking['special_requests'] ?? null,
            'subtotal'         => (int) $booking['subtotal'],
            'tax'              => $tax,
            'total_price'      => $total,
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'paid',
        ]);

        session([
            'invoice_id'      => $bookingModel->booking_code,
            'booking_id'      => $bookingModel->id,
            'payment_method'  => $request->payment_method,
            'payment_tax'     => $tax,
            'payment_total'   => $total,
        ]);

        return view('payment.success', [
            'booking'        => $booking,
            'bookingRecord'  => $bookingModel,
            'invoice'        => $bookingModel->booking_code,
            'payment_method' => $request->payment_method,
            'tax'            => $tax,
            'total'          => $total,
        ]);
    }

    public function success()
    {
        $booking = session('booking');
        $invoice = session('invoice_id');

        if (! $invoice) {
            return redirect()->route('search');
        }

        return view('payment.success', [
            'booking'        => $booking,
            'invoice'        => $invoice,
            'payment_method' => session('payment_method'),
            'tax'            => session('payment_tax'),
            'total'          => session('payment_total'),
        ]);
    }
}
