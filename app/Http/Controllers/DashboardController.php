<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'sort' => ['sometimes', 'in:price_asc,price_desc,rooms_desc,name'],
            'month' => ['sometimes', 'date_format:Y-m'],
        ]);
        $monthStart = isset($filters['month'])
            ? Carbon::createFromFormat('!Y-m', $filters['month'])
            : now()->startOfMonth();
        $sort = $filters['sort'] ?? 'price_asc';
        $roomTypeQuery = RoomType::withCount('rooms');

        match ($sort) {
            'price_desc' => $roomTypeQuery->orderByDesc('price_per_night'),
            'rooms_desc' => $roomTypeQuery->orderByDesc('rooms_count'),
            'name' => $roomTypeQuery->orderBy('type_name'),
            default => $roomTypeQuery->orderBy('price_per_night'),
        };

        return view('dashboard', [
            'roomTypes' => $roomTypeQuery->orderBy('room_type_id')->get(),
            'roomTypeSort' => $sort,
            'monthStart' => $monthStart,
            'rooms' => Room::count(),
            'available' => Room::where('status', 'available')->count(),
            'occupied' => Room::where('status', 'occupied')->count(),
            'guests' => Guest::count(),
            'recent' => Booking::with(['guest', 'room.roomType'])->latest('booking_id')->take(5)->get(),
            'upcomingArrivals' => Booking::with(['guest', 'room'])
                ->where('status', 'booked')
                ->whereDate('check_in', '>=', today())
                ->orderBy('check_in')
                ->take(3)
                ->get(),
            'calendarBookings' => Booking::whereIn('status', ['booked', 'checked_in'])
                ->whereBetween('check_in', [$monthStart->toDateString(), $monthStart->copy()->endOfMonth()->toDateString()])
                ->get(['check_in']),
        ]);
    }

    public function settings()
    {
        $settings = SiteSetting::query()->first()?->toArray() ?? [
            'site_name' => 'Ocean Resort',
            'tagline' => 'Stay Better, Feel Home',
            'email' => 'hello@oceanresort.com',
            'phone' => '(001) 390-0000',
            'address' => '2464 Royal Ln, Mesa, New Jersey 46463',
            'location' => 'Setec Institute',
            'check_in' => '15:00',
            'check_out' => '11:00',
        ];

        return view('settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $settings = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'tagline' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'check_in' => ['sometimes', 'nullable', 'date_format:H:i'],
            'check_out' => ['sometimes', 'nullable', 'date_format:H:i'],
        ]);

        SiteSetting::query()->updateOrCreate(['id' => 1], $settings);

        return back()->with('success', 'Ocean Resort settings saved successfully.');
    }
}
