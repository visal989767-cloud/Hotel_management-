@extends('layouts.app')
@section('title', $booking->exists ? 'Edit Booking' : 'New Booking')
@section('content')

<style>
    .container:has(.booking-form-page) {
        max-width: 1300px !important;
        padding-left: clamp(1rem, 3vw, 2.5rem) !important;
        padding-right: clamp(1rem, 3vw, 2.5rem) !important;
    }
    .booking-form-page {
        --bf-gold: #c59b58;
        --bf-gold-hover: #b08542;
        --bf-dark: #0e1416;
        --bf-card-bg: rgba(14, 20, 22, 0.97);
        --bf-input-bg: rgba(255, 255, 255, 0.05);
        --bf-line: rgba(255, 255, 255, 0.08);
        --bf-muted: #8c989c;
    }

    /* Hero Header */
    .bf-hero {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        min-height: 300px;
        border-radius: 20px 20px 0 0;
        margin-bottom: 0;
        padding: 2rem clamp(1.25rem, 5vw, 4rem);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    .bf-hero .hero-slides { position: absolute; inset: 0; z-index: -2; }
    .bf-hero .hero-slide {
        position: absolute; inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity .9s ease;
    }
    .bf-hero .hero-slide:nth-child(1) { background-image: url('https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1600&q=85'); opacity: 1; }
    .bf-hero .hero-slide:nth-child(2) { background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1600&q=85'); }
    .bf-hero .hero-slide:nth-child(3) { background-image: url('https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1600&q=85'); }
    .bf-hero::after {
        content: "";
        position: absolute; inset: 0; z-index: -1;
        background:
            linear-gradient(180deg, rgba(8,14,16,.48) 0%, rgba(8,14,16,.35) 40%, rgba(8,14,16,.82) 100%),
            linear-gradient(90deg, rgba(8,14,16,.65) 0%, transparent 70%);
    }
    .bf-hero-top {
        position: relative; z-index: 2;
        display: flex; justify-content: space-between; align-items: flex-start; gap: 1.5rem;
    }
    .bf-hero-kicker {
        font-size: .78rem; letter-spacing: .22em; text-transform: uppercase;
        color: var(--bf-gold); font-weight: 600; margin-bottom: .4rem;
    }
    .bf-hero-rule { width: 50px; height: 2px; background: var(--bf-gold); margin-bottom: .9rem; }
    .bf-hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2.8rem, 6vw, 4.5rem);
        color: #fff; line-height: .95; letter-spacing: -.02em; margin-bottom: .5rem;
    }
    .bf-hero-text { color: rgba(255,255,255,.88); font-size: .92rem; max-width: 50ch; }
    .bf-hero-btn {
        display: inline-flex; align-items: center; gap: .4rem;
        border: 1px solid rgba(255,255,255,.35); background: rgba(8,14,16,.45);
        color: #fff; padding: .55rem 1.4rem; border-radius: 30px; font-size: .84rem;
        font-weight: 500; text-decoration: none; backdrop-filter: blur(6px);
        transition: all .25s ease; white-space: nowrap;
    }
    .bf-hero-btn:hover { border-color: var(--bf-gold); background: var(--bf-gold); color: #0f2429; transform: translateY(-1px); }
    .bf-hero-note {
        position: absolute; right: clamp(1.25rem, 5vw, 4rem); bottom: 1.1rem; z-index: 2;
        display: inline-flex; align-items: center; gap: .5rem;
        color: #f3e7d2; font-family: 'Cormorant Garamond', serif; font-size: 1rem;
        text-shadow: 0 2px 10px rgba(0,0,0,.55);
    }

    /* Principles Strip */
    .bf-principles {
        display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .65rem;
        padding: 1.1rem clamp(1rem, 3vw, 2rem);
        background: rgba(8,14,16,.95);
        border: 1px solid var(--bf-line); border-top: 0;
    }
    .bf-principle {
        display: grid; grid-template-columns: 3rem 1fr; align-items: center; gap: .65rem;
        padding: .3rem .4rem; border-radius: 8px; transition: background .2s ease;
    }
    .bf-principle:hover { background: rgba(255,255,255,.03); }
    .bf-principle-thumb {
        position: relative; width: 3rem; height: 3rem; border-radius: 50%;
        border: 1.5px solid rgba(197,155,88,.55); overflow: hidden; flex-shrink: 0;
        transition: transform .3s ease;
    }
    .bf-principle:hover .bf-principle-thumb { transform: scale(1.06); }
    .bf-principle-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .bf-principle-badge {
        position: absolute; right: 0; bottom: 0;
        width: 1.1rem; height: 1.1rem; border-radius: 50%;
        background: rgba(8,14,16,.92); border: 1px solid rgba(197,155,88,.75);
        color: var(--bf-gold); font-size: .58rem;
        display: grid; place-items: center; box-shadow: 0 2px 6px rgba(0,0,0,.55);
    }
    .bf-principle strong { display: block; color: #f3e7d2; font-family: 'Cormorant Garamond', serif; font-size: 1rem; font-weight: 600; }
    .bf-principle span { display: block; color: var(--bf-muted); font-size: .62rem; margin-top: .1rem; }

    /* Main layout */
    .bf-layout {
        background: rgba(8,14,16,.95);
        border: 1px solid var(--bf-line); border-top: 0;
        border-radius: 0 0 20px 20px;
        padding: clamp(1.25rem, 3vw, 2.5rem);
    }

    /* Form Card */
    .bf-form-card {
        background: var(--bf-card-bg);
        border: 1px solid var(--bf-line);
        border-radius: 18px;
        padding: clamp(1.25rem, 2.5vw, 2.25rem);
        box-shadow: 0 20px 50px rgba(0,0,0,.65);
    }
    .bf-form-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.9rem; color: #fff; font-weight: 600; margin-bottom: .2rem;
    }
    .bf-form-subtitle { color: var(--bf-muted); font-size: .84rem; margin-bottom: 1.75rem; }
    .bf-label {
        font-size: .82rem; font-weight: 600; color: #e0e8ec;
        letter-spacing: .02em; margin-bottom: .4rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .bf-label i { color: var(--bf-gold); font-size: .82rem; }
    .bf-input, .bf-select {
        min-height: 46px; border-radius: 8px !important;
        background-color: var(--bf-input-bg) !important;
        border: 1px solid rgba(255,255,255,.12) !important;
        color: #ffffff !important; font-size: .88rem; padding: .6rem 1rem;
        transition: border-color .2s ease, box-shadow .2s ease;
        width: 100%;
    }
    .bf-input:focus, .bf-select:focus {
        background-color: rgba(255,255,255,.08) !important;
        border-color: var(--bf-gold) !important;
        box-shadow: 0 0 0 .2rem rgba(197,155,88,.22) !important;
        color: #fff !important; outline: none;
    }
    .bf-input::placeholder { color: #6c7c80; }
    .bf-select option { background-color: #111618; color: #fff; }
    .bf-textarea {
        min-height: 90px; resize: vertical;
    }
    .bf-btn-save {
        background: var(--bf-gold); border: 1px solid var(--bf-gold);
        color: #0f2429; font-weight: 600; font-size: .88rem; border-radius: 30px;
        padding: .68rem 2rem; display: inline-flex; align-items: center; gap: .5rem;
        transition: all .25s ease;
    }
    .bf-btn-save:hover { background: var(--bf-gold-hover); border-color: var(--bf-gold-hover); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(197,155,88,.4); }
    .bf-btn-cancel {
        background: transparent; border: 1px solid rgba(255,255,255,.22); color: #e0e8ec;
        font-size: .88rem; font-weight: 500; border-radius: 30px;
        padding: .68rem 1.6rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: .5rem;
        transition: all .2s ease;
    }
    .bf-btn-cancel:hover { background: rgba(255,255,255,.08); color: #fff; }

    /* Live Price Preview Card */
    .bf-preview-sticky { position: sticky; top: 1.5rem; }
    .bf-preview-card {
        background: var(--bf-card-bg);
        border: 1px solid var(--bf-line);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,.65);
    }
    .bf-preview-header {
        padding: .9rem 1.3rem;
        background: rgba(255,255,255,.03);
        border-bottom: 1px solid var(--bf-line);
        display: flex; justify-content: space-between; align-items: center;
    }
    .bf-preview-kicker { color: var(--bf-gold); font-size: .74rem; letter-spacing: .15em; text-transform: uppercase; font-weight: 600; }
    .bf-preview-live-badge {
        display: inline-flex; align-items: center; gap: .35rem; font-size: .7rem;
        color: #81c784; background: rgba(46,125,50,.2); padding: 2px 8px;
        border-radius: 12px; border: 1px solid rgba(76,175,80,.3);
    }
    .bf-preview-live-dot { width: 6px; height: 6px; border-radius: 50%; background: #4caf50; box-shadow: 0 0 6px #4caf50; }

    /* Room Image Preview */
    .bf-room-preview-img {
        height: 160px; width: 100%;
        background: #111618;
        background-image: url('https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=75');
        background-size: cover; background-position: center;
        position: relative; overflow: hidden;
        transition: background-image .4s ease;
    }
    .bf-room-preview-img::after {
        content: ""; position: absolute; inset: 0;
        background: linear-gradient(180deg, transparent 50%, rgba(14,20,22,.98) 100%);
    }

    /* Booking Summary Data */
    .bf-summary-body { padding: 1.15rem 1.3rem; }
    .bf-summary-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: .55rem 0; border-bottom: 1px solid rgba(255,255,255,.05);
        font-size: .84rem;
    }
    .bf-summary-row:last-child { border-bottom: 0; }
    .bf-summary-label { color: var(--bf-muted); display: flex; align-items: center; gap: .4rem; }
    .bf-summary-label i { color: var(--bf-gold); font-size: .8rem; }
    .bf-summary-val { color: #fff; font-weight: 600; }
    .bf-price-block {
        margin: 1rem 1.3rem;
        background: rgba(197,155,88,.08);
        border: 1px solid rgba(197,155,88,.22);
        border-radius: 14px; padding: 1rem 1.25rem;
        text-align: center;
    }
    .bf-price-label { font-size: .72rem; color: var(--bf-muted); text-transform: uppercase; letter-spacing: .1em; margin-bottom: .25rem; }
    .bf-price-total { font-family: 'Cormorant Garamond', serif; font-size: 2.4rem; color: var(--bf-gold); font-weight: 700; line-height: 1; }
    .bf-price-note { font-size: .72rem; color: var(--bf-muted); margin-top: .25rem; }

    @media (max-width: 991.98px) {
        .bf-principles { grid-template-columns: repeat(2, 1fr); }
        .bf-preview-sticky { position: static; margin-top: 1.5rem; }
    }
    @media (max-width: 575.98px) {
        .bf-hero { min-height: 0; padding: 1.5rem 1.15rem; }
        .bf-principles { grid-template-columns: 1fr; gap: .5rem; }
        .bf-hero-top { flex-direction: column; }
        .bf-hero-note { display: none; }
        .bf-layout { padding: 1rem .65rem; }
    }
</style>

<div class="booking-form-page">
    <!-- Hero Header -->
    <header class="bf-hero reveal">
        <div class="hero-slides" aria-hidden="true">
            <div class="hero-slide"></div>
            <div class="hero-slide"></div>
            <div class="hero-slide"></div>
        </div>
        <div class="bf-hero-top">
            <div>
                <div class="bf-hero-kicker">Ocean Resort · Reservations</div>
                <div class="bf-hero-rule"></div>
                <h1 class="bf-hero-title">{{ $booking->exists ? 'Edit booking' : 'Create a booking' }}</h1>
                <p class="bf-hero-text">{{ $booking->exists ? 'Update the reservation details, dates, and guest information.' : 'Choose the guest, room, and dates. The total will be calculated automatically.' }}</p>
            </div>
            <a href="{{ route('bookings.index') }}" class="bf-hero-btn">
                <i class="bi bi-arrow-left"></i> Back to bookings
            </a>
        </div>
        <div class="bf-hero-note">
            <i class="bi bi-calendar-heart"></i> Every stay begins with a great reservation
        </div>
    </header>

    <!-- Principles Strip -->
    <section class="bf-principles" aria-label="Reservation standards">
        <div class="bf-principle">
            <div class="bf-principle-thumb">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=200&q=75" alt="Seamless check-in" loading="lazy">
                <span class="bf-principle-badge"><i class="bi bi-check2"></i></span>
            </div>
            <div><strong>Seamless check-in</strong><span>Effortless arrivals every time</span></div>
        </div>
        <div class="bf-principle">
            <div class="bf-principle-thumb">
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=200&q=75" alt="Best room rates" loading="lazy">
                <span class="bf-principle-badge"><i class="bi bi-tag"></i></span>
            </div>
            <div><strong>Best room rates</strong><span>No hidden fees, transparent pricing</span></div>
        </div>
        <div class="bf-principle">
            <div class="bf-principle-thumb">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=200&q=75" alt="Flexible dates" loading="lazy">
                <span class="bf-principle-badge"><i class="bi bi-calendar2-week"></i></span>
            </div>
            <div><strong>Flexible dates</strong><span>Modify or cancel any time</span></div>
        </div>
        <div class="bf-principle">
            <div class="bf-principle-thumb">
                <img src="https://images.unsplash.com/photo-1615874959474-d609969a20ed?auto=format&fit=crop&w=200&q=75" alt="Guest priority" loading="lazy">
                <span class="bf-principle-badge"><i class="bi bi-star"></i></span>
            </div>
            <div><strong>Guest priority</strong><span>Personalised stay every visit</span></div>
        </div>
    </section>

    <!-- Main Layout: Form + Live Preview -->
    <div class="row g-4 align-items-start bf-layout">
        <!-- Left Column: Form -->
        <div class="col-lg-7">
            <div class="bf-form-card reveal" style="--i:1">
                <h4 class="bf-form-title">{{ $booking->exists ? 'Edit Booking' : 'New Booking' }}</h4>
                <p class="bf-form-subtitle">Fill in the details below. The total price is calculated automatically based on the room type and number of nights.</p>

                @if ($errors->any())
                    <div class="alert mb-4" style="background: rgba(239,68,68,.18); border: 1px solid rgba(239,68,68,.35); border-radius: 12px; color: #f87171;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $booking->exists ? route('bookings.update', $booking) : route('bookings.store') }}" id="bookingForm">
                    @csrf
                    @if ($booking->exists) @method('PUT') @endif

                    <!-- Guest & Room Row -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="bf-label"><i class="bi bi-person"></i> Guest</label>
                            <select id="bf_guest" name="guest_id" class="bf-select @error('guest_id') is-invalid @enderror" required>
                                <option value="">-- Select Guest --</option>
                                @foreach ($guests as $g)
                                    <option value="{{ $g->guest_id }}" @selected(old('guest_id', $booking->guest_id) == $g->guest_id)>
                                        {{ $g->guest_name }} ({{ $g->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('guest_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="bf-label"><i class="bi bi-door-closed"></i> Room</label>
                            <select id="bf_room" name="room_id" class="bf-select @error('room_id') is-invalid @enderror" required onchange="updatePreview()">
                                <option value="">-- Select Room --</option>
                                @foreach ($rooms as $r)
                                    <option value="{{ $r->room_id }}"
                                        data-type="{{ $r->roomType->type_name }}"
                                        data-price="{{ $r->roomType->price_per_night }}"
                                        data-status="{{ $r->status }}"
                                        @selected(old('room_id', $booking->room_id) == $r->room_id)>
                                        Room {{ $r->room_number }} – {{ $r->roomType->type_name }} (${{ number_format($r->roomType->price_per_night, 2) }}/night)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Dates & Status Row -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="bf-label"><i class="bi bi-calendar-event"></i> Check-in</label>
                            <input id="bf_checkin" type="date" name="check_in"
                                value="{{ old('check_in', $booking->check_in?->format('Y-m-d')) }}"
                                class="bf-input @error('check_in') is-invalid @enderror" required onchange="updatePreview()">
                            @error('check_in') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="bf-label"><i class="bi bi-calendar2-check"></i> Check-out</label>
                            <input id="bf_checkout" type="date" name="check_out"
                                value="{{ old('check_out', $booking->check_out?->format('Y-m-d')) }}"
                                class="bf-input @error('check_out') is-invalid @enderror" required onchange="updatePreview()">
                            @error('check_out') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="bf-label"><i class="bi bi-tag"></i> Status</label>
                            <select name="status" class="bf-select @error('status') is-invalid @enderror" required>
                                @foreach ($statuses as $s)
                                    <option value="{{ $s }}" @selected(old('status', $booking->status) == $s)>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="bf-label"><i class="bi bi-card-text"></i> Special Requests & Notes</label>
                        <textarea name="notes" rows="3" class="bf-input bf-textarea" placeholder="Airport shuttle, early check-in, extra bed...">{{ old('notes', $booking->notes) }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2 flex-wrap pt-1">
                        <button type="submit" class="bf-btn-save">
                            <i class="bi bi-check2"></i> {{ $booking->exists ? 'Save changes' : 'Save booking' }}
                        </button>
                        <a href="{{ route('bookings.index') }}" class="bf-btn-cancel">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Live Booking Summary Preview -->
        <div class="col-lg-5">
            <div class="bf-preview-sticky reveal" style="--i:2">
                <div class="bf-preview-card">
                    <div class="bf-preview-header">
                        <span class="bf-preview-kicker">Booking Summary</span>
                        <span class="bf-preview-live-badge"><span class="bf-preview-live-dot"></span> Live Preview</span>
                    </div>

                    <!-- Room Photo Banner -->
                    <div class="bf-room-preview-img" id="previewRoomImg"></div>

                    <div class="bf-summary-body">
                        <div class="bf-summary-row">
                            <span class="bf-summary-label"><i class="bi bi-person"></i> Guest</span>
                            <span class="bf-summary-val" id="previewGuest">{{ $booking->exists ? $booking->guest->guest_name : '—' }}</span>
                        </div>
                        <div class="bf-summary-row">
                            <span class="bf-summary-label"><i class="bi bi-door-closed"></i> Room</span>
                            <span class="bf-summary-val" id="previewRoom">{{ $booking->exists ? 'Room '.$booking->room->room_number.' ('.$booking->room->roomType->type_name.')' : '—' }}</span>
                        </div>
                        <div class="bf-summary-row">
                            <span class="bf-summary-label"><i class="bi bi-calendar-event"></i> Check-in</span>
                            <span class="bf-summary-val" id="previewCheckIn">{{ $booking->exists ? $booking->check_in->format('M d, Y') : '—' }}</span>
                        </div>
                        <div class="bf-summary-row">
                            <span class="bf-summary-label"><i class="bi bi-calendar2-check"></i> Check-out</span>
                            <span class="bf-summary-val" id="previewCheckOut">{{ $booking->exists ? $booking->check_out->format('M d, Y') : '—' }}</span>
                        </div>
                        <div class="bf-summary-row">
                            <span class="bf-summary-label"><i class="bi bi-moon-stars"></i> Duration</span>
                            <span class="bf-summary-val" id="previewNights">{{ $booking->exists ? $booking->nights.' Night'.($booking->nights > 1 ? 's' : '') : '—' }}</span>
                        </div>
                        <div class="bf-summary-row">
                            <span class="bf-summary-label"><i class="bi bi-cash"></i> Rate / Night</span>
                            <span class="bf-summary-val" id="previewRate">{{ $booking->exists ? '$'.number_format($booking->room->roomType->price_per_night, 2) : '—' }}</span>
                        </div>
                    </div>

                    <!-- Estimated Total Price Box -->
                    <div class="bf-price-block">
                        <p class="bf-price-label">Estimated Total</p>
                        <div class="bf-price-total" id="previewTotal">
                            {{ $booking->exists ? '$'.number_format($booking->total_price, 2) : '$0.00' }}
                        </div>
                        <p class="bf-price-note">Auto-calculated · nights × room rate</p>
                    </div>

                    <div style="padding: 0 1.3rem 1.3rem;">
                        <div class="bf-summary-row" style="border: 0; padding-top: 0;">
                            <span class="bf-summary-label"><i class="bi bi-info-circle"></i> Note</span>
                            <span style="font-size: .74rem; color: var(--bf-muted); text-align: right;">Final price saved on form submit.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var roomImages = {
        'Single': 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=600&q=75',
        'Double': 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=75',
        'Deluxe': 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=75',
        'Suite': 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=75',
        'Garden Suite': 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=600&q=75',
        'Junior Suite': 'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?auto=format&fit=crop&w=600&q=75',
        'Family Room': 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=600&q=75',
        'Executive Room': 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=600&q=75',
        'Ocean View': 'https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=600&q=75',
        'Penthouse Suite': 'https://images.unsplash.com/photo-1615874959474-d609969a20ed?auto=format&fit=crop&w=600&q=75',
        'Honeymoon Suite': 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=600&q=75',
        'Accessible Room': 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=600&q=75',
    };

    var guestSelect = document.getElementById('bf_guest');
    var roomSelect = document.getElementById('bf_room');
    var checkInInput = document.getElementById('bf_checkin');
    var checkOutInput = document.getElementById('bf_checkout');

    var previewGuest = document.getElementById('previewGuest');
    var previewRoom = document.getElementById('previewRoom');
    var previewCheckIn = document.getElementById('previewCheckIn');
    var previewCheckOut = document.getElementById('previewCheckOut');
    var previewNights = document.getElementById('previewNights');
    var previewRate = document.getElementById('previewRate');
    var previewTotal = document.getElementById('previewTotal');
    var previewRoomImg = document.getElementById('previewRoomImg');

    function formatDate(str) {
        if (!str) return '—';
        var d = new Date(str + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    window.updatePreview = function () {
        // Guest
        var guestOpt = guestSelect ? guestSelect.options[guestSelect.selectedIndex] : null;
        if (previewGuest) previewGuest.textContent = (guestOpt && guestOpt.value) ? guestOpt.text.split(' (')[0] : '—';

        // Room
        var roomOpt = roomSelect ? roomSelect.options[roomSelect.selectedIndex] : null;
        var price = 0;
        var roomType = '';
        if (roomOpt && roomOpt.value) {
            price = parseFloat(roomOpt.getAttribute('data-price') || 0);
            roomType = roomOpt.getAttribute('data-type') || '';
            var roomLabel = roomOpt.text.split(' –')[0];
            if (previewRoom) previewRoom.textContent = roomLabel + (roomType ? ' (' + roomType + ')' : '');
            if (previewRate) previewRate.textContent = price > 0 ? '$' + price.toFixed(2) : '—';

            // Update room image
            if (previewRoomImg) {
                var imgUrl = roomImages[roomType] || 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=600&q=75';
                previewRoomImg.style.backgroundImage = 'url(' + imgUrl + ')';
            }
        } else {
            if (previewRoom) previewRoom.textContent = '—';
            if (previewRate) previewRate.textContent = '—';
        }

        // Dates & nights
        var ci = checkInInput ? checkInInput.value : '';
        var co = checkOutInput ? checkOutInput.value : '';
        if (previewCheckIn) previewCheckIn.textContent = ci ? formatDate(ci) : '—';
        if (previewCheckOut) previewCheckOut.textContent = co ? formatDate(co) : '—';

        var nights = 0;
        if (ci && co) {
            var d1 = new Date(ci + 'T00:00:00'), d2 = new Date(co + 'T00:00:00');
            if (d2 > d1) nights = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
        }
        if (previewNights) previewNights.textContent = nights > 0 ? nights + (nights === 1 ? ' Night' : ' Nights') : '—';

        // Total
        var total = nights * price;
        if (previewTotal) previewTotal.textContent = total > 0 ? '$' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '$0.00';
    };

    // Bind events
    if (guestSelect) guestSelect.addEventListener('change', updatePreview);
    if (roomSelect) roomSelect.addEventListener('change', updatePreview);
    if (checkInInput) checkInInput.addEventListener('change', updatePreview);
    if (checkOutInput) checkOutInput.addEventListener('change', updatePreview);

    // Run once on load
    updatePreview();
})();
</script>
@endsection
