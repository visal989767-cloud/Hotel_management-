@extends('layouts.app')
@section('title', 'Guests')
@section('content')
@php
    $knownAvatars = [
        'sal@gmail.com' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=400&q=80',
        'lengmakara@gmail.com' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
        'povnita@gmail.com' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
        'lin@gmail.com' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
        'kongsopheak@gmail.com' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
    ];
    $fallbackAvatars = [
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
    ];
    $getAvatar = function ($guest) use ($knownAvatars, $fallbackAvatars) {
        $email = strtolower(trim((string) $guest->email));
        if (isset($knownAvatars[$email])) {
            return $knownAvatars[$email];
        }
        $id = (int) ($guest->guest_id ?: 1);
        return $fallbackAvatars[($id - 1) % count($fallbackAvatars)];
    };
@endphp

<style>
    /* Full width styling for Guests page */
    .container:has(.guests-ocean-layout) {
        max-width: 1440px !important;
        padding-left: clamp(1rem, 3vw, 2.5rem) !important;
        padding-right: clamp(1rem, 3vw, 2.5rem) !important;
    }

    .guests-ocean-layout {
        --ocean-gold: #d7b477;
        --ocean-gold-hover: #e5c78f;
        --ocean-card-dark: #0e1416;
        --ocean-item-dark: #111618;
        --ocean-pill-bg: #161c1e;
        --ocean-border: rgba(255, 255, 255, 0.08);
        --ocean-muted: #a4b0b3;
    }

    /* Centered Hero Section (matching screenshot) */
    .guests-hero-centered {
        position: relative;
        min-height: 340px;
        padding: 3rem 1.5rem 2.5rem;
        border: 1px solid rgba(224, 232, 236, 0.14);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-bottom: 1.5rem;
        box-shadow: 0 24px 60px -25px rgba(0, 0, 0, 0.8);
        overflow: hidden;
    }
    .guests-hero-centered::after {
        background:
            linear-gradient(180deg, rgba(8, 14, 16, 0.45) 0%, rgba(8, 14, 16, 0.3) 45%, rgba(8, 14, 16, 0.75) 100%),
            linear-gradient(90deg, rgba(8, 14, 16, 0.5) 0%, transparent 50%, rgba(8, 14, 16, 0.5) 100%);
    }
    .hero-center-content {
        position: relative;
        z-index: 2;
        max-width: 780px;
        margin: 0 auto;
    }
    .hero-kicker-line {
        display: inline-flex;
        align-items: center;
        gap: 1.25rem;
        color: var(--ocean-gold);
        letter-spacing: .25em;
        font-size: .8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: .6rem;
    }
    .hero-kicker-line::before,
    .hero-kicker-line::after {
        content: "";
        width: 55px;
        height: 1.5px;
        background: var(--ocean-gold);
        opacity: .85;
    }
    .hero-title-centered {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(3.5rem, 8vw, 6.2rem);
        color: #ffffff;
        line-height: .92;
        letter-spacing: -.02em;
        margin: 0 0 .75rem 0;
        text-shadow: 0 2px 20px rgba(0, 0, 0, 0.6);
    }
    .hero-sub-centered {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.05rem;
        max-width: 56ch;
        margin: 0 auto 1.4rem;
        line-height: 1.55;
        text-shadow: 0 1px 8px rgba(0, 0, 0, 0.5);
    }
    .guest-explore-pill {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .55rem 1.6rem;
        border-radius: 30px;
        background: rgba(215, 180, 119, 0.18);
        border: 1px solid rgba(215, 180, 119, 0.45);
        color: #f3e7d2;
        font-size: .88rem;
        font-weight: 500;
        text-decoration: none;
        backdrop-filter: blur(8px);
        transition: all .25s ease;
    }
    .guest-explore-pill:hover {
        background: var(--ocean-gold);
        border-color: var(--ocean-gold);
        color: #0f2429;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(215, 180, 119, 0.35);
    }

    /* Main Directory Container Card */
    .guest-directory-container {
        background: var(--ocean-card-dark);
        border: 1px solid var(--ocean-border);
        border-radius: 24px;
        box-shadow: 0 24px 60px -20px rgba(0, 0, 0, 0.85);
        padding: 1.75rem clamp(1rem, 2.5vw, 2rem);
        backdrop-filter: blur(14px);
    }

    /* Toolbar Row 1 (Search + Dropdown + Filter + Reset) */
    .guest-toolbar-form {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        align-items: center;
    }
    .guest-search-box {
        position: relative;
        flex: 1 1 420px;
        min-width: 260px;
    }
    .guest-search-icon {
        position: absolute;
        left: 1.15rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ocean-muted);
        pointer-events: none;
        font-size: .95rem;
    }
    .guest-search-input-pill {
        width: 100%;
        height: 44px;
        border-radius: 30px;
        background: var(--ocean-pill-bg) !important;
        border: 1px solid var(--ocean-border) !important;
        color: #ffffff !important;
        padding-left: 2.85rem !important;
        padding-right: 1.25rem !important;
        font-size: .88rem;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .guest-search-input-pill:focus {
        border-color: var(--ocean-gold) !important;
        box-shadow: 0 0 0 .2rem rgba(215, 180, 119, 0.18) !important;
    }
    .guest-search-input-pill::placeholder {
        color: #839194;
    }
    .guest-select-box {
        position: relative;
        min-width: 150px;
    }
    .guest-select-icon {
        position: absolute;
        left: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ocean-muted);
        pointer-events: none;
        font-size: .85rem;
    }
    .guest-select-pill {
        height: 44px;
        border-radius: 30px !important;
        background-color: var(--ocean-pill-bg) !important;
        border: 1px solid var(--ocean-border) !important;
        color: #e2e8ea !important;
        padding-left: 2.5rem !important;
        padding-right: 2.2rem !important;
        font-size: .88rem;
        outline: none;
        cursor: pointer;
    }
    .guest-select-pill:focus {
        border-color: var(--ocean-gold) !important;
        box-shadow: 0 0 0 .2rem rgba(215, 180, 119, 0.18) !important;
    }
    .guest-select-pill option {
        background-color: #12191b;
        color: #fff;
    }
    .guest-pill-btn {
        height: 44px;
        border-radius: 30px !important;
        padding: 0 1.45rem !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        font-size: .88rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
    }
    .guest-btn-filter {
        background: var(--ocean-pill-bg);
        border: 1px solid var(--ocean-border);
        color: #e2e8ea;
    }
    .guest-btn-filter:hover {
        background: var(--ocean-gold);
        border-color: var(--ocean-gold);
        color: #0f2429;
    }
    .guest-btn-reset {
        background: var(--ocean-pill-bg);
        border: 1px solid var(--ocean-border);
        color: var(--ocean-muted);
    }
    .guest-btn-reset:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.2);
    }

    /* Sub-toolbar Row 2 (Total Guests & Toggle buttons) */
    .guest-sub-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.35rem;
        margin-bottom: 1.25rem;
    }
    .guest-total-count {
        color: #d1dcde;
        font-size: .95rem;
        font-weight: 500;
    }
    .guest-toggle-group {
        display: inline-flex;
        gap: .45rem;
    }
    .guest-toggle-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid var(--ocean-border);
        background: var(--ocean-pill-bg);
        color: var(--ocean-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        cursor: pointer;
        transition: all .2s ease;
    }
    .guest-toggle-btn.is-active {
        background: var(--ocean-gold) !important;
        color: #0f2429 !important;
        border-color: var(--ocean-gold) !important;
        box-shadow: 0 2px 10px rgba(215, 180, 119, 0.35);
    }

    /* Cards Grid View (Exact 5-column row matching screenshot) */
    .guest-cards-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
    }
    .guest-card-box {
        background: var(--ocean-item-dark);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        text-align: center;
        transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease;
    }
    .guest-card-box:hover {
        transform: translateY(-4px);
        border-color: rgba(215, 180, 119, 0.4);
        box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.7);
    }
    .guest-card-hero-banner {
        height: 95px;
        position: relative;
        background-image: linear-gradient(180deg, rgba(8, 14, 16, 0.15) 0%, rgba(17, 22, 24, 0.98) 100%),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=75');
        background-size: cover;
        background-position: center;
    }
    .guest-avatar-holder {
        width: 80px;
        height: 80px;
        margin: -40px auto 8px;
        position: relative;
        border-radius: 50%;
        border: 3.5px solid #111618;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5);
        background: #182022;
    }
    .guest-avatar-img-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        display: block;
    }
    .guest-online-dot {
        position: absolute;
        bottom: 3px;
        left: 3px;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #22c55e;
        border: 2.5px solid #111618;
        box-shadow: 0 0 6px rgba(34, 197, 94, 0.7);
    }
    .guest-card-content-area {
        padding: 0 1rem 1.15rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .guest-title-name {
        font-size: 1.15rem;
        font-weight: 600;
        color: #ffffff;
        margin: 0 0 .35rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .guest-contact-block {
        display: flex;
        flex-direction: column;
        gap: .2rem;
        margin-bottom: .6rem;
    }
    .guest-info-line {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        color: var(--ocean-muted);
        font-size: .78rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .guest-info-line i {
        color: var(--ocean-muted);
        font-size: .75rem;
        flex-shrink: 0;
    }
    .guest-status-pill-green {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        background: #143521;
        color: #4ade80;
        border: 1px solid rgba(74, 222, 128, 0.25);
    }
    .guest-actions-circle-row {
        margin-top: auto;
        padding-top: .85rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: center;
        gap: .5rem;
    }
    .guest-circle-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #1a2224;
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #c4d0d3;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
    }
    .guest-circle-btn:hover {
        background: var(--ocean-gold);
        color: #0f2429;
        border-color: var(--ocean-gold);
        transform: scale(1.08);
    }
    .guest-btn-trash:hover {
        background: #e57373;
        color: #fff;
        border-color: #e57373;
    }

    /* List / Table View Styling */
    .guest-table-box {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--ocean-border);
        background: var(--ocean-item-dark);
    }
    .guest-table-box th {
        background: rgba(255, 255, 255, 0.03);
        color: var(--ocean-gold);
        font-size: .8rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        border-bottom: 1px solid var(--ocean-border);
        padding: 1rem .85rem;
    }
    .guest-table-box td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: .85rem;
        color: #e2e8ea;
        font-size: .85rem;
    }

    /* Modal styling */
    #guestDetailModal {
        z-index: 1065 !important;
    }
    #guestDetailModal .modal-dialog {
        z-index: 1066 !important;
        max-width: 580px;
    }
    .guest-modal-shell {
        background: #0e1416;
        border: 1px solid rgba(215, 180, 119, 0.35);
        color: #f3e7d2;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.85);
    }
    .guest-modal-top-banner {
        height: 110px;
        background-image: linear-gradient(180deg, rgba(8, 14, 16, 0.3) 0%, rgba(14, 20, 22, 0.98) 100%),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=75');
        background-size: cover;
        background-position: center;
        position: relative;
    }
    .guest-modal-close-icon {
        position: absolute;
        top: .85rem;
        right: .85rem;
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 50%;
        background: rgba(8, 14, 16, 0.85);
        border: 1px solid rgba(215, 180, 119, 0.5);
        color: #f3e7d2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .95rem;
        cursor: pointer;
        transition: all .2s ease;
    }
    .guest-modal-close-icon:hover {
        background: var(--ocean-gold);
        color: #0f2429;
        transform: scale(1.08);
    }
    .guest-modal-portrait {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        border: 3.5px solid #0e1416;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.6);
        margin: -44px auto 0;
        display: block;
        object-fit: cover;
    }

    /* Responsive grid overrides */
    @media (max-width: 1200px) {
        .guest-cards-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 768px) {
        .guest-cards-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .hero-title-centered { font-size: 3.2rem; }
        .hero-kicker-line::before, .hero-kicker-line::after { width: 30px; }
        .guest-toolbar-form { flex-direction: column; align-items: stretch; }
        .guest-search-box { width: 100%; }
        .guest-pill-btn { width: 100%; }
    }
    @media (max-width: 480px) {
        .guest-cards-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="guests-ocean-layout">
    <!-- Centered Hero Section (matching screenshot) -->
    <header class="page-hero photo-hero photo-hero--guests guests-hero-centered reveal">
        <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
        <div class="hero-center-content">
            <div class="hero-kicker-line">OUR VALUED GUESTS</div>
            <h1 class="hero-title-centered">Guests</h1>
            <p class="hero-sub-centered">Manage your guests and keep their information organized.</p>
            <div>
                <a href="#guestDirectoryCard" class="guest-explore-pill">
                    <i class="bi bi-search"></i> Explore Guests <i class="bi bi-chevron-down"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Directory Container Card -->
    <div class="guest-directory-container reveal" id="guestDirectoryCard" style="--i:1">
        <!-- Row 1: Search & Filter Toolbar -->
        <form method="GET" action="{{ route('guests.index') }}" class="guest-toolbar-form">
            <div class="guest-search-box">
                <i class="bi bi-search guest-search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="guest-search-input-pill" placeholder="Search by name, email or phone...">
            </div>
            <div class="guest-select-box">
                <i class="bi bi-funnel guest-select-icon"></i>
                <select name="gender" class="guest-select-pill" onchange="this.form.submit()">
                    <option value="">All Guests</option>
                    <option value="Male" @selected(request('gender') === 'Male')>Male</option>
                    <option value="Female" @selected(request('gender') === 'Female')>Female</option>
                    <option value="Other" @selected(request('gender') === 'Other')>Other</option>
                </select>
            </div>
            <button type="submit" class="guest-pill-btn guest-btn-filter">
                <i class="bi bi-sliders"></i> Filter
            </button>
            <a href="{{ route('guests.index') }}" class="guest-pill-btn guest-btn-reset">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </a>
        </form>

        <!-- Row 2: Sub-toolbar with Total Guests and View Switcher -->
        <div class="guest-sub-bar">
            <div class="guest-total-count">
                Total Guests: {{ $guests->total() }}
            </div>
            <div class="guest-toggle-group" role="group" aria-label="View mode">
                <button type="button" class="guest-toggle-btn is-active" id="btnGridView" title="Grid View">
                    <i class="bi bi-grid-fill"></i>
                </button>
                <button type="button" class="guest-toggle-btn" id="btnListView" title="List View">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>

        <!-- Row 3: 5 Guest Cards in one row (Grid View) -->
        <div class="guest-cards-grid" id="guestGridView">
            @forelse ($guests as $g)
                @php
                    $avatar = $getAvatar($g);
                @endphp
                <div class="guest-card-box">
                    <div class="guest-card-hero-banner"></div>
                    <div class="guest-card-content-area">
                        <div class="guest-avatar-holder">
                            <img src="{{ $avatar }}" alt="{{ $g->guest_name }}" class="guest-avatar-img-circle" loading="lazy" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($g->guest_name) }}&background=111618&color=d7b477'">
                            <span class="guest-online-dot" title="Active"></span>
                        </div>
                        <h5 class="guest-title-name" title="{{ $g->guest_name }}">{{ $g->guest_name }}</h5>
                        <div class="guest-contact-block">
                            <div class="guest-info-line" title="{{ $g->email }}">
                                <i class="bi bi-envelope"></i>
                                <span>{{ $g->email }}</span>
                            </div>
                            <div class="guest-info-line" title="{{ $g->phone ?: 'N/A' }}">
                                <i class="bi bi-telephone"></i>
                                <span>{{ $g->phone ?: 'N/A' }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="guest-status-pill-green">Active</span>
                        </div>
                        <div class="guest-actions-circle-row">
                            <button type="button" class="guest-circle-btn guest-view-btn" data-guest="{{ json_encode([
                                'id' => $g->guest_id,
                                'name' => $g->guest_name,
                                'email' => $g->email,
                                'phone' => $g->phone ?: 'N/A',
                                'gender' => $g->gender,
                                'id_number' => $g->id_number ?: 'N/A',
                                'address' => $g->address ?: 'N/A',
                                'avatar' => $avatar,
                                'bookings_count' => $g->bookings_count,
                                'bookings' => $g->bookings->map(fn($b) => [
                                    'id' => $b->booking_id,
                                    'room' => $b->room->room_number ?? 'N/A',
                                    'room_type' => $b->room->roomType->type_name ?? 'Room',
                                    'check_in' => $b->check_in ? \Carbon\Carbon::parse($b->check_in)->format('M d, Y') : '',
                                    'check_out' => $b->check_out ? \Carbon\Carbon::parse($b->check_out)->format('M d, Y') : '',
                                    'status' => ucfirst(str_replace('_', ' ', $b->status)),
                                    'price' => number_format($b->total_price, 2)
                                ]),
                                'edit_url' => route('guests.edit', $g)
                            ]) }}" title="View details" aria-label="View {{ $g->guest_name }}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="{{ route('guests.edit', $g) }}" class="guest-circle-btn" title="Edit guest" aria-label="Edit {{ $g->guest_name }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('guests.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this guest?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="guest-circle-btn guest-btn-trash" title="Delete guest" aria-label="Delete {{ $g->guest_name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-people fs-1 d-block mb-2 text-warning"></i>
                    No guests found.
                </div>
            @endforelse
        </div>

        <!-- List / Table View -->
        <div class="guest-list-view d-none" id="guestListView">
            <div class="table-responsive guest-table-box">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>ID Number</th>
                            <th>Address</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($guests as $g)
                            @php
                                $avatar = $getAvatar($g);
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $avatar }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                        <div>
                                            <div class="fw-semibold text-light">{{ $g->guest_name }}</div>
                                            <small class="text-muted">#{{ $g->guest_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $g->email }}</div>
                                    <small class="text-muted">{{ $g->phone ?: 'N/A' }}</small>
                                </td>
                                <td><span class="badge bg-dark border border-secondary text-light">{{ $g->gender }}</span></td>
                                <td>{{ $g->id_number ?: 'N/A' }}</td>
                                <td><small class="text-muted">{{ $g->address ?: 'N/A' }}</small></td>
                                <td><span class="badge bg-info text-dark">{{ $g->bookings_count }} stays</span></td>
                                <td><span class="guest-status-pill-green">Active</span></td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1 justify-content-end">
                                        <button type="button" class="guest-circle-btn guest-view-btn" data-guest="{{ json_encode([
                                            'id' => $g->guest_id,
                                            'name' => $g->guest_name,
                                            'email' => $g->email,
                                            'phone' => $g->phone ?: 'N/A',
                                            'gender' => $g->gender,
                                            'id_number' => $g->id_number ?: 'N/A',
                                            'address' => $g->address ?: 'N/A',
                                            'avatar' => $avatar,
                                            'bookings_count' => $g->bookings_count,
                                            'bookings' => $g->bookings->map(fn($b) => [
                                                'id' => $b->booking_id,
                                                'room' => $b->room->room_number ?? 'N/A',
                                                'room_type' => $b->room->roomType->type_name ?? 'Room',
                                                'check_in' => $b->check_in ? \Carbon\Carbon::parse($b->check_in)->format('M d, Y') : '',
                                                'check_out' => $b->check_out ? \Carbon\Carbon::parse($b->check_out)->format('M d, Y') : '',
                                                'status' => ucfirst(str_replace('_', ' ', $b->status)),
                                                'price' => number_format($b->total_price, 2)
                                            ]),
                                            'edit_url' => route('guests.edit', $g)
                                        ]) }}" title="View details"><i class="bi bi-eye"></i></button>
                                        <a href="{{ route('guests.edit', $g) }}" class="guest-circle-btn" title="Edit guest"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('guests.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this guest?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="guest-circle-btn guest-btn-trash" title="Delete guest"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">No guests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">Showing {{ $guests->firstItem() ?? 0 }} to {{ $guests->lastItem() ?? 0 }} of {{ $guests->total() }} guests</div>
            <div>{{ $guests->links() }}</div>
        </div>
    </div>
</div>

<!-- Guest Detail Modal -->
<div class="modal fade" id="guestDetailModal" tabindex="-1" aria-labelledby="guestModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content guest-modal-shell">
            <div class="guest-modal-top-banner">
                <button type="button" class="guest-modal-close-icon" data-bs-dismiss="modal" aria-label="Close" title="Close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <img id="guestModalAvatar" src="" alt="" class="guest-modal-portrait">
                <div class="text-center mt-2 mb-3">
                    <h4 id="guestModalName" class="mb-1" style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.85rem; color: #fff;"></h4>
                    <span class="guest-status-pill-green">Active Guest</span>
                </div>

                <div class="row g-2 mb-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: .75rem;">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Email Address</small>
                        <span id="guestModalEmail" class="text-light small text-break"></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Phone Number</small>
                        <span id="guestModalPhone" class="text-light small"></span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <small class="text-muted d-block">Gender</small>
                        <span id="guestModalGender" class="text-light small"></span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <small class="text-muted d-block">National ID / Passport</small>
                        <span id="guestModalIdNumber" class="text-light small"></span>
                    </div>
                    <div class="col-12 mt-2">
                        <small class="text-muted d-block">Address</small>
                        <span id="guestModalAddress" class="text-light small"></span>
                    </div>
                </div>

                <div>
                    <h6 class="text-uppercase small mb-2" style="color: var(--ocean-gold); letter-spacing: .1em;">Stay & Booking History</h6>
                    <div id="guestModalBookings" class="small">
                        <!-- Filled by JS -->
                    </div>
                </div>

                <div class="mt-4 pt-3 d-flex justify-content-between align-items-center" style="border-top: 1px solid rgba(224,232,236,0.12);">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Close
                    </button>
                    <a id="guestModalEditBtn" href="#" class="btn btn-sm ocean-nav-add-btn px-3">
                        <i class="bi bi-pencil me-1"></i> Edit Guest
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var btnGrid = document.getElementById('btnGridView');
        var btnList = document.getElementById('btnListView');
        var viewGrid = document.getElementById('guestGridView');
        var viewList = document.getElementById('guestListView');

        function setView(view) {
            if (view === 'list') {
                viewGrid.classList.add('d-none');
                viewList.classList.remove('d-none');
                btnList.classList.add('is-active');
                btnGrid.classList.remove('is-active');
                try { localStorage.setItem('guest_view_pref', 'list'); } catch (e) {}
            } else {
                viewList.classList.add('d-none');
                viewGrid.classList.remove('d-none');
                btnGrid.classList.add('is-active');
                btnList.classList.remove('is-active');
                try { localStorage.setItem('guest_view_pref', 'grid'); } catch (e) {}
            }
        }

        if (btnGrid && btnList) {
            btnGrid.addEventListener('click', function () { setView('grid'); });
            btnList.addEventListener('click', function () { setView('list'); });

            var saved = 'grid';
            try { saved = localStorage.getItem('guest_view_pref') || 'grid'; } catch (e) {}
            setView(saved);
        }

        // Guest Detail Modal Logic
        var modalEl = document.getElementById('guestDetailModal');
        if (modalEl) {
            if (modalEl.parentNode !== document.body) {
                document.body.appendChild(modalEl);
            }

            function hideGuestModal() {
                if (window.bootstrap && window.bootstrap.Modal) {
                    var inst = bootstrap.Modal.getInstance(modalEl);
                    if (inst) {
                        try { inst.hide(); } catch (err) {}
                    }
                }
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
            }

            modalEl.querySelectorAll('.guest-modal-close-icon, [data-bs-dismiss="modal"]').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    hideGuestModal();
                });
            });

            modalEl.addEventListener('click', function (e) {
                if (e.target === modalEl) {
                    hideGuestModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && (modalEl.classList.contains('show') || modalEl.style.display === 'block')) {
                    hideGuestModal();
                }
            });

            document.querySelectorAll('.guest-view-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var dataStr = btn.getAttribute('data-guest');
                    if (!dataStr) return;
                    try {
                        var guest = JSON.parse(dataStr);
                        document.getElementById('guestModalAvatar').src = guest.avatar || '';
                        document.getElementById('guestModalName').textContent = guest.name || '';
                        document.getElementById('guestModalEmail').textContent = guest.email || 'N/A';
                        document.getElementById('guestModalPhone').textContent = guest.phone || 'N/A';
                        document.getElementById('guestModalGender').textContent = guest.gender || 'N/A';
                        document.getElementById('guestModalIdNumber').textContent = guest.id_number || 'N/A';
                        document.getElementById('guestModalAddress').textContent = guest.address || 'N/A';
                        document.getElementById('guestModalEditBtn').href = guest.edit_url || '#';

                        var bookingsContainer = document.getElementById('guestModalBookings');
                        if (guest.bookings && guest.bookings.length > 0) {
                            var html = '<div class="list-group list-group-flush">';
                            guest.bookings.forEach(function (b) {
                                html += '<div class="list-group-item bg-transparent text-light px-0 py-2 border-secondary border-opacity-25 d-flex justify-content-between align-items-center">' +
                                    '<div>' +
                                    '<span class="fw-semibold text-warning">Room ' + b.room + '</span> (' + b.room_type + ')<br>' +
                                    '<small class="text-muted">' + b.check_in + ' - ' + b.check_out + '</small>' +
                                    '</div>' +
                                    '<div class="text-end">' +
                                    '<span class="badge bg-secondary">' + b.status + '</span><br>' +
                                    '<small class="text-warning">$' + b.price + '</small>' +
                                    '</div>' +
                                    '</div>';
                            });
                            html += '</div>';
                            bookingsContainer.innerHTML = html;
                        } else {
                            bookingsContainer.innerHTML = '<p class="text-muted mb-0">No booking history recorded for this guest yet.</p>';
                        }

                        if (window.bootstrap && window.bootstrap.Modal) {
                            bootstrap.Modal.getOrCreateInstance(modalEl).show();
                        } else {
                            modalEl.classList.add('show');
                            modalEl.style.display = 'block';
                            document.body.classList.add('modal-open');
                        }
                    } catch (err) {
                        console.error('Error opening guest modal', err);
                    }
                });
            });
        }
    })();
</script>
@endsection
