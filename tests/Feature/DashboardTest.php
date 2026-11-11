<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_room_types_upcoming_arrivals_and_booking_history(): void
    {
        $roomType = RoomType::create([
            'type_name' => 'Garden Suite',
            'description' => 'A spacious suite with a garden view.',
            'price_per_night' => 120,
            'capacity' => 3,
        ]);
        $room = Room::create([
            'room_number' => '301',
            'floor' => 3,
            'status' => 'available',
            'room_type_id' => $roomType->room_type_id,
        ]);
        $guest = Guest::create([
            'guest_name' => 'Jamie Guest',
            'email' => 'jamie@example.com',
            'gender' => 'Other',
        ]);

        Booking::create([
            'guest_id' => $guest->guest_id,
            'room_id' => $room->room_id,
            'check_in' => today()->addDays(2),
            'check_out' => today()->addDays(4),
            'status' => 'booked',
            'total_price' => 240,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Room Types')
            ->assertSee('Garden Suite')
            ->assertSee('Upcoming Arrivals')
            ->assertSee('Jamie Guest')
            ->assertSee('Booking History')
            ->assertSee('Room Overview');
    }

    public function test_dashboard_sort_and_calendar_controls_use_selected_values(): void
    {
        foreach ([
            ['type_name' => 'Budget Room', 'price_per_night' => 40],
            ['type_name' => 'Classic Room', 'price_per_night' => 80],
            ['type_name' => 'Premium Suite', 'price_per_night' => 160],
        ] as $roomType) {
            RoomType::create($roomType + ['capacity' => 2]);
        }

        $month = now()->subMonthNoOverflow();

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard', ['sort' => 'price_desc', 'month' => $month->format('Y-m')]))
            ->assertOk()
            ->assertSeeInOrder(['Premium Suite', 'Classic Room', 'Budget Room'])
            ->assertSee('value="price_desc" selected', false)
            ->assertSee($month->format('M'))
            ->assertSee($month->format('Y'))
            ->assertSee('month='.$month->copy()->subMonth()->format('Y-m').'&amp;sort=price_desc', false)
            ->assertSee('month='.$month->copy()->addMonth()->format('Y-m').'&amp;sort=price_desc', false);
    }

    public function test_dashboard_rejects_unsupported_sort_and_calendar_values(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard', ['sort' => 'unsupported', 'month' => 'not-a-month']))
            ->assertRedirect();
    }

    public function test_room_overview_actions_open_all_available_and_occupied_rooms(): void
    {
        $roomType = RoomType::create([
            'type_name' => 'Overview Type',
            'price_per_night' => 80,
            'capacity' => 2,
        ]);
        Room::create([
            'room_number' => '101',
            'floor' => 1,
            'status' => 'available',
            'room_type_id' => $roomType->room_type_id,
        ]);
        Room::create([
            'room_number' => '102',
            'floor' => 1,
            'status' => 'available',
            'room_type_id' => $roomType->room_type_id,
        ]);
        Room::create([
            'room_number' => '201',
            'floor' => 2,
            'status' => 'occupied',
            'room_type_id' => $roomType->room_type_id,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('href="'.route('rooms.index').'" style="--occupancy: 33%" aria-label="View all 3 rooms"', false)
            ->assertSee('href="'.route('rooms.index', ['status' => 'available']).'" aria-label="View 2 available rooms"', false)
            ->assertSee('href="'.route('rooms.index', ['status' => 'occupied']).'" aria-label="View 1 occupied rooms"', false)
            ->assertSee('href="'.route('rooms.index', ['status' => 'occupied']).'" aria-label="View occupied rooms for 33 percent occupancy"', false);

        $this->get(route('rooms.index', ['status' => 'available']))
            ->assertOk()
            ->assertSee('101')
            ->assertSee('102')
            ->assertDontSee('201')
            ->assertSee('<option value="available" selected>Available</option>', false);

        $this->get(route('rooms.index', ['status' => 'occupied']))
            ->assertOk()
            ->assertSee('201')
            ->assertDontSee('101')
            ->assertSee('<option value="occupied" selected>Occupied</option>', false);
    }
}
