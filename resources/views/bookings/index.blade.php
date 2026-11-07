@extends('layouts.app')
@section('title', 'Bookings')
@section('content')
<header class="page-hero photo-hero photo-hero--bookings reveal">
    <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
    <div class="hero-top">
        <div>
            <div class="hero-kicker">Reservations</div>
            <div class="hero-rule"></div>
            <h1 class="hero-title">Bookings</h1>
            <p class="hero-text">Review upcoming stays and manage reservation details.</p>
        </div>
        <div class="hero-actions"><a href="{{ route('bookings.create') }}" class="hero-btn hero-btn-primary"><i class="bi bi-calendar-plus me-1"></i> New booking</a></div>
    </div>
    <div class="hero-bottom"><div></div><div class="hero-dots" aria-hidden="true"><span></span><span></span><span></span></div></div>
</header>

<div class="panel-card mb-3 reveal" style="--i:1"><div class="card-body">
    <form method="GET" class="row g-2">
        <div class="col-md-6"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by guest name or room number..."></div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i> Filter</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
        </div>
    </form>
</div></div>

<div class="panel-card reveal" style="--i:2">
    <div class="table-responsive">
        <table class="table table-hover align-middle themed-table">
            <thead><tr><th>ID</th><th>Guest</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Nights</th><th>Total</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse ($bookings as $b)
                <tr>
                    <td>{{ $b->booking_id }}</td>
                    <td class="fw-semibold">{{ $b->guest->guest_name }}</td>
                    <td>{{ $b->room->room_number }} <small class="text-muted">({{ $b->room->roomType->type_name }})</small></td>
                    <td>{{ $b->check_in->format('M d, Y') }}</td>
                    <td>{{ $b->check_out->format('M d, Y') }}</td>
                    <td>{{ $b->nights }}</td>
                    <td>${{ number_format($b->total_price, 2) }}</td>
                    <td>@include('bookings._status', ['status' => $b->status])</td>
                    <td class="text-end">
                        <span class="action-buttons">
                        <a href="{{ route('bookings.edit', $b) }}" class="icon-btn icon-btn-edit" title="Edit booking" aria-label="Edit booking {{ $b->booking_id }}"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('bookings.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this booking?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn icon-btn-delete" title="Delete booking" aria-label="Delete booking {{ $b->booking_id }}"><i class="bi bi-trash"></i></button>
                        </form>
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="empty-state">No bookings found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $bookings->links() }}</div>
@endsection
