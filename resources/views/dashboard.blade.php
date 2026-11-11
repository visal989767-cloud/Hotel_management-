@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php
    $occupancy = $rooms > 0 ? round(($occupied / $rooms) * 100) : 0;
    $daysInMonth = $monthStart->daysInMonth;
    $calendarOffset = $monthStart->dayOfWeek;
    $bookingDays = $calendarBookings->map(fn ($booking) => $booking->check_in->day)->all();
    $roomTypeImages = [
        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=640&q=80',
        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=640&q=80',
        'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=640&q=80',
        'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=640&q=80',
    ];
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');

    body.cinematic-dashboard {
        --bs-body-bg: #071114;
        --bs-body-color: #e7e9e7;
        position: relative;
        isolation: isolate;
        background: #071114 !important;
        color: #e7e9e7;
        font-family: 'Figtree', system-ui, sans-serif;
    }

    body.cinematic-dashboard::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            linear-gradient(180deg, rgba(6,14,16,.48), rgba(6,14,16,.7)),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=85') center / cover no-repeat;
    }

    .cinematic-dashboard .container { max-width: 1440px; }

    .cinematic-dashboard .navbar {
        position: relative;
        z-index: 1;
    }

    .hotel-dashboard {
        --blue: #4a96f5;
        --blue-dark: #327edc;
        --blue-light: rgba(74, 150, 245, .14);
        --orange: #ff6b35;
        --ink: #f3e7d2;
        --muted: #a9b5b6;
        --line: rgba(224, 232, 236, .15);
        color: #e7e9e7;
        font-family: 'Figtree', system-ui, sans-serif;
        padding-bottom: 1.5rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 2.08fr) minmax(270px, .92fr);
        align-items: start;
        gap: 1rem;
    }

    .dashboard-main,
    .dashboard-sidebar { min-width: 0; display: grid; gap: 1rem; align-content: start; }

    .dashboard-card {
        background: rgba(8, 14, 16, .78);
        border: 1px solid rgba(208, 170, 112, .24);
        border-radius: 12px;
        box-shadow: 0 18px 50px -35px rgba(0, 0, 0, .8);
        backdrop-filter: blur(8px);
        min-width: 0;
    }

    .dashboard-card-heading {
        min-height: 42px;
        padding: .8rem 1rem .25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
    }

    .dashboard-card-heading h2 {
        margin: 0;
        color: var(--ink);
        font-size: .98rem;
        font-weight: 700;
        letter-spacing: -.02em;
    }

    .dashboard-card-heading a,
    .dashboard-link {
        color: var(--blue);
        font-size: .76rem;
        font-weight: 500;
        text-decoration: none;
    }
    .dashboard-card-heading a:hover,
    .dashboard-link:hover { color: var(--blue-dark); }

    .heading-tools { display: flex; align-items: center; gap: .45rem; color: #9aa2ab; font-size: .72rem; }
    .heading-tools label { margin: 0; white-space: nowrap; }
    .heading-tools select {
        border: 0;
        border-radius: 6px;
        background: var(--blue-light);
        color: var(--blue-dark);
        padding: .32rem .55rem;
        font: inherit;
        outline-color: var(--blue);
        cursor: pointer;
    }

    .room-type-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .7rem;
        padding: .35rem .7rem .75rem;
    }

    .room-type-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid #eff1f4;
        border-radius: 10px;
        padding: .45rem;
        transition: box-shadow .18s ease, transform .18s ease;
    }
    .room-type-card:hover { transform: translateY(-2px); box-shadow: 0 7px 20px rgba(35, 56, 76, .09); }

    .room-type-image {
        position: relative;
        overflow: hidden;
        border-radius: 7px;
        height: 100px;
        background: #e8f1f8;
    }
    .room-type-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .room-type-capacity {
        position: absolute;
        top: .4rem;
        right: .4rem;
        padding: .18rem .45rem;
        border-radius: 20px;
        background: #fff;
        color: #4b5560;
        font-size: .65rem;
        font-weight: 700;
        box-shadow: 0 1px 5px rgba(0,0,0,.1);
    }
    .room-type-card h3 {
        overflow: hidden;
        margin: .48rem 0 .1rem;
        color: #222931;
        font-size: .8rem;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .room-type-subtitle { color: var(--muted); font-size: .69rem; }
    .room-type-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .3rem;
        margin-top: .48rem;
    }
    .room-type-price { color: var(--orange); font-size: .81rem; font-weight: 700; white-space: nowrap; }
    .room-type-price small { color: #a7adb3; font-size: .67rem; font-weight: 400; }
    .small-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 5px;
        background: var(--blue);
        color: #fff;
        padding: .32rem .55rem;
        font-size: .66rem;
        font-weight: 600;
        line-height: 1.25;
        text-decoration: none;
        white-space: nowrap;
    }
    .small-action:hover { background: var(--blue-dark); color: #fff; }

    .overview-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .overview-card { min-height: 225px; }
    .icon-button {
        display: grid;
        width: 25px;
        height: 25px;
        place-items: center;
        border: 0;
        border-radius: 5px;
        background: #eef6fd;
        color: #6d849a;
        font-size: .85rem;
        line-height: 1;
        text-decoration: none;
        transition: background .18s ease, color .18s ease, transform .18s ease;
    }
    .icon-button:hover,
    .icon-button:focus-visible { background: rgba(176, 141, 87, .22); color: #f3e7d2; transform: translateY(-1px); }
    .icon-button:focus-visible,
    .calendar-controls a:focus-visible,
    .small-action:focus-visible { outline: 2px solid #d7b477; outline-offset: 2px; }

    .occupancy-overview { padding: .5rem 1rem .85rem; }
    .occupancy-chart {
        width: 142px;
        height: 142px;
        margin: .15rem auto .7rem;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: conic-gradient(var(--blue) var(--occupancy), #e6f2fd 0);
        transform: rotate(-130deg);
        color: inherit;
        text-decoration: none;
        transition: filter .18s ease, transform .18s ease;
    }
    a.occupancy-chart:hover { filter: brightness(1.12); transform: rotate(-130deg) scale(1.035); }
    a.occupancy-chart:focus-visible { outline: 2px solid #d7b477; outline-offset: 4px; }
    .occupancy-chart::before {
        content: "";
        grid-area: 1 / 1;
        width: 114px;
        height: 114px;
        border-radius: 50%;
        background: #fff;
    }
    .occupancy-chart-content {
        grid-area: 1 / 1;
        z-index: 1;
        transform: rotate(130deg);
        text-align: center;
    }
    .occupancy-chart-content strong { display: block; font-size: 1.4rem; line-height: 1.2; }
    .occupancy-chart-content span { color: var(--muted); font-size: .68rem; }
    .occupancy-legend { display: flex; justify-content: space-around; gap: .5rem; }
    .occupancy-legend div { padding-left: .5rem; border-left: 2px solid var(--blue); }
    .occupancy-legend a {
        display: block;
        border-radius: 3px;
        color: inherit;
        text-decoration: none;
        transition: transform .18s ease, background .18s ease;
    }
    .occupancy-legend a:hover { transform: translateY(-2px); }
    .occupancy-legend a:focus-visible { outline: 2px solid #d7b477; outline-offset: 3px; }
    .occupancy-legend div:first-child { border-color: #a9d2fa; }
    .occupancy-legend div:last-child { border-color: #d7e9f8; }
    .occupancy-legend strong { display: block; font-size: .72rem; font-weight: 600; }
    .occupancy-legend span { color: var(--muted); font-size: .63rem; }

    .room-breakdown { padding: .25rem .8rem .75rem; }
    .room-breakdown-total {
        position: relative;
        margin-bottom: .65rem;
        padding: .65rem .7rem;
        border-radius: 7px;
        background: var(--blue-light);
    }
    .room-breakdown-total strong { display: block; color: var(--orange); font-size: 1.08rem; }
    .room-breakdown-total span { color: var(--muted); font-size: .66rem; }
    .room-breakdown-total i {
        color: var(--blue);
        font-size: 1.2rem;
    }
    .guest-total-link {
        position: absolute;
        top: 50%;
        right: .7rem;
        display: grid;
        place-items: center;
        color: var(--blue);
        text-decoration: none;
        transform: translateY(-50%);
        transition: color .18s ease, transform .18s ease;
    }
    .guest-total-link:hover { color: #d7b477; transform: translateY(-50%) translateX(2px); }
    .guest-total-link:focus-visible { outline: 2px solid #d7b477; outline-offset: 3px; border-radius: 50%; }
    .room-breakdown-row {
        display: grid;
        grid-template-columns: 30px minmax(0, 1fr) auto;
        align-items: center;
        gap: .5rem;
        padding: .34rem 0;
    }
    .room-breakdown-thumb { width: 30px; height: 30px; border-radius: 5px; object-fit: cover; }
    .room-breakdown-info { min-width: 0; }
    .room-breakdown-info strong {
        display: block;
        overflow: hidden;
        font-size: .68rem;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .room-breakdown-track { height: 4px; margin-top: .23rem; overflow: hidden; border-radius: 5px; background: #f0f1f2; }
    .room-breakdown-track span { display: block; height: 100%; border-radius: inherit; background: var(--blue); }
    .room-breakdown-value { color: #4a5057; font-size: .65rem; }

    .booking-table-wrap { overflow-x: auto; }
    .hotel-dashboard .booking-table { width: 100%; min-width: 680px; margin: 0; border-collapse: separate; border-spacing: 0; }
    .hotel-dashboard .booking-table thead th {
        padding: .65rem .8rem;
        border: 0;
        background: #eef6fd;
        color: #75818d;
        font-size: .64rem;
        font-weight: 500;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .hotel-dashboard .booking-table thead th:first-child { padding-left: 1rem; }
    .hotel-dashboard .booking-table tbody td {
        padding: .62rem .8rem;
        border-bottom: 1px solid #f0f1f3;
        color: #353c44;
        font-size: .7rem;
        white-space: nowrap;
    }
    .hotel-dashboard .booking-table tbody tr:last-child td { border-bottom: 0; }
    .booking-id { color: #d7b477; font-weight: 600; text-decoration: none; }
    .booking-id:hover { color: #f3e7d2; text-decoration: underline; }
    .booking-guest { display: block; color: #838b94; font-size: .65rem; }
    .empty-state { padding: 1.75rem 1rem !important; color: var(--muted) !important; text-align: center; }

    .calendar-card { padding-bottom: .65rem; }
    .calendar-controls { display: flex; align-items: center; justify-content: space-between; padding: .15rem .8rem .3rem; }
    .calendar-controls button {
        width: 23px;
        height: 23px;
        border: 0;
        border-radius: 5px;
        background: #eef6fd;
        color: var(--blue);
        line-height: 1;
    }
    .calendar-controls a {
        display: grid;
        width: 27px;
        height: 27px;
        place-items: center;
        border: 1px solid rgba(208, 170, 112, .2);
        border-radius: 6px;
        background: rgba(255, 255, 255, .07);
        color: #d7b477;
        text-decoration: none;
        transition: background .18s ease, color .18s ease, transform .18s ease;
    }
    .calendar-controls a:hover { background: #b08d57; color: #0f2429; transform: translateY(-1px); }
    .calendar-month { display: flex; gap: .8rem; color: #5e6872; font-size: .65rem; font-weight: 600; }
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); padding: 0 .55rem; }
    .calendar-weekday, .calendar-day {
        position: relative;
        display: grid;
        min-height: 26px;
        place-items: center;
        color: #808994;
        font-size: .61rem;
    }
    .calendar-day { color: #4c555f; }
    .calendar-day.is-today span {
        display: grid;
        width: 20px;
        height: 20px;
        place-items: center;
        border-radius: 6px;
        background: var(--blue);
        color: #fff;
    }
    .calendar-day.has-booking::after {
        position: absolute;
        bottom: 1px;
        left: calc(50% - 2px);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--orange);
        content: "";
    }
    .calendar-day.is-today.has-booking::after { background: var(--blue-dark); }

    .arrival-list, .activity-list { padding: .15rem .85rem .75rem; }
    .arrival-item {
        display: grid;
        grid-template-columns: 42px minmax(0, 1fr);
        align-items: center;
        gap: .55rem;
        padding: .35rem 0;
    }
    .arrival-thumb {
        width: 42px;
        height: 42px;
        border-radius: 7px;
        object-fit: cover;
    }
    .arrival-item strong {
        display: block;
        overflow: hidden;
        color: #252b32;
        font-size: .72rem;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .arrival-item span { color: var(--muted); font-size: .62rem; }
    .arrival-item .bi { margin-right: .18rem; }
    .sidebar-empty { padding: .75rem .1rem; color: var(--muted); font-size: .72rem; }

    .activity-item {
        position: relative;
        display: grid;
        grid-template-columns: 24px minmax(0, 1fr);
        gap: .55rem;
        padding: .25rem 0 .75rem;
    }
    .activity-item + .activity-item { padding-top: .65rem; }
    .activity-icon {
        display: grid;
        width: 22px;
        height: 22px;
        place-items: center;
        border-radius: 50%;
        background: var(--blue);
        color: #fff;
        font-size: .65rem;
    }
    .activity-item p { margin: 0; color: #515963; font-size: .69rem; line-height: 1.45; }
    .activity-item strong { color: #252b32; font-weight: 600; }
    .activity-item time { display: block; margin-top: .25rem; color: var(--muted); font-size: .6rem; }
    .dashboard-fade { animation: dashboardFade .45s ease both; }
    @keyframes dashboardFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 991.98px) {
        .dashboard-grid { grid-template-columns: minmax(0, 1fr) 280px; gap: .75rem; }
        .overview-grid { gap: .75rem; }
        .dashboard-main, .dashboard-sidebar { gap: .75rem; }
        .room-type-image { height: 85px; }
    }
    @media (max-width: 767.98px) {
        .dashboard-grid { grid-template-columns: minmax(0, 1fr); }
        .dashboard-sidebar { grid-template-columns: repeat(2, minmax(0, 1fr)); align-items: start; }
        .calendar-card { grid-column: 1 / -1; }
    }
    @media (max-width: 575.98px) {
        .cinematic-dashboard .container { padding-right: .75rem; padding-left: .75rem; }
        .dashboard-card-heading { padding-right: .8rem; padding-left: .8rem; }
        .room-type-grid { grid-template-columns: 1fr; }
        .room-type-card { display: grid; grid-template-columns: 38% minmax(0, 1fr); align-items: center; gap: .6rem; }
        .room-type-image { grid-row: span 3; height: 95px; }
        .room-type-card h3 { margin-top: 0; }
        .room-type-footer { margin-top: .15rem; }
        .overview-grid { grid-template-columns: 1fr; }
        .overview-card { min-height: auto; }
        .occupancy-chart { width: 132px; height: 132px; }
        .occupancy-chart::before { width: 106px; height: 106px; }
        .dashboard-sidebar { grid-template-columns: 1fr; }
        .calendar-card { grid-column: auto; }
    }
    @media (prefers-reduced-motion: reduce) {
        .hotel-dashboard *, .hotel-dashboard *::before, .hotel-dashboard *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
        }
    }

    .dashboard-card-heading h2,
    .room-type-card h3,
    .room-breakdown-info strong,
    .arrival-item strong,
    .activity-item strong { color: #f3e7d2; }

    .dashboard-card-heading a,
    .dashboard-link { color: #d7b477; }
    .dashboard-card-heading a:hover,
    .dashboard-link:hover { color: #e8c790; }

    .heading-tools { color: #a9b5b6; }
    .heading-tools select,
    .icon-button,
    .calendar-controls button {
        border: 1px solid rgba(208, 170, 112, .2);
        background: rgba(255, 255, 255, .07);
        color: #d7b477;
    }

    .room-type-card {
        border-color: rgba(224, 232, 236, .15);
        background: rgba(4, 8, 10, .34);
    }
    .room-type-image { background: #17333a; }
    .room-type-capacity {
        background: rgba(8, 14, 16, .88);
        color: #f3e7d2;
    }
    .room-type-subtitle,
    .room-type-price small,
    .occupancy-legend span,
    .room-breakdown-total span,
    .arrival-item span,
    .activity-item time,
    .sidebar-empty { color: #a9b5b6; }
    .room-type-price { color: #ff9a64; }
    .small-action {
        border: 1px solid rgba(208, 170, 112, .55);
        background: #b08d57;
        color: #0f2429;
    }
    .small-action:hover { background: #c19d66; color: #0f2429; }
    .room-type-card:hover { border-color: rgba(208, 170, 112, .4); }

    .occupancy-chart::before { background: #0c171a; }
    .occupancy-chart-content strong,
    .occupancy-legend strong { color: #f5f2ed; }
    .occupancy-chart-content span { color: #a9b5b6; }
    .occupancy-legend div:first-child { border-color: #a9d2fa; }
    .occupancy-legend div:nth-child(2) { border-color: #4a96f5; }
    .occupancy-legend div:last-child { border-color: #d7b477; }

    .room-breakdown-total { background: rgba(74, 150, 245, .12); }
    .room-breakdown-track { background: rgba(255, 255, 255, .12); }
    .room-breakdown-value { color: #d7d9d8; }
    .hotel-dashboard .booking-table thead th {
        background: rgba(255, 255, 255, .055);
        color: #c0b49f;
    }
    .hotel-dashboard .booking-table tbody td { color: #e7e9e7; }
    .hotel-dashboard .booking-table tbody tr:hover > * {
        --bs-table-bg-state: rgba(196, 154, 90, .08);
        color: #fff;
    }
    .booking-id { color: #d7b477; }
    .booking-guest { color: #a9b5b6; }
    .calendar-weekday { color: #a9b5b6; }
    .calendar-day { color: #e0e5e3; }
    .calendar-day.is-today span { background: #b08d57; color: #0f2429; }
    .calendar-day.is-today.has-booking::after { background: #f3e7d2; }
    .activity-item p { color: #c1cbca; }
    .activity-item strong { color: #f3e7d2; }
    .text-muted { color: #a9b5b6 !important; }
    .empty-state { color: #a9b5b6 !important; }
</style>

<main class="hotel-dashboard dashboard-fade">
    <div class="dashboard-grid">
        <div class="dashboard-main">
            <section class="dashboard-card" aria-labelledby="room-types-heading">
                <div class="dashboard-card-heading">
                    <h2 id="room-types-heading">Room Types</h2>
                    <form class="heading-tools" method="GET" action="{{ route('dashboard') }}">
                        <input type="hidden" name="month" value="{{ $monthStart->format('Y-m') }}">
                        <label for="room-type-sort">Sort by:</label>
                        <select id="room-type-sort" name="sort" aria-label="Sort room types" onchange="this.form.submit()">
                            <option value="price_asc" @selected($roomTypeSort === 'price_asc')>Price: low to high</option>
                            <option value="price_desc" @selected($roomTypeSort === 'price_desc')>Price: high to low</option>
                            <option value="rooms_desc" @selected($roomTypeSort === 'rooms_desc')>Most rooms</option>
                            <option value="name" @selected($roomTypeSort === 'name')>Name</option>
                        </select>
                    </form>
                </div>
                <div class="room-type-grid">
                    @forelse ($roomTypes as $index => $roomType)
                        <article class="room-type-card">
                            <div class="room-type-image">
                                <img src="{{ $roomTypeImages[$index % count($roomTypeImages)] }}" alt="" loading="lazy">
                                <span class="room-type-capacity"><i class="bi bi-people-fill"></i> {{ $roomType->capacity }}</span>
                            </div>
                            <h3>{{ $roomType->type_name }}</h3>
                            <div class="room-type-subtitle">{{ $roomType->rooms_count }} {{ \Illuminate\Support\Str::plural('room', $roomType->rooms_count) }} · up to {{ $roomType->capacity }} {{ \Illuminate\Support\Str::plural('guest', $roomType->capacity) }}</div>
                            <div class="room-type-footer">
                                <span class="room-type-price">${{ number_format($roomType->price_per_night, 0) }}<small>/night</small></span>
                                <a class="small-action" href="{{ route('room-types.index') }}">Manage</a>
                            </div>
                        </article>
                    @empty
                        <p class="sidebar-empty">Add room types to see them here.</p>
                    @endforelse
                </div>
            </section>

            <div class="overview-grid">
                <section class="dashboard-card overview-card" aria-labelledby="occupancy-heading">
                    <div class="dashboard-card-heading">
                        <h2 id="occupancy-heading">Room Overview</h2>
                        <a class="icon-button" href="{{ route('rooms.index') }}" aria-label="View all rooms" title="View all rooms"><i class="bi bi-arrow-up-right"></i></a>
                    </div>
                    <div class="occupancy-overview">
                        <a class="occupancy-chart" href="{{ route('rooms.index') }}" style="--occupancy: {{ $occupancy }}%" aria-label="View all {{ $rooms }} rooms">
                            <div class="occupancy-chart-content">
                                <strong>{{ number_format($rooms) }}</strong>
                                <span>Total Rooms</span>
                            </div>
                        </a>
                        <div class="occupancy-legend">
                            <div><a href="{{ route('rooms.index', ['status' => 'available']) }}" aria-label="View {{ $available }} available rooms"><strong>{{ $available }}</strong><span>Available</span></a></div>
                            <div><a href="{{ route('rooms.index', ['status' => 'occupied']) }}" aria-label="View {{ $occupied }} occupied rooms"><strong>{{ $occupied }}</strong><span>Occupied</span></a></div>
                            <div><a href="{{ route('rooms.index', ['status' => 'occupied']) }}" aria-label="View occupied rooms for {{ $occupancy }} percent occupancy"><strong>{{ $occupancy }}%</strong><span>Occupancy</span></a></div>
                        </div>
                    </div>
                </section>

                <section class="dashboard-card overview-card" aria-labelledby="room-mix-heading">
                    <div class="dashboard-card-heading">
                        <h2 id="room-mix-heading">Room Type Mix</h2>
                        <a class="icon-button" href="{{ route('room-types.index') }}" aria-label="View all room types" title="View all room types"><i class="bi bi-arrow-up-right"></i></a>
                    </div>
                    <div class="room-breakdown">
                        <div class="room-breakdown-total">
                            <strong>{{ number_format($guests) }}</strong>
                            <span>Total Guests</span>
                            <a class="guest-total-link" href="{{ route('guests.index') }}" aria-label="View all guests" title="View all guests"><i class="bi bi-arrow-right-circle"></i></a>
                        </div>
                        @forelse ($roomTypes as $index => $roomType)
                            @php
                                $typeShare = $rooms > 0 ? round(($roomType->rooms_count / $rooms) * 100) : 0;
                            @endphp
                            <div class="room-breakdown-row">
                                <img class="room-breakdown-thumb" src="{{ $roomTypeImages[$index % count($roomTypeImages)] }}" alt="" loading="lazy">
                                <div class="room-breakdown-info">
                                    <strong>{{ $roomType->type_name }}</strong>
                                    <div class="room-breakdown-track"><span style="width: {{ $typeShare }}%"></span></div>
                                </div>
                                <span class="room-breakdown-value">{{ $typeShare }}%</span>
                            </div>
                        @empty
                            <p class="sidebar-empty">No room type data yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="dashboard-card" aria-labelledby="booking-history-heading">
                <div class="dashboard-card-heading">
                    <h2 id="booking-history-heading">Booking History</h2>
                    <a href="{{ route('bookings.index') }}">View all</a>
                </div>
                <div class="booking-table-wrap">
                    <table class="table booking-table align-middle">
                        <thead>
                            <tr>
                                <th>ID / Guest</th><th>Room</th><th>Check-in</th><th>Duration</th><th>Amount</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($recent as $booking)
                            <tr>
                                <td><a class="booking-id" href="{{ route('bookings.edit', $booking) }}">#{{ $booking->booking_id }}</a><span class="booking-guest">{{ $booking->guest->guest_name }}</span></td>
                                <td>{{ $booking->room->room_number }} <span class="text-muted">({{ $booking->room->roomType->type_name }})</span></td>
                                <td>{{ $booking->check_in->format('M d, Y') }}</td>
                                <td>{{ $booking->nights }} {{ \Illuminate\Support\Str::plural('night', $booking->nights) }}</td>
                                <td>${{ number_format($booking->total_price, 2) }}</td>
                                <td>@include('bookings._status', ['status' => $booking->status])</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-state">No bookings yet. New reservations will appear here.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <aside class="dashboard-sidebar">
            <section class="dashboard-card calendar-card" aria-labelledby="calendar-heading">
                <div class="dashboard-card-heading">
                    <h2 id="calendar-heading">Calendar</h2>
                    <a class="icon-button" href="{{ route('dashboard', ['month' => now()->format('Y-m'), 'sort' => $roomTypeSort]) }}" aria-label="Go to current month" title="Go to current month"><i class="bi bi-calendar-check"></i></a>
                </div>
                <div class="calendar-controls">
                    <a href="{{ route('dashboard', ['month' => $monthStart->copy()->subMonth()->format('Y-m'), 'sort' => $roomTypeSort]) }}" aria-label="Previous month" title="Previous month"><i class="bi bi-chevron-left"></i></a>
                    <div class="calendar-month"><span>{{ $monthStart->format('M') }}</span><span>{{ $monthStart->format('Y') }}</span></div>
                    <a href="{{ route('dashboard', ['month' => $monthStart->copy()->addMonth()->format('Y-m'), 'sort' => $roomTypeSort]) }}" aria-label="Next month" title="Next month"><i class="bi bi-chevron-right"></i></a>
                </div>
                <div class="calendar-grid" role="grid" aria-label="{{ $monthStart->format('F Y') }}">
                    @foreach (['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $weekday)
                        <span class="calendar-weekday" role="columnheader">{{ $weekday }}</span>
                    @endforeach
                    @for ($blank = 0; $blank < $calendarOffset; $blank++)
                        <span class="calendar-day" role="gridcell" aria-hidden="true"></span>
                    @endfor
                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        <span class="calendar-day {{ $monthStart->isSameMonth(now()) && $day === now()->day ? 'is-today' : '' }} {{ in_array($day, $bookingDays, true) ? 'has-booking' : '' }}" role="gridcell" aria-label="{{ $monthStart->copy()->day($day)->format('F j') }}">
                            <span>{{ $day }}</span>
                        </span>
                    @endfor
                </div>
            </section>

            <section class="dashboard-card" aria-labelledby="arrivals-heading">
                <div class="dashboard-card-heading">
                    <h2 id="arrivals-heading">Upcoming Arrivals</h2>
                    <a href="{{ route('bookings.index') }}">View all</a>
                </div>
                <div class="arrival-list">
                    @forelse ($upcomingArrivals as $index => $booking)
                        <a class="arrival-item text-decoration-none" href="{{ route('bookings.edit', $booking) }}">
                            <img class="arrival-thumb" src="{{ $roomTypeImages[$index % count($roomTypeImages)] }}" alt="" loading="lazy">
                            <span>
                                <strong>{{ $booking->guest->guest_name }}</strong>
                                <span><i class="bi bi-calendar-event"></i>{{ $booking->check_in->format('M j') }} · Room {{ $booking->room->room_number }}</span>
                            </span>
                        </a>
                    @empty
                        <p class="sidebar-empty">No upcoming arrivals scheduled.</p>
                    @endforelse
                </div>
            </section>

            <section class="dashboard-card" aria-labelledby="activity-heading">
                <div class="dashboard-card-heading">
                    <h2 id="activity-heading">Recent Activity</h2>
                    <a class="icon-button" href="{{ route('bookings.index') }}" aria-label="View booking activity" title="View booking activity"><i class="bi bi-arrow-up-right"></i></a>
                </div>
                <div class="activity-list">
                    @forelse ($recent->take(3) as $booking)
                        <div class="activity-item">
                            <span class="activity-icon"><i class="bi bi-calendar-check"></i></span>
                            <div>
                                <p><strong>{{ $booking->guest->guest_name }}</strong> · {{ ucfirst(str_replace('_', ' ', $booking->status)) }} booking for room {{ $booking->room->room_number }}.</p>
                                <time datetime="{{ $booking->created_at->toIso8601String() }}">{{ $booking->created_at->diffForHumans() }}</time>
                            </div>
                        </div>
                    @empty
                        <p class="sidebar-empty">Booking activity will appear here.</p>
                    @endforelse
                </div>
            </section>
        </aside>
    </div>
</main>
@endsection
