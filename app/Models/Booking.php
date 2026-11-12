<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'hotel_id',
        'hotel_name',
        'hotel_foto',
        'room_type',
        'room_price',
        'checkin_date',
        'checkin_time',
        'checkout_date',
        'checkout_time',
        'nights',
        'guests',
        'guest_name',
        'guest_email',
        'guest_phone',
        'no_ktp',
        'special_requests',
        'subtotal',
        'tax',
        'total_price',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'checkout_date' => 'date',
        'room_price' => 'integer',
        'nights' => 'integer',
        'guests' => 'integer',
        'subtotal' => 'integer',
        'tax' => 'integer',
        'total_price' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
