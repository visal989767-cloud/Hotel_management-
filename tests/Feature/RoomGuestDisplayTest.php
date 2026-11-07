<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomGuestDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_rooms_page_shows_guest_for_active_booking_only(): void
    {
        $roomType = RoomType::create([
            'type_name' => 'Double',
            'price_per_night' => 50,
            'capacity' => 2,
        ]);
        $room = Room::create([
            'room_number' => '201',
            'floor' => 2,
            'status' => 'occupied',
            'room_type_id' => $roomType->room_type_id,
        ]);
        $currentGuest = Guest::create([
            'guest_name' => 'Sal',
            'email' => 'sal@example.com',
            'gender' => 'Other',
        ]);
        $pastGuest = Guest::create([
            'guest_name' => 'Past Guest',
            'email' => 'past@example.com',
            'gender' => 'Other',
        ]);

        Booking::create([
            'guest_id' => $currentGuest->guest_id,
            'room_id' => $room->room_id,
            'check_in' => now()->toDateString(),
            'check_out' => now()->addDay()->toDateString(),
            'status' => 'checked_in',
            'total_price' => 50,
        ]);
        Booking::create([
            'guest_id' => $pastGuest->guest_id,
            'room_id' => $room->room_id,
            'check_in' => now()->subDays(3)->toDateString(),
            'check_out' => now()->subDay()->toDateString(),
            'status' => 'checked_out',
            'total_price' => 100,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('rooms.index'))
            ->assertOk()
            ->assertSee('Guest')
            ->assertSee('Sal')
            ->assertDontSee('Past Guest');
    }
}
