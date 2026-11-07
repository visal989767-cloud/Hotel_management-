<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public const STATUSES = ['booked', 'checked_in', 'checked_out', 'cancelled'];

    public function index(Request $request)
    {
        $bookings = Booking::with(['guest', 'room.roomType'])
            ->when($request->search, function ($q, $s) {
                $q->where(function ($w) use ($s) {
                    $w->whereHas('guest', fn ($g) => $g->where('guest_name', 'like', "%{$s}%"))
                        ->orWhereHas('room', fn ($r) => $r->where('room_number', 'like', "%{$s}%"));
                });
            })
            ->when($request->status, fn ($q, $st) => $q->where('status', $st))
            ->orderByDesc('booking_id')
            ->paginate(10)
            ->withQueryString();

        return view('bookings.index', ['bookings' => $bookings, 'statuses' => self::STATUSES]);
    }

    public function create()
    {
        return view('bookings.form', $this->formData(new Booking(['status' => 'booked'])));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['total_price'] = $this->calculateTotal($data);

        $booking = Booking::create($data);
        $this->syncRoomStatus($booking);

        return redirect()->route('bookings.index')->with('success', 'Booking created.');
    }

    public function edit(Booking $booking)
    {
        return view('bookings.form', $this->formData($booking));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $this->validated($request, $booking);
        $data['total_price'] = $this->calculateTotal($data);

        $booking->update($data);
        $this->syncRoomStatus($booking);

        return redirect()->route('bookings.index')->with('success', 'Booking updated.');
    }

    public function destroy(Booking $booking)
    {
        $room = $booking->room;
        $booking->delete();

        if ($room->status === 'occupied' && ! $room->bookings()->where('status', 'checked_in')->exists()) {
            $room->update(['status' => 'available']);
        }

        return redirect()->route('bookings.index')->with('success', 'Booking deleted.');
    }

    private function formData(Booking $booking): array
    {
        return [
            'booking' => $booking,
            'guests' => Guest::orderBy('guest_name')->get(),
            'rooms' => Room::with('roomType')->orderBy('room_number')->get(),
            'statuses' => self::STATUSES,
        ];
    }

    private function validated(Request $request, ?Booking $booking = null): array
    {
        $data = $request->validate([
            'guest_id' => ['required', 'exists:guests,guest_id'],
            'room_id' => ['required', 'exists:rooms,room_id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
            'notes' => ['nullable', 'string'],
        ]);

        // Prevent double booking of the same room for overlapping dates.
        if (in_array($data['status'], ['booked', 'checked_in'])) {
            $overlap = Booking::where('room_id', $data['room_id'])
                ->whereIn('status', ['booked', 'checked_in'])
                ->when($booking, fn ($q) => $q->where('booking_id', '!=', $booking->booking_id))
                ->where('check_in', '<', $data['check_out'])
                ->where('check_out', '>', $data['check_in'])
                ->exists();

            if ($overlap) {
                throw ValidationException::withMessages([
                    'room_id' => 'This room is already booked for the selected dates.',
                ]);
            }
        }

        return $data;
    }

    private function calculateTotal(array $data): float
    {
        $room = Room::with('roomType')->findOrFail($data['room_id']);
        $nights = max(1, (int) abs(\Illuminate\Support\Carbon::parse($data['check_in'])->diffInDays($data['check_out'])));

        return $nights * (float) $room->roomType->price_per_night;
    }

    private function syncRoomStatus(Booking $booking): void
    {
        $room = $booking->room;

        if ($booking->status === 'checked_in') {
            $room->update(['status' => 'occupied']);
        } elseif (in_array($booking->status, ['checked_out', 'cancelled']) && $room->status === 'occupied') {
            if (! $room->bookings()->where('status', 'checked_in')->exists()) {
                $room->update(['status' => 'available']);
            }
        }
    }
}
