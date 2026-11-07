<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $guests = Guest::withCount('bookings')
            ->when($request->search, function ($q, $s) {
                $q->where(function ($w) use ($s) {
                    $w->where('guest_name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%")
                        ->orWhere('phone', 'like', "%{$s}%");
                });
            })
            ->when($request->gender, fn ($q, $g) => $q->whereRaw('LOWER(TRIM(gender)) = ?', [mb_strtolower(trim($g))]))
            ->orderByDesc('guest_id')
            ->paginate(10)
            ->withQueryString();

        return view('guests.index', compact('guests'));
    }

    public function create()
    {
        return view('guests.form', ['guest' => new Guest()]);
    }

    public function store(Request $request)
    {
        Guest::create($this->validated($request));

        return redirect()->route('guests.index')->with('success', 'Guest created.');
    }

    public function edit(Guest $guest)
    {
        return view('guests.form', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $guest->update($this->validated($request, $guest));

        return redirect()->route('guests.index')->with('success', 'Guest updated.');
    }

    public function destroy(Guest $guest)
    {
        if ($guest->bookings()->exists()) {
            return back()->with('error', 'Cannot delete: this guest has bookings.');
        }

        $guest->delete();

        return redirect()->route('guests.index')->with('success', 'Guest deleted.');
    }

    private function validated(Request $request, ?Guest $guest = null): array
    {
        return $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:guests,email,'.($guest?->guest_id ?? 'NULL').',guest_id'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'id_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
