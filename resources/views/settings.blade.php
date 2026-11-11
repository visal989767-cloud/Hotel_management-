@extends('layouts.app')
@section('title', 'Settings - Ocean Resort')
@section('content')
@php
    $fmtTime = function ($time) {
        if (!$time) return null;
        try { return \Carbon\Carbon::parse($time)->format('g:i A'); } catch (\Throwable $e) { return $time; }
    };
    $checkInLabel  = $fmtTime($settings['check_in'] ?? null);
    $checkOutLabel = $fmtTime($settings['check_out'] ?? null);
    $resortName    = old('site_name', $settings['site_name'] ?? '') ?: 'Ocean Resort';
    $resortTagline = old('tagline', $settings['tagline'] ?? '') ?: 'Stay Better, Feel Home';

    // Resort contact + location (used when nothing is saved yet, or the old sample text is still saved)
    $defaultAddress = 'No. 86A, Street 110, Russian Federation Blvd (110), Phnom Penh, Cambodia';
    $defaultEmail   = 'setec@gmail.com';
    $mapsUrl        = 'https://share.google/qPQ2Or3cKgvi6dGQ6';

    $savedAddress = trim($settings['address'] ?? '');
    $savedEmail   = trim($settings['email'] ?? '');
    $addressValue = old('address', ($savedAddress === '' || $savedAddress === '2464 Royal Ln, Mesa, New Jersey 46463') ? $defaultAddress : $savedAddress);
    $emailValue   = old('email', ($savedEmail === '' || $savedEmail === 'hello@oceanresort.com') ? $defaultEmail : $savedEmail);

    $amenities = [
        ['bi-wifi', 'High-Speed Wi-Fi'],
        ['bi-snow2', 'Air Conditioning'],
        ['bi-tv', 'Flat-Screen TV'],
        ['bi-water', 'Swimming Pool'],
        ['bi-cup-hot', 'Breakfast Included'],
        ['bi-shield-lock', 'Electronic Safe'],
        ['bi-car-front', 'Free Parking'],
        ['bi-bell', '24/7 Front Desk'],
        ['bi-droplet-half', 'Housekeeping'],
    ];
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Figtree:wght@400;500;600;700&display=swap');

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background:
            linear-gradient(180deg, rgba(6,14,16,.55), rgba(6,14,16,.78)),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=85') center / cover no-repeat;
    }

    .settings-page {
        --gold: #d7b477;
        --gold-deep: #b08d57;
        --cream: #f3e7d2;
        --text: #e7e9e7;
        --muted: #a9b5b6;
        --line: rgba(224, 232, 236, .15);
        --card: rgba(8, 14, 16, .78);
        --ease: cubic-bezier(.22, .8, .24, 1);
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 0 2.5rem;
        color: var(--text);
        font-family: 'Figtree', system-ui, sans-serif;
    }

    @keyframes settingsRise {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { opacity: 0; animation: settingsRise .8s var(--ease) forwards; animation-delay: calc(var(--i, 0) * 120ms); }

    /* ---------- Banner ---------- */
    .settings-banner {
        position: relative;
        overflow: hidden;
        display: grid;
        place-items: center;
        min-height: 190px;
        margin-bottom: 1.75rem;
        border: 1px solid rgba(208, 170, 112, .24);
        border-radius: 16px;
        text-align: center;
        background:
            linear-gradient(180deg, rgba(8,14,16,.45), rgba(8,14,16,.82)),
            url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1800&q=80') center / cover no-repeat;
    }
    .settings-banner h1 {
        margin: 0 0 .4rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2.2rem, 4vw, 3.1rem);
        font-weight: 600;
        letter-spacing: .02em;
        color: #fff;
    }
    .settings-crumbs {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        font-size: .82rem;
        font-weight: 500;
        color: rgba(255,255,255,.8);
    }
    .settings-crumbs a { color: #fff; text-decoration: none; }
    .settings-crumbs a:hover { color: var(--gold); }
    .settings-crumbs .sep { width: 1px; height: 12px; background: rgba(255,255,255,.5); }
    .settings-crumbs .current { color: var(--gold); }

    /* ---------- Layout ---------- */
    .settings-layout {
        display: grid;
        grid-template-columns: minmax(0, 2.1fr) minmax(280px, .9fr);
        gap: 1.5rem;
        align-items: start;
    }
    .settings-main, .settings-side { display: grid; gap: 1.5rem; min-width: 0; align-content: start; }

    .s-card {
        background: var(--card);
        border: 1px solid rgba(208, 170, 112, .24);
        border-radius: 16px;
        box-shadow: 0 18px 50px -35px rgba(0, 0, 0, .8);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        min-width: 0;
    }
    .s-card-body { padding: 1.5rem; }

    .s-heading {
        margin: 0 0 1.1rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: .01em;
        color: var(--cream);
    }
    .s-sub { margin: -.7rem 0 1.2rem; color: var(--muted); font-size: .88rem; }
    .s-text { margin: 0 0 1rem; color: #c1cbca; font-size: .92rem; line-height: 1.7; }

    /* ---------- Summary card ---------- */
    .summary-top {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: .75rem 1rem;
    }
    .summary-name { display: flex; align-items: center; flex-wrap: wrap; gap: .75rem; }
    .summary-name h2 {
        margin: 0;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.9rem;
        font-weight: 700;
        color: var(--cream);
    }
    .pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .3rem .8rem;
        border-radius: 999px;
        background: rgba(176, 141, 87, .22);
        border: 1px solid rgba(215, 180, 119, .4);
        color: var(--gold);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .summary-tagline {
        color: var(--gold);
        font-family: 'Cormorant Garamond', serif;
        font-size: .95rem;
        font-weight: 600;
        letter-spacing: .16em;
        text-transform: uppercase;
    }
    .summary-address {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin: .85rem 0 0;
        color: var(--muted);
        font-size: .9rem;
    }
    .summary-address i { color: var(--gold); }
    .summary-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .75rem;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--line);
    }
    .stat { display: flex; align-items: center; gap: .65rem; min-width: 0; }
    .stat-icon {
        flex: none;
        display: grid;
        place-items: center;
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(208, 170, 112, .2);
        color: var(--gold);
        font-size: 1rem;
    }
    .stat-text { min-width: 0; }
    .stat-text small { display: block; color: var(--muted); font-size: .68rem; text-transform: uppercase; letter-spacing: .08em; }
    .stat-text strong {
        display: block;
        overflow: hidden;
        color: var(--cream);
        font-size: .85rem;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ---------- Form fields ---------- */
    .s-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.1rem; }
    .s-field { min-width: 0; margin-bottom: 1rem; }
    .s-grid-2 .s-field { margin-bottom: 0; }
    .s-grid-2 + .s-field, .s-grid-2 + .s-grid-2 { margin-top: 1rem; }
    .s-label {
        display: block;
        margin-bottom: .4rem;
        color: var(--cream);
        font-size: .82rem;
        font-weight: 600;
        letter-spacing: .02em;
    }
    .s-input-wrap { position: relative; }
    .s-input-wrap > i {
        position: absolute;
        top: 50%;
        left: .9rem;
        transform: translateY(-50%);
        color: var(--gold-deep);
        font-size: .95rem;
        pointer-events: none;
    }
    .s-input {
        width: 100%;
        padding: .72rem .95rem .72rem 2.5rem;
        border: 1px solid rgba(224, 232, 236, .2);
        border-radius: 10px;
        background: rgba(255, 255, 255, .06);
        color: #fff;
        font-size: .92rem;
        font-family: inherit;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        color-scheme: dark;
    }
    .s-input::placeholder { color: #7f8c8e; }
    .s-input:hover { border-color: rgba(215, 180, 119, .45); }
    .s-input:focus {
        outline: none;
        border-color: var(--gold);
        background: rgba(255, 255, 255, .09);
        box-shadow: 0 0 0 .2rem rgba(215, 180, 119, .18);
    }
    .s-input.is-invalid { border-color: #e5736b; }
    .s-error { margin-top: .3rem; color: #ff9d96; font-size: .78rem; }
    .s-hint { display: block; margin-top: .35rem; color: var(--muted); font-size: .76rem; }

    /* ---------- Overview highlights ---------- */
    .highlight-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .75rem;
        margin-top: 1.1rem;
    }
    .highlight {
        padding: 1rem;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: rgba(255, 255, 255, .04);
        text-align: center;
    }
    .highlight i { display: block; margin-bottom: .45rem; color: var(--gold); font-size: 1.35rem; }
    .highlight strong {
        display: block;
        color: var(--cream);
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.1;
    }
    .highlight span { display: block; margin-top: .2rem; color: var(--muted); font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; }

    /* ---------- Amenities ---------- */
    .amenity-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .85rem 1rem;
    }
    .amenity { display: flex; align-items: center; gap: .7rem; min-width: 0; }
    .amenity .stat-icon { border-radius: 10px; }
    .amenity span:last-child {
        overflow: hidden;
        color: var(--cream);
        font-size: .88rem;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ---------- Booking rules ---------- */
    .rules-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .rules-col h3 {
        display: flex;
        align-items: center;
        gap: .55rem;
        margin: 0 0 .85rem;
        color: var(--cream);
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 700;
    }
    .rules-col h3 small {
        padding: .15rem .6rem;
        border-radius: 999px;
        background: rgba(215, 180, 119, .18);
        color: var(--gold);
        font-family: 'Figtree', sans-serif;
        font-size: .72rem;
        font-weight: 700;
    }
    .rules-list { margin: 0; padding: 0; list-style: none; display: grid; gap: .6rem; }
    .rules-list li {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        color: #c1cbca;
        font-size: .86rem;
        line-height: 1.5;
    }
    .rules-list li i { margin-top: .12rem; color: #e39a55; font-size: 1rem; }

    /* ---------- Sidebar ---------- */
    .side-title {
        margin: 0 0 1.1rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--cream);
    }
    .side-note {
        display: flex;
        gap: .6rem;
        margin: 1.1rem 0 1.25rem;
        padding: .8rem .9rem;
        border-radius: 10px;
        background: rgba(74, 150, 245, .1);
        border: 1px solid rgba(74, 150, 245, .22);
        color: #c1cbca;
        font-size: .78rem;
        line-height: 1.5;
    }
    .side-note i { color: #7db3f7; margin-top: .1rem; }
    .btn-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .55rem;
        width: 100%;
        padding: .85rem 1rem;
        border: 1px solid var(--gold-deep);
        border-radius: 999px;
        background: linear-gradient(135deg, #c9a367, #a8824c);
        color: #0f2429;
        font-family: inherit;
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: .03em;
        cursor: pointer;
        transition: transform .2s var(--ease), box-shadow .2s ease, filter .2s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        filter: brightness(1.07);
        box-shadow: 0 12px 26px -12px rgba(215, 180, 119, .7);
    }
    .btn-save:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }
    .btn-save .spinner {
        display: none;
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(15, 36, 41, .3);
        border-top-color: #0f2429;
        border-radius: 50%;
        animation: settingsSpin .7s linear infinite;
    }
    .btn-save.is-loading { pointer-events: none; opacity: .9; }
    .btn-save.is-loading .spinner { display: inline-block; }
    .btn-save.is-loading .bi { display: none; }
    @keyframes settingsSpin { to { transform: rotate(360deg); } }
    .btn-back {
        display: block;
        margin-top: .8rem;
        text-align: center;
        color: var(--muted);
        font-size: .82rem;
        text-decoration: none;
    }
    .btn-back:hover { color: var(--gold); }

    .contact-list { display: grid; gap: .85rem; margin: 0; padding: 0; list-style: none; }
    .contact-list li { display: flex; align-items: center; gap: .7rem; min-width: 0; }
    .contact-list .stat-text strong { white-space: normal; word-break: break-word; }
    .status-dot {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        margin-top: 1rem;
        padding: .35rem .8rem;
        border-radius: 999px;
        background: rgba(27, 158, 85, .16);
        border: 1px solid rgba(27, 158, 85, .45);
        color: #8fe0b2;
        font-size: .74rem;
        font-weight: 700;
    }
    .status-dot::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #3ddc84;
        box-shadow: 0 0 0 3px rgba(61, 220, 132, .2);
    }

    .info-table { width: 100%; margin: 0; border-collapse: collapse; }
    .info-table tr + tr td { border-top: 1px solid var(--line); }
    .info-table td { padding: .6rem 0; font-size: .82rem; }
    .info-table td:first-child { color: var(--muted); }
    .info-table td:last-child { color: var(--cream); font-weight: 600; text-align: right; }

    .s-alert {
        display: flex;
        align-items: center;
        gap: .6rem;
        margin-bottom: 1.25rem;
        padding: .8rem 1rem;
        border-radius: 10px;
        background: rgba(27, 158, 85, .14);
        border: 1px solid rgba(27, 158, 85, .4);
        color: #b8f0cf;
        font-size: .88rem;
    }

    /* ---------- Location ---------- */
    .loc-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: 1.1rem;
    }
    .loc-head .s-heading { margin: 0; }
    .loc-badge {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .38rem .85rem;
        border-radius: 999px;
        background: rgba(27, 158, 85, .16);
        border: 1px solid rgba(27, 158, 85, .45);
        color: #8fe0b2;
        font-size: .76rem;
        font-weight: 700;
    }
    .loc-map {
        position: relative;
        display: block;
        overflow: hidden;
        border: 1px solid rgba(215, 180, 119, .35);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 14px 34px -18px rgba(0, 0, 0, .8);
    }
    .loc-map img { display: block; width: 100%; height: auto; transition: transform .6s var(--ease); }
    .loc-map:hover img { transform: scale(1.025); }
    .loc-map-open {
        position: absolute;
        right: .8rem;
        bottom: .8rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .8rem;
        border-radius: 999px;
        background: rgba(8, 14, 16, .85);
        color: var(--cream);
        font-size: .74rem;
        font-weight: 600;
        opacity: 0;
        transform: translateY(6px);
        transition: opacity .25s ease, transform .25s ease;
    }
    .loc-map:hover .loc-map-open,
    .loc-map:focus-visible .loc-map-open { opacity: 1; transform: translateY(0); }

    .loc-map-svg { display: block; width: 100%; height: auto; border-radius: 12px; transition: transform .6s var(--ease); }
    .loc-map:hover .loc-map-svg { transform: scale(1.025); }

    .loc-caption { font-size: .78rem; color: var(--muted); text-align: center; margin-top: .5rem; }

    .loc-info { margin-top: 1.25rem; }
    .loc-name {
        margin: 0 0 .15rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--cream);
    }
    .loc-sub { margin: 0 0 1.1rem; color: var(--muted); font-size: .88rem; }
    .loc-meta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .7rem; }
    .loc-item {
        display: flex;
        align-items: center;
        gap: .7rem;
        padding: .7rem .8rem;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: rgba(255, 255, 255, .04);
    }
    .loc-item .stat-icon { width: 34px; height: 34px; }
    .loc-item small { display: block; color: var(--muted); font-size: .68rem; text-transform: uppercase; letter-spacing: .08em; }
    .loc-item strong { display: block; color: var(--cream); font-size: .85rem; font-weight: 600; }
    .loc-actions { display: flex; flex-wrap: wrap; gap: .7rem; margin-top: 1.1rem; }
    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1.1rem;
        border: 1px solid rgba(215, 180, 119, .5);
        border-radius: 999px;
        background: transparent;
        color: var(--gold);
        font-size: .84rem;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s ease, color .2s ease, transform .2s ease;
    }
    .btn-ghost:hover { background: var(--gold-deep); color: #0f2429; transform: translateY(-1px); }

    /* ---------- Responsive ---------- */
    @media (max-width: 991.98px) {
        .settings-layout { grid-template-columns: minmax(0, 1fr); }
        .summary-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .amenity-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 575.98px) {
        .s-card-body { padding: 1.15rem; }
        .s-grid-2, .loc-meta, .rules-grid, .highlight-row, .amenity-grid { grid-template-columns: 1fr; }
        .s-grid-2 .s-field { margin-bottom: 0; }
        .settings-banner { min-height: 150px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .fade-up { opacity: 1; animation: none; }
        .settings-page *, .settings-page *::before, .settings-page *::after { transition-duration: .01ms !important; }
    }
</style>

<div class="settings-page">

    {{-- Banner --}}
    <section class="settings-banner fade-up" style="--i:0">
        <div>
            <h1>Settings</h1>
            <div class="settings-crumbs">
                <a href="{{ route('dashboard') }}">Home</a>
                <span class="sep"></span>
                <span class="current">Settings</span>
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('settings.update') }}" id="settingsForm">
        @csrf

        @if (session('status'))
            <div class="s-alert fade-up" style="--i:1" role="status">
                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
            </div>
        @endif

        <div class="settings-layout">

            {{-- ============ Main column ============ --}}
            <div class="settings-main">

                {{-- Summary --}}
                <section class="s-card fade-up" style="--i:1" aria-label="Resort summary">
                    <div class="s-card-body">
                        <div class="summary-top">
                            <div class="summary-name">
                                <h2>{{ $resortName }}</h2>
                                <span class="pill"><i class="bi bi-stars"></i> Resort</span>
                            </div>
                            <span class="summary-tagline">{{ $resortTagline }}</span>
                        </div>

                        <p class="summary-address">
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $addressValue ?: 'No address added yet' }}
                        </p>

                        <div class="summary-stats">
                            <div class="stat">
                                <span class="stat-icon"><i class="bi bi-telephone"></i></span>
                                <span class="stat-text"><small>Phone</small><strong>{{ ($settings['phone'] ?? '') ?: '-' }}</strong></span>
                            </div>
                            <div class="stat">
                                <span class="stat-icon"><i class="bi bi-envelope"></i></span>
                                <span class="stat-text"><small>Email</small><strong>{{ $emailValue ?: '-' }}</strong></span>
                            </div>
                            <div class="stat">
                                <span class="stat-icon"><i class="bi bi-box-arrow-in-right"></i></span>
                                <span class="stat-text"><small>Check-in</small><strong>{{ $checkInLabel ?: '-' }}</strong></span>
                            </div>
                            <div class="stat">
                                <span class="stat-icon"><i class="bi bi-box-arrow-right"></i></span>
                                <span class="stat-text"><small>Check-out</small><strong>{{ $checkOutLabel ?: '-' }}</strong></span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Resort details (form) --}}
                <section class="s-card fade-up" style="--i:2" aria-labelledby="details-heading">
                    <div class="s-card-body">
                        <h2 class="s-heading" id="details-heading">Resort Details</h2>
                        <p class="s-sub">The name, tagline and contact details shown across the system.</p>

                        <div class="s-grid-2">
                            <div class="s-field">
                                <label class="s-label" for="site_name">Site Name</label>
                                <div class="s-input-wrap">
                                    <i class="bi bi-building"></i>
                                    <input id="site_name" type="text" name="site_name" class="s-input @error('site_name') is-invalid @enderror"
                                           value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="Ocean Resort">
                                </div>
                                @error('site_name') <div class="s-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="s-field">
                                <label class="s-label" for="tagline">Tagline</label>
                                <div class="s-input-wrap">
                                    <i class="bi bi-chat-quote"></i>
                                    <input id="tagline" type="text" name="tagline" class="s-input @error('tagline') is-invalid @enderror"
                                           value="{{ old('tagline', $settings['tagline'] ?? '') }}" placeholder="Stay Better, Feel Home">
                                </div>
                                @error('tagline') <div class="s-error">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="s-grid-2">
                            <div class="s-field">
                                <label class="s-label" for="email">Email Address</label>
                                <div class="s-input-wrap">
                                    <i class="bi bi-envelope"></i>
                                    <input id="email" type="email" name="email" class="s-input @error('email') is-invalid @enderror"
                                           value="{{ $emailValue }}" placeholder="setec@gmail.com">
                                </div>
                                @error('email') <div class="s-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="s-field">
                                <label class="s-label" for="phone">Phone Number</label>
                                <div class="s-input-wrap">
                                    <i class="bi bi-telephone"></i>
                                    <input id="phone" type="tel" name="phone" class="s-input @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="(001) 390-0000">
                                </div>
                                @error('phone') <div class="s-error">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="s-field">
                            <label class="s-label" for="address">Address</label>
                            <div class="s-input-wrap">
                                <i class="bi bi-geo-alt"></i>
                                <input id="address" type="text" name="address" class="s-input @error('address') is-invalid @enderror"
                                       value="{{ $addressValue }}" placeholder="No. 86A, Street 110, Russian Federation Blvd (110), Phnom Penh, Cambodia">
                            </div>
                            @error('address') <div class="s-error">{{ $message }}</div> @enderror
                            <span class="s-hint">Appears on invoices and the location section below.</span>
                        </div>
                    </div>
                </section>

                {{-- Overview --}}
                <section class="s-card fade-up" style="--i:3" aria-labelledby="overview-heading">
                    <div class="s-card-body">
                        <h2 class="s-heading" id="overview-heading">Overview</h2>
                        <p class="s-text">
                            {{ $resortName }} is a resort built around comfort and calm. Guests are welcomed at a front desk that is open around the clock,
                            and every stay is supported by attentive housekeeping and simple, transparent booking.
                            This page controls the details that appear on your invoices, guest communications and the dashboard.
                        </p>
                        <p class="s-text" style="margin-bottom:0">
                            Keep your contact information, check-in and check-out times and location up to date so your team and your guests always see the same, correct details.
                        </p>

                        <div class="highlight-row">
                            <div class="highlight">
                                <i class="bi bi-clock"></i>
                                <strong>24 / 7</strong>
                                <span>Front desk</span>
                            </div>
                            <div class="highlight">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <strong>{{ $checkInLabel ?: '-' }}</strong>
                                <span>Check-in from</span>
                            </div>
                            <div class="highlight">
                                <i class="bi bi-box-arrow-right"></i>
                                <strong>{{ $checkOutLabel ?: '-' }}</strong>
                                <span>Check-out by</span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Amenities --}}
                <section class="s-card fade-up" style="--i:4" aria-labelledby="amenities-heading">
                    <div class="s-card-body">
                        <h2 class="s-heading" id="amenities-heading">Resort Amenities</h2>
                        <p class="s-sub">Facilities and services available to guests during their stay.</p>
                        <div class="amenity-grid">
                            @foreach ($amenities as [$icon, $label])
                                <div class="amenity">
                                    <span class="stat-icon"><i class="bi {{ $icon }}"></i></span>
                                    <span>{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- Booking rules --}}
                <section class="s-card fade-up" style="--i:5" aria-labelledby="rules-heading">
                    <div class="s-card-body">
                        <h2 class="s-heading" id="rules-heading">Booking Rules</h2>
                        <div class="rules-grid">
                            <div class="rules-col">
                                <h3>Check In <small>{{ $checkInLabel ?: 'Not set' }}</small></h3>
                                <ul class="rules-list">
                                    <li><i class="bi bi-check-circle-fill"></i> Check-in starts from {{ $checkInLabel ?: 'the time set in Stay Times' }}.</li>
                                    <li><i class="bi bi-check-circle-fill"></i> A valid photo ID or passport is required for every guest.</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Early arrival is subject to room availability.</li>
                                </ul>
                            </div>
                            <div class="rules-col">
                                <h3>Check Out <small>{{ $checkOutLabel ?: 'Not set' }}</small></h3>
                                <ul class="rules-list">
                                    <li><i class="bi bi-check-circle-fill"></i> Check-out is by {{ $checkOutLabel ?: 'the time set in Stay Times' }}.</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Please return room keys to the front desk.</li>
                                    <li><i class="bi bi-check-circle-fill"></i> Late check-out can be requested and may carry a charge.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Location --}}
                <section class="s-card fade-up" style="--i:6" aria-labelledby="location-heading">
                    <div class="s-card-body">
                        <div class="loc-head">
                            <h2 class="s-heading" id="location-heading">Location</h2>
                            <span class="loc-badge"><i class="bi bi-geo-alt-fill"></i> Setec Institute Location</span>
                        </div>

                        <a class="loc-map" href="{{ asset('images/setec-map.jpg') }}" target="_blank" rel="noopener" aria-label="Open the Setec Institute map in full size">
                            <svg class="loc-map-svg" width="100%" height="100%" viewBox="0 0 700 360" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Setec Institute location map">
                                <defs>
                                    <linearGradient id="mapBg" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" stop-color="#ffffff" stop-opacity="1"/>
                                        <stop offset="100%" stop-color="#f5f7f8" stop-opacity="1"/>
                                    </linearGradient>
                                    <filter id="pinShadow" x="-40%" y="-40%" width="180%" height="180%">
                                        <feGaussianBlur in="SourceAlpha" stdDeviation="5"/>
                                        <feOffset dx="0" dy="5" result="offsetblur"/>
                                        <feComponentTransfer><feFuncA type="linear" slope="0.35"/></feComponentTransfer>
                                        <feMerge><feMergeNode/><feMergeNode in="SourceGraphic"/></feMerge>
                                    </filter>
                                </defs>
                                <rect width="700" height="360" fill="url(#mapBg)"/>
                                <!-- Road grid -->
                                <g stroke="#1a1a1a" stroke-width="13" stroke-linecap="round" opacity=".95">
                                    <line x1="0" y1="138" x2="700" y2="138"/>
                                    <line x1="185" y1="0" x2="185" y2="360"/>
                                    <line x1="490" y1="0" x2="490" y2="360"/>
                                </g>
                                <g stroke="#b9c4c8" stroke-width="7" stroke-linecap="round" opacity=".72">
                                    <line x1="92" y1="0" x2="92" y2="360"/>
                                    <line x1="340" y1="0" x2="340" y2="360"/>
                                    <line x1="610" y1="0" x2="610" y2="360"/>
                                    <line x1="0" y1="78" x2="700" y2="78"/>
                                    <line x1="0" y1="278" x2="700" y2="278"/>
                                </g>
                                <!-- Distance marker -->
                                <g font-family="Figtree, sans-serif" font-size="14" font-weight="800" fill="#0a242a">
                                    <text x="325" y="130" text-anchor="middle">600m</text>
                                </g>
                                <!-- Landmark labels -->
                                <g font-family="Figtree, sans-serif" font-size="13" font-weight="700" fill="#0a242a" opacity=".9">
                                    <text x="50" y="158">RUPP</text>
                                    <text x="100" y="138">IFL</text>
                                </g>
                                <!-- Red hospital cross -->
                                <g filter="url(#pinShadow)">
                                    <rect x="485" y="133" width="14" height="34" fill="#d32f2f" rx="3"/>
                                    <rect x="468" y="150" width="48" height="14" fill="#d32f2f" rx="3"/>
                                </g>
                                <!-- Green SETec shield map pin (image 1 style) -->
                                <g filter="url(#pinShadow)">
                                    <path d="M152 2 C132 2 116 15 116 34 C116 52 138 70 152 86 C166 70 188 52 188 34 C188 15 172 2 152 2 Z" fill="#1b9e55"/>
                                    <path d="M152 10 C140 10 130 18 130 30 C130 40 142 50 152 58 C162 50 174 40 174 30 C174 18 164 10 152 10 Z" fill="#ffffff"/>
                                    <path d="M144 26 C140 26 136 30 136 34 C136 38 140 42 144 42 C148 42 152 38 152 34 C152 30 148 26 144 26 Z" fill="#1b9e55"/>
                                    <rect x="148" y="50" width="8" height="7" rx="2" fill="#ffffff"/>
                                    <text x="152" y="53" font-family="Figtree, sans-serif" font-size="11" font-weight="800" fill="#1b9e55" text-anchor="middle">SET</text>
                                </g>
                                <!-- Cambodian-style labels -->
                                <g font-family="Figtree, sans-serif" font-size="12" font-weight="600" fill="#1b9e55" opacity=".9">
                                    <text x="152" y="212" text-anchor="middle">Setec Institute</text>
                                </g>
                                <text x="50%" y="62%" font-family="Figtree, sans-serif" font-size="18" font-weight="700" fill="#1b9e55" text-anchor="middle" dy=".35em" opacity=".4">Setec Institute</text>
                                <text x="50%" y="68%" font-family="Figtree, sans-serif" font-size="11" font-weight="600" fill="#6b7b7f" text-anchor="middle">Pin location · Setec Institute</text>
                            </svg>
                            <span class="loc-map-open"><i class="bi bi-arrows-fullscreen"></i> View full map</span>
                            <p class="loc-caption mt-1 reveal" style="--i:1">Map showing the Setec Institute location on Russian Federation Blvd (110), near RUPP and IFL</p>
                        </a>

                        <div class="loc-info">
                            <h3 class="loc-name">Setec Institute</h3>
                            <p class="loc-sub">{{ $addressValue }}</p>

                            <div class="loc-meta">
                                <div class="loc-item">
                                    <span class="stat-icon"><i class="bi bi-signpost-split"></i></span>
                                    <span><small>Main road</small><strong>Blvd (110)</strong></span>
                                </div>
                                <div class="loc-item">
                                    <span class="stat-icon"><i class="bi bi-signpost-2"></i></span>
                                    <span><small>Side street</small><strong>Street 259</strong></span>
                                </div>
                                <div class="loc-item">
                                    <span class="stat-icon"><i class="bi bi-mortarboard"></i></span>
                                    <span><small>Nearby</small><strong>RUPP · IFL</strong></span>
                                </div>
                                <div class="loc-item">
                                    <span class="stat-icon"><i class="bi bi-hospital"></i></span>
                                    <span><small>Nearby</small><strong>Pediatric Hospital</strong></span>
                                </div>
                            </div>

                            <div class="loc-actions">
                                <a class="btn-ghost" href="{{ $mapsUrl }}" target="_blank" rel="noopener">
                                    <i class="bi bi-map"></i> Open in Google Maps
                                </a>
                                <a class="btn-ghost" href="{{ $mapsUrl }}" target="_blank" rel="noopener">
                                    <i class="bi bi-image"></i> View full map
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- ============ Sidebar ============ --}}
            <aside class="settings-side">

                {{-- Stay times (form) --}}
                <div class="s-card fade-up" style="--i:2">
                    <div class="s-card-body">
                        <h2 class="side-title">Stay Times</h2>

                        <div class="s-field">
                            <label class="s-label" for="check_in">Check-in Time</label>
                            <div class="s-input-wrap">
                                <i class="bi bi-clock"></i>
                                <input id="check_in" type="time" name="check_in" class="s-input @error('check_in') is-invalid @enderror"
                                       value="{{ old('check_in', $settings['check_in'] ?? '') }}">
                            </div>
                            @error('check_in') <div class="s-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="s-field">
                            <label class="s-label" for="check_out">Check-out Time</label>
                            <div class="s-input-wrap">
                                <i class="bi bi-clock-history"></i>
                                <input id="check_out" type="time" name="check_out" class="s-input @error('check_out') is-invalid @enderror"
                                       value="{{ old('check_out', $settings['check_out'] ?? '') }}">
                            </div>
                            @error('check_out') <div class="s-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="side-note">
                            <i class="bi bi-info-circle"></i>
                            <span>These times are used for new bookings and printed on guest invoices.</span>
                        </div>

                        <button type="submit" class="btn-save" id="saveBtn">
                            <span class="spinner" aria-hidden="true"></span>
                            <i class="bi bi-check-lg"></i>
                            <span class="label">Save Changes</span>
                        </button>
                        <a class="btn-back" href="{{ route('dashboard') }}">Back to Dashboard</a>
                    </div>
                </div>

                {{-- Front desk --}}
                <div class="s-card fade-up" style="--i:3">
                    <div class="s-card-body">
                        <h2 class="side-title">Front Desk</h2>
                        <ul class="contact-list">
                            <li>
                                <span class="stat-icon"><i class="bi bi-telephone"></i></span>
                                <span class="stat-text"><small>Phone</small><strong>{{ ($settings['phone'] ?? '') ?: '-' }}</strong></span>
                            </li>
                            <li>
                                <span class="stat-icon"><i class="bi bi-envelope"></i></span>
                                <span class="stat-text"><small>Email</small><strong>{{ $emailValue ?: '-' }}</strong></span>
                            </li>
                            <li>
                                <span class="stat-icon"><i class="bi bi-geo-alt"></i></span>
                                <span class="stat-text"><small>Address</small><strong>{{ $addressValue ?: '-' }}</strong></span>
                            </li>
                            <li>
                                <span class="stat-icon"><i class="bi bi-clock"></i></span>
                                <span class="stat-text"><small>Hours</small><strong>Open 24 hours, every day</strong></span>
                            </li>
                        </ul>
                        <span class="status-dot">Front desk open</span>
                    </div>
                </div>

                {{-- System information --}}
                <div class="s-card fade-up" style="--i:4">
                    <div class="s-card-body">
                        <h2 class="side-title">System Information</h2>
                        <table class="info-table">
                            <tr><td>Application</td><td>{{ $resortName }}</td></tr>
                            <tr><td>Framework</td><td>Laravel {{ app()->version() }}</td></tr>
                            <tr><td>PHP version</td><td>{{ PHP_VERSION }}</td></tr>
                            <tr><td>Timezone</td><td>{{ config('app.timezone') }}</td></tr>
                            <tr><td>Environment</td><td>{{ ucfirst(app()->environment()) }}</td></tr>
                            <tr><td>Server time</td><td>{{ now()->format('M d, Y · g:i A') }}</td></tr>
                        </table>
                    </div>
                </div>

            </aside>

        </div>
    </form>
</div>

<script>
    (function () {
        var form = document.getElementById('settingsForm');
        var btn = document.getElementById('saveBtn');
        if (!form || !btn) return;
        form.addEventListener('submit', function () {
            btn.classList.add('is-loading');
            btn.querySelector('.label').textContent = 'Saving...';
        });
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                btn.classList.remove('is-loading');
                btn.querySelector('.label').textContent = 'Save Changes';
            }
        });
    })();
</script>
@endsection