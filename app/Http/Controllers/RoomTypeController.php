<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $roomTypes = RoomType::withCount('rooms')
            ->when($request->search, fn ($q, $s) => $q->where('type_name', 'like', "%{$s}%"))
            ->when($request->capacity, fn ($q, $c) => $q->where('capacity', $c))
            ->orderBy('room_type_id')
            ->paginate(10)
            ->withQueryString();

        return view('room_types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('room_types.form', ['roomType' => new RoomType()]);
    }

    public function store(Request $request)
    {
        RoomType::create($this->validated($request));

        return redirect()->route('room-types.index')->with('success', 'Room type created.');
    }

    public function edit(RoomType $room_type)
    {
        return view('room_types.form', ['roomType' => $room_type]);
    }

    public function update(Request $request, RoomType $room_type)
    {
        $room_type->update($this->validated($request, $room_type));

        return redirect()->route('room-types.index')->with('success', 'Room type updated.');
    }

    public function destroy(RoomType $room_type)
    {
        if ($room_type->rooms()->exists()) {
            return back()->with('error', 'Cannot delete: this room type still has rooms.');
        }

        $room_type->delete();

        return redirect()->route('room-types.index')->with('success', 'Room type deleted.');
    }

    private function validated(Request $request, ?RoomType $roomType = null): array
    {
        return $request->validate([
            'type_name' => ['required', 'string', 'max:255', 'unique:room_types,type_name,'.($roomType?->room_type_id ?? 'NULL').',room_type_id'],
            'description' => ['nullable', 'string'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);
    }
}
