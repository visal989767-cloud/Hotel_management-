<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public const STATUSES = ['available', 'occupied', 'maintenance'];

    public function index(Request $request)
    {
        $rooms = Room::with([
            'roomType',
            'bookings' => function ($query) {
                $query->with('guest')
                    ->where(function ($query) {
                        $query->where('status', 'checked_in')
                            ->orWhere(function ($query) {
                                $query->where('status', 'booked')
                                    ->whereDate('check_out', '>=', now()->toDateString());
                            });
                    })
                    ->orderByRaw("CASE status WHEN 'checked_in' THEN 0 ELSE 1 END")
                    ->orderBy('check_in');
            },
        ])
            ->when($request->search, fn ($q, $s) => $q->where('room_number', 'like', "%{$s}%"))
            ->when($request->room_type_id, fn ($q, $id) => $q->where('room_type_id', $id))
            ->when($request->status, fn ($q, $st) => $q->where('status', $st))
            ->orderBy('room_number')
            ->paginate(10)
            ->withQueryString();

        return view('rooms.index', [
            'rooms' => $rooms,
            'roomTypes' => RoomType::orderBy('type_name')->get(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function create()
    {
        return view('rooms.form', [
            'room' => new Room(['status' => 'available', 'floor' => 1]),
            'roomTypes' => RoomType::orderBy('type_name')->get(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        Room::create($this->validated($request));

        return redirect()->route('rooms.index')->with('success', 'Room created.');
    }

    public function edit(Room $room)
    {
        return view('rooms.form', [
            'room' => $room,
            'roomTypes' => RoomType::orderBy('type_name')->get(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $room->update($this->validated($request, $room));

        return redirect()->route('rooms.index')->with('success', 'Room updated.');
    }

    public function destroy(Room $room)
    {
        if ($room->bookings()->exists()) {
            return back()->with('error', 'Cannot delete: this room has bookings.');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted.');
    }

    private function validated(Request $request, ?Room $room = null): array
    {
        return $request->validate([
            'room_number' => ['required', 'string', 'max:20', 'unique:rooms,room_number,'.($room?->room_id ?? 'NULL').',room_id'],
            'floor' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
            'room_type_id' => ['required', 'exists:room_types,room_type_id'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
