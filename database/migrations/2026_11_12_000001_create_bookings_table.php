<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('hotel_id');
            $table->string('hotel_name');
            $table->string('hotel_foto')->nullable();
            $table->string('room_type');
            $table->integer('room_price');
            $table->date('checkin_date');
            $table->string('checkin_time')->default('14:00 WIB');
            $table->date('checkout_date');
            $table->string('checkout_time')->default('12:00 WIB');
            $table->integer('nights')->default(1);
            $table->integer('guests')->default(1);
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->string('no_ktp');
            $table->text('special_requests')->nullable();
            $table->integer('subtotal');
            $table->integer('tax');
            $table->integer('total_price');
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
