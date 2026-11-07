<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_id');
            $table->foreignId('guest_id')
                ->constrained('guests', 'guest_id')
                ->restrictOnDelete();
            $table->foreignId('room_id')
                ->constrained('rooms', 'room_id')
                ->restrictOnDelete();
            $table->date('check_in');
            $table->date('check_out');
            $table->enum('status', ['booked', 'checked_in', 'checked_out', 'cancelled'])->default('booked');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
