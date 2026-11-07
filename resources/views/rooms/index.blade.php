@extends('layouts.app')
@section('title', 'Rooms')
@section('content')
<header class="page-hero photo-hero photo-hero--rooms reveal">
    <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
    <div class="hero-top">
        <div>
            <div class="hero-kicker">Accommodation</div>
            <div class="hero-rule"></div>
            <h1 class="hero-title">Rooms</h1>
            <p class="hero-text">Manage room details, availability and rates.</p>
        </div>
        <div class="hero-actions"><a href="{{ route('rooms.create') }}" class="hero-btn hero-btn-primary"><i class="bi bi-plus-lg me-1"></i> Add room</a></div>
    </div>
    <div class="hero-bottom"><div></div><div class="hero-dots" aria-hidden="true"><span></span><span></span><span></span></div></div>
</header>

<div class="panel-card mb-3 reveal" style="--i:1"><div class="card-body">
    <form method="GET" class="row g-2">
        <div class="col-md-4"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by room number..."></div>
        <div class="col-md-3">
            <select name="room_type_id" class="form-select">
                <option value="">All Room Types</option>
                @foreach ($roomTypes as $t)
                    <option value="{{ $t->room_type_id }}" @selected(request('room_type_id') == $t->room_type_id)>{{ $t->type_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i> Filter</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
        </div>
    </form>
</div></div>

<div class="panel-card reveal" style="--i:2">
    <div class="table-responsive">
        <table class="table table-hover align-middle themed-table">
            <thead><tr><th>ID</th><th>Room No.</th><th>Floor</th><th>Room Type</th><th>Guest</th><th>Price / Night</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse ($rooms as $r)
                @php $color = ['available' => 'success', 'occupied' => 'danger', 'maintenance' => 'warning'][$r->status]; @endphp
                <tr>
                    <td>{{ $r->room_id }}</td>
                    <td class="fw-semibold">{{ $r->room_number }}</td>
                    <td>{{ $r->floor }}</td>
                    {{-- Column from related table room_types --}}
                    <td><span class="badge bg-primary-subtle text-primary-emphasis">{{ $r->roomType->type_name }}</span></td>
                    <td>{{ $r->bookings->first()?->guest?->guest_name ?? '—' }}</td>
                    <td>${{ number_format($r->roomType->price_per_night, 2) }}</td>
                    <td><span class="badge bg-{{ $color }}">{{ ucfirst($r->status) }}</span></td>
                    <td class="text-end">
                        <span class="action-buttons">
                        <a href="{{ route('rooms.edit', $r) }}" class="icon-btn icon-btn-edit" title="Edit room" aria-label="Edit room {{ $r->room_number }}"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('rooms.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn icon-btn-delete" title="Delete room" aria-label="Delete room {{ $r->room_number }}"><i class="bi bi-trash"></i></button>
                        </form>
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty-state">No rooms found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $rooms->links() }}</div>
@endsection
