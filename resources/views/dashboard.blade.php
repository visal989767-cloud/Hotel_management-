@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php
    $occupancy = $rooms > 0 ? round(($occupied / $rooms) * 100) : 0;
    $stats = [
        ['Room types', $roomTypes],
        ['Total rooms', $rooms],
        ['Available', $available],
        ['Occupied', $occupied],
        ['Guests', $guests],
        ['Bookings', $bookings],
    ];
@endphp

<style>
    /* ---------- Navbar theme (overrides Bootstrap blue) ---------- */
    body { background: #030608 !important; font-family: 'Outfit', sans-serif; color: #f5f2ed; }

    .navbar {
        background: #0f2429 !important;
        border-bottom: 2px solid #b08d57;
        padding: .8rem 0;
        box-shadow: 0 10px 30px -18px rgba(15,36,41,.8);
    }

    .navbar .navbar-brand {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.7rem;
        font-weight: 600;
        letter-spacing: .02em;
        color: #fff !important;
    }

    .navbar .navbar-brand .bi,
    .navbar .navbar-brand i { color: #b08d57; }

    .navbar .nav-link {
        position: relative;
        padding: .5rem .85rem !important;
        font-weight: 500;
        color: rgba(255,255,255,.68) !important;
        transition: color .2s ease;
    }

    .navbar .nav-link::after {
        content: "";
        position: absolute;
        left: .85rem;
        right: .85rem;
        bottom: .1rem;
        height: 2px;
        background: #b08d57;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .3s cubic-bezier(.22,.8,.24,1);
    }

    .navbar .nav-link:hover,
    .navbar .nav-link:focus-visible,
    .navbar .nav-link.active { color: #fff !important; }

    .navbar .nav-link:hover::after,
    .navbar .nav-link:focus-visible::after,
    .navbar .nav-link.active::after { transform: scaleX(1); }

    .navbar .btn {
        background: transparent;
        border: 1px solid rgba(255,255,255,.35);
        color: #fff;
        font-weight: 500;
        border-radius: 6px;
        transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
    }

    .navbar .btn:hover,
    .navbar .btn:focus-visible {
        background: #b08d57;
        border-color: #b08d57;
        color: #0f2429;
        transform: translateY(-1px);
    }

    .navbar .navbar-toggler { border-color: rgba(255,255,255,.35); }
    .navbar .navbar-toggler:focus { box-shadow: 0 0 0 .2rem rgba(176,141,87,.35); }
</style>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Italiana&family=Outfit:wght@300;400;600&display=swap');

    .dash {
        --pine: #17333a;
        --pine-deep: #0f2429;
        --brass: #b08d57;
        --brass-dark: #8f7142;
        --ivory: #fbfaf8;
        --ink: #1f2a2e;
        --muted: #6b7b7f;
        --line: #dfe5e4;
        --ease: cubic-bezier(.22, .8, .24, 1);
        font-family: 'Figtree', system-ui, sans-serif;
        color: var(--ink);
    }

    @keyframes dashRise {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes dashGrow {
        from { width: 0; }
        to   { width: 56px; }
    }
    @keyframes dashBar {
        from { transform: scaleX(0); }
        to   { transform: scaleX(1); }
    }

    .dash .reveal {
        opacity: 0;
        animation: dashRise 1.2s var(--ease) forwards;
        animation-delay: calc(var(--i, 0) * 180ms + 200ms);
    }

    /* ---------- Hero header ---------- */
    @keyframes heroFade {
        0%   { opacity: 0; }
        8%   { opacity: 1; }
        33%  { opacity: 1; }
        41%  { opacity: 0; }
        100% { opacity: 0; }
    }
    @keyframes heroZoom {
        from { transform: scale(1) translate3d(0, 0, 0); }
        to   { transform: scale(1.14) translate3d(-1.5%, -1%, 0); }
    }
    @keyframes heroDot {
        0%   { transform: scaleX(0); }
        33%  { transform: scaleX(1); }
        33.01%, 100% { transform: scaleX(0); }
    }

    .dash-hero {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        border-radius: 22px;
        margin-bottom: 1.75rem;
        padding: 2.25rem 2.5rem 1.75rem;
        min-height: 320px;
        color: #fff;
        background: var(--pine);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 2rem;
        box-shadow: 0 24px 48px -28px rgba(15,36,41,.65);
    }

    .hero-slides { position: absolute; inset: 0; z-index: -2; background: var(--pine); }

    .hero-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        will-change: opacity, transform;
        animation: heroFade 21s ease-in-out infinite, heroZoom 21s linear infinite;
    }
    .hero-slide:nth-child(1) { background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1800&q=80'); animation-delay: 0s; }
    .hero-slide:nth-child(2) { background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1800&q=80'); animation-delay: 7s; }
    .hero-slide:nth-child(3) { background-image: url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1800&q=80'); animation-delay: 14s; }

    .dash-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        background:
            linear-gradient(90deg, rgba(15,36,41,.88) 0%, rgba(15,36,41,.55) 55%, rgba(15,36,41,.35) 100%),
            linear-gradient(180deg, rgba(15,36,41,.1) 0%, rgba(15,36,41,.7) 100%);
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .hero-date { font-size: .9rem; color: rgba(255,255,255,.8); margin-bottom: .75rem; }

    .dash-rule {
        height: 2px;
        width: 56px;
        background: var(--brass);
        margin-bottom: 1.1rem;
        animation: dashGrow 1.6s var(--ease) .6s both;
    }

    .hero-title {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 500;
        font-size: clamp(2.2rem, 3.6vw, 3.3rem);
        line-height: 1.08;
        margin: 0 0 .75rem;
        color: #fff;
    }

    .hero-text { max-width: 44ch; margin: 0; color: rgba(255,255,255,.82); }

    .hero-actions { display: flex; gap: .65rem; flex-wrap: wrap; }

    .hero-btn {
        position: relative;
        overflow: hidden;
        display: inline-block;
        padding: .7rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: .95rem;
        text-decoration: none;
        transition: transform .2s var(--ease), box-shadow .2s ease, background .2s ease, border-color .2s ease;
    }

    .hero-btn::after {
        content: "";
        position: absolute;
        top: 0;
        left: -75%;
        width: 50%;
        height: 100%;
        background: linear-gradient(100deg, transparent, rgba(255,255,255,.28), transparent);
        transform: skewX(-20deg);
    }

    .hero-btn:hover, .hero-btn:focus-visible { transform: translateY(-2px); }
    .hero-btn:hover::after, .hero-btn:focus-visible::after { left: 130%; transition: left .7s var(--ease); }
    .hero-btn:focus-visible { outline: 2px solid #fff; outline-offset: 2px; }

    .hero-btn-primary {
        background: var(--brass);
        border: 1px solid var(--brass);
        color: var(--pine-deep);
    }
    .hero-btn-primary:hover, .hero-btn-primary:focus-visible {
        background: #c19d66;
        border-color: #c19d66;
        color: var(--pine-deep);
        box-shadow: 0 10px 22px -10px rgba(0,0,0,.6);
    }

    .hero-btn-ghost {
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.35);
        color: #fff;
        backdrop-filter: blur(6px);
    }
    .hero-btn-ghost:hover, .hero-btn-ghost:focus-visible {
        background: rgba(255,255,255,.2);
        border-color: rgba(255,255,255,.6);
        color: #fff;
    }

    .hero-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .hero-chips { display: flex; gap: .75rem; flex-wrap: wrap; }

    .hero-chip {
        min-width: 130px;
        padding: .75rem 1.1rem;
        border-radius: 14px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.22);
        backdrop-filter: blur(8px);
        display: flex;
        flex-direction: column;
    }
    .hero-chip strong {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 1.8rem;
        line-height: 1.1;
    }
    .hero-chip span { font-size: .85rem; color: rgba(255,255,255,.8); }

    .hero-dots { display: flex; gap: .5rem; }
    .hero-dots span {
        position: relative;
        height: 3px;
        width: 28px;
        border-radius: 2px;
        background: rgba(255,255,255,.3);
        overflow: hidden;
    }
    .hero-dots span::after {
        content: "";
        position: absolute;
        inset: 0;
        background: var(--brass);
        transform: scaleX(0);
        transform-origin: left;
        animation: heroDot 21s linear infinite;
    }
    .hero-dots span:nth-child(2)::after { animation-delay: 7s; }
    .hero-dots span:nth-child(3)::after { animation-delay: 14s; }

    /* ---------- Revenue panel (echoes the login photo panel) ---------- */
    .revenue-panel {
        position: relative;
        height: 100%;
        min-height: 260px;
        padding: 2rem 2.25rem;
        border-radius: 18px;
        color: #fff;
        background:
            linear-gradient(180deg, rgba(15,36,41,.78) 0%, rgba(15,36,41,.9) 100%),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80') center / cover;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }

    .revenue-panel .label { color: rgba(255,255,255,.75); font-size: .9rem; }

    .revenue-panel .amount {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 500;
        font-size: clamp(2.6rem, 4vw, 3.6rem);
        line-height: 1.05;
        margin: .35rem 0 0;
    }

    .occupancy-head {
        display: flex;
        justify-content: space-between;
        font-size: .9rem;
        color: rgba(255,255,255,.85);
        margin-bottom: .5rem;
    }

    .occupancy-track {
        height: 3px;
        border-radius: 2px;
        background: rgba(255,255,255,.28);
        overflow: hidden;
    }

    .occupancy-fill {
        height: 100%;
        background: var(--brass);
        transform-origin: left;
        animation: dashBar 1.8s var(--ease) 1s both;
    }

    /* ---------- Stat grid ---------- */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        height: 100%;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 18px;
        overflow: hidden;
    }

    .stat-cell {
        padding: 1.5rem 1.5rem;
        border-right: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: background .25s ease;
    }

    .stat-cell:nth-child(3n) { border-right: 0; }
    .stat-cell:nth-child(n+4) { border-bottom: 0; }
    .stat-cell:hover { background: var(--ivory); }

    .stat-value {
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 2.4rem;
        line-height: 1;
        color: var(--pine);
    }

    .stat-label { color: var(--muted); font-size: .9rem; margin-top: .4rem; }

    /* ---------- Bookings table ---------- */
    .panel-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 18px;
        overflow: hidden;
    }

    .panel-card-head {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--line);
        font-family: 'Cormorant Garamond', serif;
        font-weight: 600;
        font-size: 1.5rem;
        color: var(--pine);
    }

    .dash .table { margin-bottom: 0; --bs-table-hover-bg: var(--ivory); }

    .dash .table thead th {
        font-weight: 500;
        font-size: .85rem;
        color: var(--muted);
        background: var(--ivory);
        border-bottom: 1px solid var(--line);
        padding: .8rem 1.5rem;
        white-space: nowrap;
    }

    .dash .table tbody td {
        padding: .95rem 1.5rem;
        border-color: var(--line);
        color: var(--ink);
    }

    .dash .table tbody tr:last-child td { border-bottom: 0; }

    .booking-id { font-weight: 600; color: var(--brass-dark); }

    .empty-state { padding: 3rem 1rem; text-align: center; color: var(--muted); }

    /* ---------- Responsive ---------- */
    @media (max-width: 767.98px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
        .stat-cell:nth-child(3n) { border-right: 1px solid var(--line); }
        .stat-cell:nth-child(2n) { border-right: 0; }
        .stat-cell:nth-child(n+4) { border-bottom: 1px solid var(--line); }
        .stat-cell:nth-child(n+5) { border-bottom: 0; }
        .dash-hero { padding: 1.75rem 1.5rem 1.5rem; min-height: 0; }
        .hero-chip { min-width: 0; flex: 1 1 100px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .dash *, .dash *::before, .dash *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
        .dash .reveal { opacity: 1; }
        .dash .hero-slide:nth-child(1) { opacity: 1; }
    }

    .cinematic-dashboard {
        --bs-body-color: #f5f2ed;
        --bs-body-bg: #030608;
        --bs-border-color: rgba(224, 232, 236, .14);
        background: #030608 !important;
    }

    .cinematic-dashboard::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background:
            linear-gradient(180deg, rgba(3,6,8,.52), rgba(3,6,8,.68)),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=85') center / cover no-repeat;
    }

    .cinematic-dashboard .navbar {
        position: relative;
        z-index: 4;
        background: rgba(3, 6, 8, .72) !important;
        border-bottom-color: rgba(176, 141, 87, .65);
        backdrop-filter: blur(14px);
    }

    .cinematic-dashboard .navbar .navbar-brand,
    .cinematic-dashboard .navbar .nav-link,
    .cinematic-dashboard .navbar .navbar-text { color: #f5f2ed !important; }

    .cinematic-dashboard .container {
        position: relative;
        z-index: 2;
    }

    #hotel-bronze-stage {
        opacity: .24;
    }

    .cinematic-grid {
        position: fixed;
        z-index: 1;
        inset: 0 40px;
        pointer-events: none;
        border-left: 1px solid rgba(255,255,255,.035);
        border-right: 1px solid rgba(255,255,255,.035);
        background: linear-gradient(90deg, transparent calc(50% - .5px), rgba(255,255,255,.025) 50%, transparent calc(50% + .5px));
    }

    .cinematic-dashboard .container > .alert {
        position: relative;
        z-index: 3;
        background: rgba(15, 25, 28, .9);
        color: #f5f2ed;
        border-color: rgba(176, 141, 87, .5);
    }

    .dash {
        --pine: #0e171a;
        --pine-deep: #060b0d;
        --brass: #c49a5a;
        --brass-dark: #d7b477;
        --ivory: rgba(255,255,255,.055);
        --ink: #f5f2ed;
        --muted: #a9b5b6;
        --line: rgba(224,232,236,.15);
        color: var(--ink);
    }

    .dash-hero {
        border: 1px solid rgba(208,170,112,.24);
        background: rgba(8, 14, 16, .18);
        backdrop-filter: blur(2px);
        box-shadow: 0 20px 55px -38px rgba(0,0,0,.72);
    }

    .hero-slides {
        background-color: #0e171a;
    }

    .dash-hero::after {
        background:
            linear-gradient(90deg, rgba(4,8,10,.82) 0%, rgba(10,18,21,.52) 58%, rgba(7,15,20,.3) 100%),
            linear-gradient(180deg, rgba(4,8,10,.08) 15%, rgba(4,8,10,.68) 100%);
    }

    .hero-slide { display: block; }
    .hero-copy { position: relative; z-index: 1; }
    .hero-date { text-transform: uppercase; letter-spacing: .16em; font-size: .72rem; color: #d5bd98; }
    .hero-title, .hero-chip strong, .stat-value, .panel-card-head, .revenue-panel .amount {
        font-family: 'Italiana', Georgia, serif;
    }
    .hero-title { font-size: clamp(2.5rem, 5vw, 4.5rem); letter-spacing: .01em; }
    .hero-text, .hero-chip span, .stat-label, .revenue-panel .label { color: #c1cbca; }
    .hero-chip, .hero-btn-ghost {
        background: rgba(255,255,255,.07);
        border-color: rgba(255,255,255,.2);
    }
    .hero-btn-primary { color: #101312; }
    .revenue-panel {
        border: 1px solid rgba(208,170,112,.26);
        background:
            linear-gradient(145deg, rgba(20,31,34,.74), rgba(7,13,16,.84)),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80') center / cover;
    }
    .stat-grid, .panel-card {
        background: rgba(8,14,16,.7);
        border-color: var(--line);
        box-shadow: 0 18px 50px -35px rgba(0,0,0,.9);
        backdrop-filter: blur(8px);
    }
    .stat-cell { border-color: var(--line); }
    .stat-cell:hover { background: rgba(196,154,90,.09); }
    .stat-value, .panel-card-head { color: #f3e7d2; }
    .dash .table { --bs-table-bg: transparent; --bs-table-color: #e7e9e7; --bs-table-hover-color: #fff; --bs-table-hover-bg: rgba(196,154,90,.08); }
    .dash .table thead th { background: rgba(255,255,255,.045); color: #c0b49f; border-color: var(--line); }
    .dash .table tbody td { color: #e7e9e7; border-color: var(--line); }
    .dash .table tbody tr:last-child td { border-bottom-color: transparent; }
    .booking-id { color: #d7b477; }
    .empty-state { color: #a9b5b6 !important; }
    .text-muted { color: #a9b5b6 !important; }

    @media (max-width: 767.98px) {
        .cinematic-grid { inset-inline: 20px; opacity: .35; }
        #hotel-bronze-stage { opacity: .36; }
        .stat-grid, .panel-card { background: rgba(8,14,16,.78); }
    }

    @media (max-width: 575.98px) {
        .dash-hero { border-radius: 18px; }
        .hero-actions { width: 100%; }
        .hero-btn { flex: 1 1 auto; text-align: center; padding-inline: .8rem; }
        .hero-chips { width: 100%; gap: .5rem; }
        .hero-chip { flex: 1 1 calc(50% - .5rem); padding: .7rem .8rem; }
        .stat-cell { padding: 1.15rem; }
        .stat-value { font-size: 2rem; }
        .revenue-panel { min-height: 220px; padding: 1.5rem; }
        .panel-card-head { padding: 1rem 1.1rem; }
        .dash .table thead th,
        .dash .table tbody td { padding-inline: 1rem; }
    }
</style>

<div class="dash">
    <div class="cinematic-grid" aria-hidden="true"></div>
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'morning' : ($hour < 18 ? 'afternoon' : 'evening');
        $firstName = auth()->check() ? \Illuminate\Support\Str::before(auth()->user()->name, ' ') : null;
    @endphp

    <header class="dash-hero reveal" style="--i:0">
        <div class="hero-slides" aria-hidden="true">
            <div class="hero-slide"></div>
            <div class="hero-slide"></div>
            <div class="hero-slide"></div>
        </div>

        <div class="hero-top">
            <div class="hero-copy">
                <div class="hero-date">{{ now()->format('l, F j, Y') }}</div>
                <div class="dash-rule"></div>
                <h1 class="hero-title">Good {{ $greeting }}{{ $firstName ? ', '.$firstName : '' }}</h1>
                <p class="hero-text">{{ $occupied }} of {{ $rooms }} rooms are occupied and {{ $available }} are ready for new guests.</p>
            </div>

            <div class="hero-actions">
                @if (Route::has('bookings.create'))
                    <a href="{{ route('bookings.create') }}" class="hero-btn hero-btn-primary">New booking</a>
                @endif
                @if (Route::has('guests.create'))
                    <a href="{{ route('guests.create') }}" class="hero-btn hero-btn-ghost">Add guest</a>
                @endif
                @if (Route::has('rooms.index'))
                    <a href="{{ route('rooms.index') }}" class="hero-btn hero-btn-ghost">Manage rooms</a>
                @endif
            </div>
        </div>

        <div class="hero-bottom">
            <div class="hero-chips">
                <div class="hero-chip"><strong>{{ $available }}</strong><span>Rooms available</span></div>
                <div class="hero-chip"><strong>{{ $occupied }}</strong><span>Rooms occupied</span></div>
                <div class="hero-chip"><strong>{{ $bookings }}</strong><span>Total bookings</span></div>
            </div>
            <div class="hero-dots" aria-hidden="true"><span></span><span></span><span></span></div>
        </div>
    </header>

    <div class="row g-4 mb-4">
        <div class="col-lg-5 reveal" style="--i:2">
            <div class="revenue-panel">
                <div>
                    <div class="label">Total revenue</div>
                    <p class="amount">${{ number_format($revenue, 2) }}</p>
                </div>
                <div>
                    <div class="occupancy-head">
                        <span>Occupancy</span>
                        <span>{{ $occupied }} of {{ $rooms }} rooms &middot; {{ $occupancy }}%</span>
                    </div>
                    <div class="occupancy-track" role="progressbar" aria-label="Room occupancy"
                         aria-valuenow="{{ $occupancy }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="occupancy-fill" style="width: {{ $occupancy }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 reveal" style="--i:3">
            <div class="stat-grid">
                @foreach ($stats as [$label, $value])
                    <div class="stat-cell">
                        <div class="stat-value">{{ $value }}</div>
                        <div class="stat-label">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="panel-card reveal" style="--i:4">
        <div class="panel-card-head">Recent bookings</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th><th>Guest</th><th>Room</th><th>Check-in</th>
                        <th>Check-out</th><th>Status</th><th>Total</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($recent as $b)
                    <tr>
                        <td class="booking-id">{{ $b->booking_id }}</td>
                        <td>{{ $b->guest->guest_name }}</td>
                        <td>{{ $b->room->room_number }} <small class="text-muted">({{ $b->room->roomType->type_name }})</small></td>
                        <td>{{ $b->check_in->format('M d, Y') }}</td>
                        <td>{{ $b->check_out->format('M d, Y') }}</td>
                        <td>@include('bookings._status', ['status' => $b->status])</td>
                        <td>${{ number_format($b->total_price, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty-state">No bookings yet. New reservations will appear here.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection