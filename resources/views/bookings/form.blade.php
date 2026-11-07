@extends('layouts.app')
@section('title', $booking->exists ? 'Edit Booking' : 'New Booking')
@section('content')
<header class="page-hero photo-hero photo-hero--bookings form-photo-hero reveal">
    <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
    <div class="hero-top">
        <div><div class="hero-kicker">Reservations</div><div class="hero-rule"></div><h1 class="hero-title">{{ $booking->exists ? 'Edit booking' : 'Create a booking' }}</h1><p class="hero-text">Choose the guest, room and dates for this stay.</p></div>
        <div class="hero-actions"><a href="{{ route('bookings.index') }}" class="hero-btn hero-btn-ghost"><i class="bi bi-arrow-left me-1"></i> Back to bookings</a></div>
    </div>
</header>
<div class="row justify-content-center"><div class="col-md-8">
<div class="card reveal" style="--i:1"><div class="card-body p-4">
    <h4 class="mb-4">{{ $booking->exists ? 'Edit Booking' : 'New Booking' }}</h4>
    <form method="POST" action="{{ $booking->exists ? route('bookings.update', $booking) : route('bookings.store') }}">
        @csrf
        @if ($booking->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Guest</label>
                <select name="guest_id" class="form-select @error('guest_id') is-invalid @enderror" required>
                    <option value="">-- Select Guest --</option>
                    @foreach ($guests as $g)
                        <option value="{{ $g->guest_id }}" @selected(old('guest_id', $booking->guest_id) == $g->guest_id)>{{ $g->guest_name }} ({{ $g->email }})</option>
                    @endforeach
                </select>
                @error('guest_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Room</label>
                <select name="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                    <option value="">-- Select Room --</option>
                    @foreach ($rooms as $r)
                        <option value="{{ $r->room_id }}" @selected(old('room_id', $booking->room_id) == $r->room_id)>
                            {{ $r->room_number }} - {{ $r->roomType->type_name }} (${{ number_format($r->roomType->price_per_night, 2) }}/night)
                        </option>
                    @endforeach
                </select>
                @error('room_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Check-in</label>
                <input type="date" name="check_in" value="{{ old('check_in', $booking->check_in?->format('Y-m-d')) }}" class="form-control @error('check_in') is-invalid @enderror" required>
                @error('check_in') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Check-out</label>
                <input type="date" name="check_out" value="{{ old('check_out', $booking->check_out?->format('Y-m-d')) }}" class="form-control @error('check_out') is-invalid @enderror" required>
                @error('check_out') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected(old('status', $booking->status) == $s)>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="3" class="form-control">{{ old('notes', $booking->notes) }}</textarea>
        </div>
        <p class="text-muted small">Total price is calculated automatically (nights × room type price).</p>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>{{ $booking->exists ? 'Save changes' : 'Save booking' }}</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Cancel</a>
        </div>
    </form>
</div></div>
</div></div>
@endsection
