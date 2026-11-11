<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTypeCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_type_page_shows_photo_catalog_and_room_actions(): void
    {
        $type = RoomType::create([
            'type_name' => 'Garden Suite',
            'description' => 'A quiet suite overlooking the gardens.',
            'price_per_night' => 145,
            'capacity' => 3,
        ]);
        Room::create([
            'room_number' => '501',
            'floor' => 5,
            'status' => 'available',
            'room_type_id' => $type->room_type_id,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('room-types.index'))
            ->assertOk()
            ->assertSee('Find your perfect stay')
            ->assertSee('Garden Suite')
            ->assertSee('A quiet suite overlooking the gardens.')
            ->assertSee('All room types')
            ->assertSee('View rooms')
            ->assertSee(route('rooms.index', ['room_type_id' => $type->room_type_id]), false)
            ->assertSee(route('room-types.edit', $type), false)
            ->assertSee('name="search"', false)
            ->assertSee('name="capacity"', false)
            ->assertSee('id="featured-hero-image"', false)
            ->assertSee('id="featured-room-image"', false)
            ->assertSee('id="featured-hero-name"', false)
            ->assertSee('id="featured-room-price"', false)
            ->assertSee('id="featured-room-capacity"', false)
            ->assertSee('id="featured-room-count"', false)
            ->assertSee('class="type-details-button"', false)
            ->assertSee('data-room-name="Garden Suite"', false)
            ->assertSee('photo-1566073771259-6a8506099945', false);
    }

    public function test_room_type_page_filters_catalog_from_the_database(): void
    {
        RoomType::create([
            'type_name' => 'Single Room',
            'description' => 'A compact single room.',
            'price_per_night' => 35,
            'capacity' => 1,
        ]);
        RoomType::create([
            'type_name' => 'Family Suite',
            'description' => 'Room for the whole family.',
            'price_per_night' => 150,
            'capacity' => 4,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('room-types.index', ['capacity' => 4]))
            ->assertOk()
            ->assertSee('Family Suite')
            ->assertDontSee('Single Room')
            ->assertSee('value="4" selected', false);
    }

    public function test_room_type_cards_use_distinct_photos_and_update_the_featured_image(): void
    {
        foreach (range(1, 10) as $number) {
            RoomType::create([
                'type_name' => 'Room Type '.$number,
                'price_per_night' => 50 + $number,
                'capacity' => 2,
            ]);
        }

        $response = $this->actingAs(User::factory()->create())
            ->get(route('room-types.index'))
            ->assertOk()
            ->assertSee('featuredHeroImage.src = room.roomImage', false);

        preg_match_all('/data-room-image="([^"]+)"/', $response->getContent(), $matches);

        $this->assertCount(10, $matches[1]);
        $this->assertCount(10, array_unique($matches[1]));
    }
}
