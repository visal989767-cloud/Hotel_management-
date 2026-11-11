<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Database\Seeders\AdditionalRoomTypesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdditionalRoomTypesSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_room_types_and_rooms_are_connected_across_hotel_pages(): void
    {
        $this->seed(AdditionalRoomTypesSeeder::class);
        $this->seed(AdditionalRoomTypesSeeder::class);

        $this->assertDatabaseCount('room_types', 8);
        $this->assertDatabaseCount('rooms', 8);
        $this->assertDatabaseHas('rooms', [
            'room_number' => '501',
            'status' => 'available',
        ]);

        $gardenSuite = RoomType::where('type_name', 'Garden Suite')->firstOrFail();
        $this->assertSame($gardenSuite->room_type_id, Room::where('room_number', '501')->value('room_type_id'));

        $this->actingAs(User::factory()->create())
            ->get(route('room-types.index'))
            ->assertOk()
            ->assertSee('Garden Suite')
            ->assertSee('Penthouse Suite')
            ->assertSee(route('rooms.index', ['room_type_id' => $gardenSuite->room_type_id]), false);

        $this->get(route('rooms.index', ['room_type_id' => $gardenSuite->room_type_id]))
            ->assertOk()
            ->assertSee('501')
            ->assertSee('Garden Suite');

        $this->get(route('bookings.create'))
            ->assertOk()
            ->assertSee('501 - Garden Suite ($110.00/night)');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Garden Suite')
            ->assertSee('Accessible Room')
            ->assertSee('View all 8 rooms');
    }
}
