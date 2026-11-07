@extends('layouts.app')
@section('title', 'Room Types')
@section('content')

<style>
    .types-hero .hero-slide:nth-child(1) { background-image: url('https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1800&q=80'); }
    .types-hero .hero-slide:nth-child(2) { background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1800&q=80'); }
    .types-hero .hero-slide:nth-child(3) { background-image: url('https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1800&q=80'); }

    .filter-bar { padding: 1.25rem 1.5rem; }
    .filter-bar .form-label { font-weight: 500; font-size: .85rem; color: var(--muted); margin-bottom: .35rem; }
    .filter-bar .search-wrap { position: relative; }
    .filter-bar .search-wrap .bi { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
    .filter-bar .search-wrap .form-control { padding-left: 2.4rem; }

    .type-id { color: var(--brass-dark); font-weight: 600; }
    .type-name { font-family: 'Cormorant Garamond', serif; font-weight: 600; font-size: 1.35rem; color: var(--pine); line-height: 1; }
    .type-price { font-weight: 600; color: var(--pine); white-space: nowrap; }
    .type-price small { font-weight: 400; color: var(--muted); }
    .type-desc { color: var(--muted); max-width: 30ch; }
    .pill-cap { background: #f6eddc; color: #8f6a1f; }
    .pill-cap::before { display: none; }
    .pill-rooms { background: #e6f2ee; color: #1f6b55; }
</style>

<header class="page-hero types-hero reveal" style="--i:0">
    <div class="hero-slides" aria-hidden="true">
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
    </div>

    <div class="hero-top">
        <div>
            <div class="hero-kicker">Pricing and capacity</div>
            <div class="hero-rule"></div>
            <h1 class="hero-title">Room types</h1>
            <p class="hero-text">Define each category of room once, then set its nightly rate and how many guests it sleeps.</p>
        </div>

        <div class="hero-actions">
            <a href="{{ route('room-types.create') }}" class="hero-btn hero-btn-primary"><i class="bi bi-plus-lg"></i> Add room type</a>
            @if (Route::has('rooms.index'))
                <a href="{{ route('rooms.index') }}" class="hero-btn hero-btn-ghost"><i class="bi bi-door-open"></i> Rooms</a>
            @endif
            @if (Route::has('bookings.index'))
                <a href="{{ route('bookings.index') }}" class="hero-btn hero-btn-ghost"><i class="bi bi-calendar-check"></i> Bookings</a>
            @endif
        </div>
    </div>

    <div class="hero-bottom">
        <div class="hero-chips">
            <div class="hero-chip"><strong>{{ $roomTypes->total() }}</strong><span>{{ request()->hasAny(['search', 'capacity']) ? 'Types matching filters' : 'Room types' }}</span></div>
            <div class="hero-chip"><strong>{{ $roomTypes->sum('rooms_count') }}</strong><span>Rooms on this page</span></div>
            <div class="hero-chip"><strong>{{ $roomTypes->currentPage() }}/{{ $roomTypes->lastPage() }}</strong><span>Page</span></div>
        </div>
        <div class="hero-dots" aria-hidden="true"><span></span><span></span><span></span></div>
    </div>
</header>

<div class="panel-card filter-bar mb-3 reveal" style="--i:1">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-6">
            <label for="search" class="form-label">Type name</label>
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input id="search" type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by type name">
            </div>
        </div>
        <div class="col-md-3">
            <label for="capacity" class="form-label">Capacity</label>
            <select id="capacity" name="capacity" class="form-select">
                <option value="">All capacities</option>
                @foreach ([1,2,3,4,5,6] as $c)
                    <option value="{{ $c }}" @selected(request('capacity') == $c)>{{ $c }} {{ $c === 1 ? 'guest' : 'guests' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('room-types.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="panel-card reveal" style="--i:2">
    <div class="panel-card-head">
        All room types
        <small>Showing {{ $roomTypes->firstItem() ?? 0 }}&ndash;{{ $roomTypes->lastItem() ?? 0 }} of {{ $roomTypes->total() }}</small>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle themed-table">
            <thead>
                <tr>
                    <th>ID</th><th>Type name</th><th>Price</th><th>Capacity</th>
                    <th>Rooms</th><th>Description</th><th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($roomTypes as $t)
                <tr>
                    <td class="type-id">{{ $t->room_type_id }}</td>
                    <td><span class="type-name">{{ $t->type_name }}</span></td>
                    <td class="type-price">${{ number_format($t->price_per_night, 2) }} <small>/ night</small></td>
                    <td><span class="pill pill-cap"><i class="bi bi-people"></i> {{ $t->capacity }}</span></td>
                    <td><span class="pill pill-rooms">{{ $t->rooms_count }} {{ $t->rooms_count === 1 ? 'room' : 'rooms' }}</span></td>
                    <td class="type-desc">{{ \Illuminate\Support\Str::limit($t->description, 50) ?: 'No description' }}</td>
                    <td class="text-end">
                        <a href="{{ route('room-types.edit', $t) }}" class="icon-btn icon-btn-edit" title="Edit room type" aria-label="Edit {{ $t->type_name }}"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('room-types.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room type?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn icon-btn-delete" title="Delete room type" aria-label="Delete {{ $t->type_name }}"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="bi bi-grid"></i>
                        No room types match these filters.
                        <div class="mt-3"><a href="{{ route('room-types.create') }}" class="btn btn-primary btn-sm">Add a room type</a></div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-center">{{ $roomTypes->links() }}</div>
@endsection