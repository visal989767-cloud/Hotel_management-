<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\RoomType;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'roomTypes' => RoomType::count(),
            'rooms' => Room::count(),
            'available' => Room::where('status', 'available')->count(),
            'occupied' => Room::where('status', 'occupied')->count(),
            'guests' => Guest::count(),
            'bookings' => Booking::count(),
            'revenue' => Booking::whereIn('status', ['booked', 'checked_in', 'checked_out'])->sum('total_price'),
            'recent' => Booking::with(['guest', 'room.roomType'])->latest('booking_id')->take(5)->get(),
        ]);
    }
}
