@extends('layouts.app')
@section('title', 'Room Types')
@section('content')
@php
    $roomImages = [
        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1617104678098-de229db51175?auto=format&fit=crop&w=1400&q=85',
        'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=1400&q=85',
    ];
    $featuredType = $roomTypes->first();
    $featuredImage = $roomImages[$featuredType ? ($featuredType->room_type_id - 1) % count($roomImages) : 0];
@endphp

<style>
    .types-page {
        --types-gold: #d7b477;
        --types-muted: #a9b5b6;
        color: #e7e9e7;
        padding-bottom: 2rem;
    }

    .types-hero {
        position: relative;
        min-height: 340px;
        margin-bottom: 1.2rem;
        padding: 1rem 1.5rem 1.6rem;
        text-align: center;
        border-radius: 12px;
    }
    .types-hero .hero-slides { z-index: -2; }
    .types-hero-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: opacity .25s ease;
    }
    .types-hero::after {
        z-index: -1;
        background:
            linear-gradient(180deg, rgba(7, 17, 20, .08) 8%, rgba(7, 17, 20, .45) 50%, rgba(7, 17, 20, .92) 100%),
            linear-gradient(90deg, rgba(7, 17, 20, .25), rgba(7, 17, 20, .08), rgba(7, 17, 20, .25));
    }
    .types-hero-nav {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: flex-end;
        gap: 1.1rem;
        min-height: 30px;
    }
    .types-hero-nav a {
        color: rgba(255,255,255,.82);
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-decoration: none;
        text-transform: uppercase;
    }
    .types-hero-nav a:hover { color: var(--types-gold); }
    .types-hero-copy {
        position: absolute;
        right: 1rem;
        bottom: 1.8rem;
        left: 1rem;
        z-index: 1;
        display: grid;
        justify-items: center;
    }
    .types-hero-copy .hero-kicker { color: #e2c48f; }
    .types-hero-copy .hero-title { margin-bottom: .15rem; font-size: clamp(2.4rem, 5vw, 3.8rem); }
    .types-hero-copy .hero-text { max-width: 55ch; }
    .types-hero-copy .hero-actions { margin-top: 1rem; justify-content: center; }
    .types-hero .hero-dots { position: absolute; right: 1.5rem; bottom: 1.2rem; }

    .types-intro {
        display: grid;
        grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
        align-items: center;
        gap: clamp(1.25rem, 4vw, 3.5rem);
        margin-bottom: 1.2rem;
        padding: clamp(1rem, 3vw, 2rem);
        border: 1px solid rgba(208,170,112,.2);
        border-radius: 12px;
        background: rgba(8,14,16,.72);
        backdrop-filter: blur(8px);
        box-shadow: 0 18px 50px -35px rgba(0,0,0,.8);
    }
    .types-intro-copy .hero-kicker { margin-bottom: .35rem; }
    .types-intro-copy h2 {
        margin: 0 0 .45rem;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2rem, 3.3vw, 2.8rem);
        font-weight: 600;
    }
    .types-intro-copy p { margin: 0; color: #c1cbca; font-size: .9rem; line-height: 1.65; }
    .types-intro-image {
        min-height: 215px;
        overflow: hidden;
        border-radius: 7px;
        background: #17333a;
    }
    .types-intro-image img { display: block; width: 100%; height: 100%; min-height: 215px; object-fit: cover; }
    .types-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .55rem;
        margin-top: 1.2rem;
    }
    .types-metric {
        padding: .6rem .5rem;
        border: 1px solid rgba(224,232,236,.13);
        border-radius: 7px;
        background: rgba(255,255,255,.04);
        text-align: center;
    }
    .types-metric strong {
        display: block;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.35rem;
        line-height: 1.15;
    }
    .types-metric span { color: var(--types-muted); font-size: .63rem; }
    .types-metric i { display: block; margin-bottom: .2rem; color: var(--types-gold); font-size: .9rem; }

    .types-section {
        margin-bottom: 1.2rem;
        padding: 1.15rem 1.25rem;
        border: 1px solid rgba(208,170,112,.2);
        border-radius: 12px;
        background: rgba(8,14,16,.72);
        backdrop-filter: blur(8px);
        box-shadow: 0 18px 50px -35px rgba(0,0,0,.8);
    }
    .types-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: .9rem;
    }
    .types-section-heading h2 {
        margin: 0;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.65rem;
        font-weight: 600;
    }
    .types-section-heading p { margin: .15rem 0 0; color: var(--types-muted); font-size: .75rem; }
    .types-count { color: #c1cbca; font-size: .72rem; white-space: nowrap; }

    .types-filter {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(150px, .42fr) auto;
        align-items: end;
        gap: .75rem;
        margin-bottom: 1rem;
        padding: .85rem;
        border: 1px solid rgba(224,232,236,.12);
        border-radius: 8px;
        background: rgba(0,0,0,.17);
    }
    .types-filter .form-label { margin-bottom: .3rem; color: #a9b5b6; font-size: .7rem; }
    .types-filter .search-wrap { position: relative; }
    .types-filter .search-wrap .bi {
        position: absolute;
        top: 50%;
        left: .8rem;
        color: #78878a;
        pointer-events: none;
        transform: translateY(-50%);
    }
    .types-filter .form-control,
    .types-filter .form-select {
        min-height: 39px;
        border-color: rgba(224,232,236,.22);
        background-color: rgba(255,255,255,.96);
        color: #273033;
        font-size: .8rem;
    }
    .types-filter .search-wrap .form-control { padding-left: 2.2rem; }
    .types-filter .form-control:focus,
    .types-filter .form-select:focus { border-color: #d7b477; box-shadow: 0 0 0 .18rem rgba(176,141,87,.2); }
    .types-filter-actions { display: flex; gap: .45rem; }
    .types-filter-actions .btn { min-height: 39px; padding-inline: .85rem; font-size: .77rem; }
    .types-filter .btn-primary { background: #17333a; border-color: #17333a; }
    .types-filter .btn-primary:hover { background: #244750; }
    .types-filter .btn-outline-secondary { border-color: rgba(255,255,255,.4); color: #e7e9e7; }

    .type-card-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .8rem;
    }
    .type-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid rgba(224,232,236,.15);
        border-radius: 8px;
        background: rgba(4,8,10,.4);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .type-card:hover {
        transform: translateY(-3px);
        border-color: rgba(208,170,112,.48);
        box-shadow: 0 12px 26px -16px rgba(0,0,0,.85);
    }
    .type-card.is-selected {
        border-color: rgba(215,180,119,.75);
        box-shadow: 0 0 0 1px rgba(215,180,119,.22), 0 12px 26px -16px rgba(0,0,0,.85);
    }
    .type-card-image { position: relative; height: 155px; overflow: hidden; background: #17333a; }
    .type-card-image img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .45s ease; }
    .type-card:hover .type-card-image img { transform: scale(1.045); }
    .type-card-price {
        position: absolute;
        right: .55rem;
        bottom: .55rem;
        padding: .28rem .5rem;
        border: 1px solid rgba(255,255,255,.22);
        border-radius: 5px;
        background: rgba(8,14,16,.84);
        color: #f3e7d2;
        font-size: .7rem;
        font-weight: 600;
        backdrop-filter: blur(6px);
    }
    .type-card-body { padding: .75rem; }
    .type-card-body h3 {
        overflow: hidden;
        margin: 0;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.3rem;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .type-card-description {
        min-height: 2.35em;
        margin: .2rem 0 .65rem;
        color: #a9b5b6;
        font-size: .7rem;
        line-height: 1.5;
    }
    .type-card-meta {
        display: flex;
        justify-content: space-between;
        gap: .4rem;
        padding: .55rem 0;
        border-top: 1px solid rgba(224,232,236,.12);
        border-bottom: 1px solid rgba(224,232,236,.12);
        color: #d8dddb;
        font-size: .66rem;
    }
    .type-card-meta span { white-space: nowrap; }
    .type-card-meta i { margin-right: .18rem; color: var(--types-gold); }
    .type-card-actions { display: flex; align-items: center; justify-content: space-between; gap: .5rem; padding-top: .6rem; }
    .type-card-actions .btn { padding: .3rem .55rem; font-size: .68rem; }
    .type-card-actions .btn-outline-light { border-color: rgba(255,255,255,.28); color: #e7e9e7; }
    .type-card-actions .btn-outline-light:hover { background: rgba(255,255,255,.12); }
    .type-card-actions .type-details-button {
        border: 0;
        background: transparent;
        color: #d7b477;
        padding: .3rem .1rem;
        font-size: .66rem;
        font-weight: 600;
    }
    .type-card-actions .type-details-button:hover { color: #f3e7d2; text-decoration: underline; }
    .type-details-button:focus-visible { outline: 2px solid #d7b477; outline-offset: 3px; border-radius: 3px; }
    .type-card-actions .action-buttons { gap: .3rem; }
    .type-card-actions .icon-btn { width: 30px; height: 30px; }
    .types-empty {
        grid-column: 1 / -1;
        padding: 2.5rem 1rem;
        border: 1px dashed rgba(224,232,236,.2);
        border-radius: 8px;
        color: #a9b5b6;
        text-align: center;
    }
    .types-pagination { display: flex; justify-content: center; margin-top: 1rem; }

    @media (max-width: 991.98px) {
        .type-card-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .type-card-image { height: 180px; }
    }
    @media (max-width: 767.98px) {
        .types-intro { grid-template-columns: 1fr; }
        .types-intro-image { grid-row: 1; }
        .types-hero { min-height: 300px; }
        .types-filter { grid-template-columns: 1fr 1fr; }
        .types-filter-actions { grid-column: 1 / -1; }
        .types-filter-actions .btn { flex: 1; }
    }
    @media (max-width: 575.98px) {
        .types-hero { min-height: 275px; padding-inline: 1rem; }
        .types-hero-nav { justify-content: center; }
        .types-hero .hero-dots { right: 1rem; }
        .types-section { padding: .9rem; }
        .types-section-heading { align-items: flex-start; }
        .types-section-heading h2 { font-size: 1.45rem; }
        .types-count { max-width: 9rem; text-align: right; white-space: normal; }
        .types-filter { grid-template-columns: 1fr; }
        .types-filter-actions { grid-column: auto; }
        .type-card-grid { gap: .55rem; }
        .type-card-image { height: 125px; }
        .type-card-body { padding: .6rem; }
        .type-card-body h3 { font-size: 1.1rem; }
        .type-card-description { font-size: .66rem; }
        .type-card-meta { flex-direction: column; gap: .3rem; }
        .type-card-actions { flex-wrap: wrap; }
        .types-metrics { gap: .35rem; }
        .types-metric span { font-size: .58rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .types-page *, .types-page *::before, .types-page *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<main class="types-page">
    <header class="page-hero types-hero reveal" style="--i:0">
        <div class="hero-slides" aria-hidden="true">
            <img class="types-hero-image" id="featured-hero-image" src="{{ $featuredImage }}" alt="">
        </div>
        <nav class="types-hero-nav" aria-label="Room type page sections">
            <a href="#room-type-collection">Rooms</a>
            <a href="#room-type-details">Room details</a>
        </nav>
        <div class="types-hero-copy">
            <div class="hero-kicker">Find your perfect stay</div>
            <h1 class="hero-title" id="featured-hero-name">{{ $featuredType?->type_name ?? 'Our rooms' }}</h1>
            <p class="hero-text" id="featured-hero-description">{{ $featuredType?->description ?: 'Discover thoughtfully appointed rooms designed for a comfortable stay.' }}</p>
            <div class="hero-actions">
                <a href="#room-type-collection" class="hero-btn hero-btn-primary">Explore room types <i class="bi bi-arrow-down ms-1"></i></a>
            </div>
        </div>
        <div class="hero-dots" aria-hidden="true"><span></span><span></span><span></span></div>
    </header>

    @if ($featuredType)
        <section class="types-intro reveal" id="room-type-details" style="--i:1" aria-labelledby="featured-room-heading" aria-live="polite">
            <div class="types-intro-copy">
                <div class="hero-kicker">Your stay, your way</div>
                <h2 id="featured-room-heading">{{ $featuredType->type_name }}</h2>
                <p id="featured-room-description">{{ $featuredType->description ?: 'A welcoming room with considered details for a restful and comfortable stay.' }}</p>
                <div class="types-metrics">
                    <div class="types-metric">
                        <i class="bi bi-rulers"></i>
                        <strong id="featured-room-price">${{ number_format($featuredType->price_per_night, 0) }}</strong>
                        <span>per night</span>
                    </div>
                    <div class="types-metric">
                        <i class="bi bi-people"></i>
                        <strong id="featured-room-capacity">{{ $featuredType->capacity }}</strong>
                        <span id="featured-room-capacity-label">{{ \Illuminate\Support\Str::plural('guest', $featuredType->capacity) }}</span>
                    </div>
                    <div class="types-metric">
                        <i class="bi bi-door-open"></i>
                        <strong id="featured-room-count">{{ $featuredType->rooms_count }}</strong>
                        <span id="featured-room-count-label">{{ \Illuminate\Support\Str::plural('room', $featuredType->rooms_count) }}</span>
                    </div>
                </div>
            </div>
            <div class="types-intro-image">
                <img id="featured-room-image" src="{{ $featuredImage }}" alt="{{ $featuredType->type_name }} room" loading="lazy">
            </div>
        </section>
    @endif

    <section class="types-section reveal" id="room-type-collection" style="--i:2" aria-labelledby="all-types-heading">
        <div class="types-section-heading">
            <div>
                <h2 id="all-types-heading">All room types</h2>
                <p>Find the right room for every kind of stay.</p>
            </div>
            <span class="types-count">Showing {{ $roomTypes->firstItem() ?? 0 }}&ndash;{{ $roomTypes->lastItem() ?? 0 }} of {{ $roomTypes->total() }}</span>
        </div>

        <form method="GET" action="{{ route('room-types.index') }}" class="types-filter" role="search">
            <div>
                <label for="search" class="form-label">Room type</label>
                <div class="search-wrap">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input id="search" type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search room types">
                </div>
            </div>
            <div>
                <label for="capacity" class="form-label">Guests</label>
                <select id="capacity" name="capacity" class="form-select">
                    <option value="">All capacities</option>
                    @foreach ([1, 2, 3, 4, 5, 6] as $capacity)
                        <option value="{{ $capacity }}" @selected(request('capacity') == $capacity)>{{ $capacity }} {{ \Illuminate\Support\Str::plural('guest', $capacity) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="types-filter-actions">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('room-types.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
            </div>
        </form>

        <div class="type-card-grid">
            @forelse ($roomTypes as $roomType)
                @php $image = $roomImages[($roomType->room_type_id - 1) % count($roomImages)]; @endphp
                <article class="type-card {{ $featuredType && $featuredType->room_type_id === $roomType->room_type_id ? 'is-selected' : '' }}">
                    <div class="type-card-image">
                        <img src="{{ $image }}" alt="{{ $roomType->type_name }} room" loading="lazy">
                        <span class="type-card-price">${{ number_format($roomType->price_per_night, 2) }} / night</span>
                    </div>
                    <div class="type-card-body">
                        <h3>{{ $roomType->type_name }}</h3>
                        <p class="type-card-description">{{ $roomType->description ?: 'A comfortable stay with the amenities you need.' }}</p>
                        <div class="type-card-meta">
                            <span><i class="bi bi-people"></i> Up to {{ $roomType->capacity }} {{ \Illuminate\Support\Str::plural('guest', $roomType->capacity) }}</span>
                            <span><i class="bi bi-door-open"></i> {{ $roomType->rooms_count }} {{ \Illuminate\Support\Str::plural('room', $roomType->rooms_count) }}</span>
                        </div>
                        <div class="type-card-actions">
                            <button
                                class="type-details-button"
                                type="button"
                                data-room-name="{{ $roomType->type_name }}"
                                data-room-description="{{ $roomType->description ?: 'A welcoming room with considered details for a restful and comfortable stay.' }}"
                                data-room-price="{{ number_format($roomType->price_per_night, 0) }}"
                                data-room-capacity="{{ $roomType->capacity }}"
                                data-room-count="{{ $roomType->rooms_count }}"
                                data-room-image="{{ $image }}"
                                aria-pressed="{{ $featuredType && $featuredType->room_type_id === $roomType->room_type_id ? 'true' : 'false' }}"
                            >View details</button>
                            <a class="btn btn-outline-light" href="{{ route('rooms.index', ['room_type_id' => $roomType->room_type_id]) }}">View rooms <i class="bi bi-arrow-right ms-1"></i></a>
                            <span class="action-buttons">
                                <a href="{{ route('room-types.edit', $roomType) }}" class="icon-btn icon-btn-edit" title="Edit room type" aria-label="Edit {{ $roomType->type_name }}"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('room-types.destroy', $roomType) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room type?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn icon-btn-delete" title="Delete room type" aria-label="Delete {{ $roomType->type_name }}"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="types-empty">
                    <i class="bi bi-grid fs-4 d-block mb-2"></i>
                    No room types match these filters.
                    @if (request()->hasAny(['search', 'capacity']))
                        <div class="mt-3"><a href="{{ route('room-types.index') }}" class="btn btn-outline-light btn-sm">Clear filters</a></div>
                    @else
                        <div class="mt-3"><a href="{{ route('room-types.create') }}" class="btn btn-primary btn-sm">Add your first room type</a></div>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($roomTypes->hasPages())
            <div class="types-pagination">{{ $roomTypes->links() }}</div>
        @endif
    </section>
</main>

<script>
    (function () {
        var detailsButtons = document.querySelectorAll('.type-details-button');
        var featuredSection = document.getElementById('room-type-details');
        var featuredHeroImage = document.getElementById('featured-hero-image');

        if (!featuredSection || !featuredHeroImage || detailsButtons.length === 0) return;

        var fields = {
            heroName: document.getElementById('featured-hero-name'),
            heroDescription: document.getElementById('featured-hero-description'),
            name: document.getElementById('featured-room-heading'),
            description: document.getElementById('featured-room-description'),
            price: document.getElementById('featured-room-price'),
            capacity: document.getElementById('featured-room-capacity'),
            capacityLabel: document.getElementById('featured-room-capacity-label'),
            roomCount: document.getElementById('featured-room-count'),
            roomCountLabel: document.getElementById('featured-room-count-label'),
            image: document.getElementById('featured-room-image')
        };

        detailsButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var room = button.dataset;

                fields.heroName.textContent = room.roomName;
                fields.heroDescription.textContent = room.roomDescription;
                fields.name.textContent = room.roomName;
                fields.description.textContent = room.roomDescription;
                fields.price.textContent = '$' + room.roomPrice;
                fields.capacity.textContent = room.roomCapacity;
                fields.capacityLabel.textContent = Number(room.roomCapacity) === 1 ? 'guest' : 'guests';
                fields.roomCount.textContent = room.roomCount;
                fields.roomCountLabel.textContent = Number(room.roomCount) === 1 ? 'room' : 'rooms';
                fields.image.src = room.roomImage;
                fields.image.alt = room.roomName + ' room';
                featuredHeroImage.src = room.roomImage;

                detailsButtons.forEach(function (otherButton) {
                    var selected = otherButton === button;
                    otherButton.setAttribute('aria-pressed', String(selected));
                    otherButton.closest('.type-card').classList.toggle('is-selected', selected);
                });
            });
        });
    })();
</script>
@endsection
