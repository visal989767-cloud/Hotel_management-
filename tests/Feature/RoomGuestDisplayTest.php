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

    public function test_room_cards_have_distinct_photos_and_selectable_featured_details(): void
    {
        $type = RoomType::create([
            'type_name' => 'Garden Suite',
            'price_per_night' => 120,
            'capacity' => 2,
        ]);

        foreach (['301', '302', '303'] as $roomNumber) {
            Room::create([
                'room_number' => $roomNumber,
                'floor' => 3,
                'status' => 'available',
                'room_type_id' => $type->room_type_id,
            ]);
        }

        $response = $this->actingAs(User::factory()->create())
            ->get(route('rooms.index'))
            ->assertOk()
            ->assertSee('id="rooms-hero-image"', false)
            ->assertSee('id="room-spotlight-image"', false)
            ->assertSee('class="rooms-data-strip"', false)
            ->assertSee('rooms-spotlight--reverse', false)
            ->assertSee('class="rooms-photo-index"', false)
            ->assertSee('data-room-gallery-number="302"', false)
            ->assertSee('class="rooms-closing-feature', false)
            ->assertSee('Room to slow down and feel at home.')
            ->assertSee('The collection')
            ->assertSee('class="btn btn-outline-light room-details-button"', false)
            ->assertSee('data-room-edit-url="'.route('rooms.edit', Room::where('room_number', '301')->first()).'"', false)
            ->assertSee('id="room-spotlight-book"', false)
            ->assertSee('Spaces to discover')
            ->assertSee('Browse room photographs')
            ->assertSee('scrollIntoView(', false)
            ->assertSee('heroImage.src = room.roomImage', false);

        preg_match_all('/data-room-image="([^"]+)"/', $response->getContent(), $matches);

        $this->assertCount(3, $matches[1]);
        $this->assertCount(3, array_unique($matches[1]));

        $this->get(route('bookings.create', ['room_id' => Room::where('room_number', '302')->value('room_id')]))
            ->assertOk()
            ->assertSee('<option value="'.Room::where('room_number', '302')->value('room_id').'" selected>', false);
    }

    public function test_room_photo_selection_is_shared_by_the_catalog_and_edit_form(): void
    {
        $type = RoomType::create([
            'type_name' => 'Photo Suite',
            'price_per_night' => 130,
            'capacity' => 2,
        ]);
        $room = Room::create([
            'room_number' => '408',
            'floor' => 4,
            'status' => 'available',
            'room_type_id' => $type->room_type_id,
            'image_key' => 4,
        ]);
        $roomImages = config('room_images');

        $this->actingAs(User::factory()->create())
            ->get(route('rooms.edit', $room))
            ->assertOk()
            ->assertSee('name="image_key" value="4"', false)
            ->assertSee('src="'.str_replace('&', '&amp;', $roomImages[3]).'"', false)
            ->assertSee('data-image-key="4"', false)
            ->assertSee('aria-pressed="true"', false)
            ->assertSee('class="edit-room-principles"', false)
            ->assertSee('Restful comfort')
            ->assertSee('room-editor-card')
            ->assertSee('aria-label="Live room preview"', false)
            ->assertSee('id="roomPreviewNumber"', false)
            ->assertSee('id="roomPreviewStatus"', false)
            ->assertSee('id="roomPreviewPrice"', false)
            ->assertSee('max-height: 285px')
            ->assertSee('overflow-y: auto')
            ->assertSee('Scroll inside the gallery to browse all photographs')
            ->assertDontSee('room-type-preview h-100');

        $this->put(route('rooms.update', $room), [
            'room_number' => '408',
            'floor' => 4,
            'status' => 'available',
            'room_type_id' => $type->room_type_id,
            'description' => 'A newly refreshed suite.',
            'image_key' => 5,
        ])->assertRedirect(route('rooms.index'));

        $this->assertDatabaseHas('rooms', [
            'room_id' => $room->room_id,
            'image_key' => 5,
        ]);

        $this->get(route('rooms.index'))
            ->assertOk()
            ->assertSee('data-room-image="'.str_replace('&', '&amp;', $roomImages[4]).'"', false)
            ->assertSee('src="'.str_replace('&', '&amp;', $roomImages[4]).'"', false);
    }
}
