<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Database\Seeders\AdditionalRoomsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdditionalRoomsSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_additional_rooms_are_idempotent_and_available_across_hotel_pages(): void
    {
        foreach ([
            ['type_name' => 'Single', 'price_per_night' => 30, 'capacity' => 1],
            ['type_name' => 'Double', 'price_per_night' => 50, 'capacity' => 2],
            ['type_name' => 'Deluxe', 'price_per_night' => 80, 'capacity' => 3],
            ['type_name' => 'Suite', 'price_per_night' => 150, 'capacity' => 4],
        ] as $type) {
            RoomType::create($type);
        }

        $this->seed(AdditionalRoomsSeeder::class);
        $this->seed(AdditionalRoomsSeeder::class);

        $this->assertDatabaseCount('rooms', 7);
        $this->assertSame(
            ['103', '104', '203', '204', '303', '402', '403'],
            Room::orderBy('room_number')->pluck('room_number')->all(),
        );
        $this->assertDatabaseHas('rooms', ['room_number' => '403', 'status' => 'available']);

        $this->actingAs(User::factory()->create())
            ->get(route('rooms.index'))
            ->assertOk()
            ->assertSee('103')
            ->assertSee('403');

        $this->get(route('room-types.index'))
            ->assertOk()
            ->assertSee('Suite')
            ->assertSee('2 rooms');

        $this->get(route('bookings.create'))
            ->assertOk()
            ->assertSee('403 - Suite ($150.00/night)');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Suite')
            ->assertSee('View all 7 rooms');
    }
}
