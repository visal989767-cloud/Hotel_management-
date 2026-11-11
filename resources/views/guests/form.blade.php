@extends('layouts.app')
@section('title', $guest->exists ? 'Edit Guest' : 'Add Guest')
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
    $guestAvatar = $getAvatar($guest);
@endphp

<style>
    .container:has(.guest-editor-page) {
        max-width: 1400px !important;
        padding-left: clamp(1rem, 3vw, 2.5rem) !important;
        padding-right: clamp(1rem, 3vw, 2.5rem) !important;
    }

    .guest-editor-page {
        --guest-gold: #d7b477;
        --guest-gold-hover: #e5c78f;
        --guest-dark-bg: rgba(14, 20, 22, 0.98);
        --guest-item-dark: #111618;
        --guest-input-bg: rgba(255, 255, 255, 0.05);
        --guest-line: rgba(224, 232, 236, 0.14);
        --guest-muted: #a4b0b3;
    }

    /* Hero Header */
    .edit-guest-hero {
        min-height: 320px;
        margin-bottom: 0;
        padding: 2.25rem clamp(1.25rem, 5vw, 4rem);
        border: 1px solid rgba(224, 232, 236, 0.16);
        border-radius: 12px 12px 0 0;
        box-shadow: 0 22px 60px -38px rgba(0, 0, 0, 0.75);
    }
    .edit-guest-hero::after {
        background:
            linear-gradient(180deg, rgba(8, 14, 16, 0.52), rgba(8, 14, 16, 0.38) 39%, rgba(8, 14, 16, 0.8)),
            linear-gradient(90deg, rgba(8, 14, 16, 0.6), transparent 85%);
    }
    .edit-guest-hero .hero-top {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
    }
    .edit-guest-heading {
        max-width: 720px;
        padding-top: .6rem;
    }
    .edit-guest-heading .hero-kicker {
        color: var(--guest-gold);
        letter-spacing: .22em;
        font-size: .8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: .35rem;
    }
    .edit-guest-heading .hero-rule {
        width: 55px;
        height: 2px;
        background: var(--guest-gold);
        margin-bottom: .85rem;
    }
    .edit-guest-heading .hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2.8rem, 6vw, 4.8rem);
        color: #fff;
        line-height: .95;
        letter-spacing: -.02em;
        margin-bottom: .55rem;
    }
    .edit-guest-heading .hero-text {
        color: rgba(255, 255, 255, 0.88);
        font-size: .95rem;
        max-width: 52ch;
        line-height: 1.6;
    }
    .edit-guest-hero .hero-btn-ghost {
        border-color: rgba(255, 255, 255, 0.35);
        background: rgba(8, 14, 16, 0.45);
        color: #fff;
        padding: .55rem 1.4rem;
        border-radius: 30px;
        font-size: .85rem;
        font-weight: 500;
        text-decoration: none;
        backdrop-filter: blur(6px);
        transition: all .25s ease;
    }
    .edit-guest-hero .hero-btn-ghost:hover {
        border-color: var(--guest-gold);
        background: var(--guest-gold);
        color: #0f2429;
        transform: translateY(-1px);
    }
    .edit-guest-hero-note {
        position: absolute;
        right: clamp(1.25rem, 5vw, 4rem);
        bottom: 1.15rem;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.05rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
    }

    /* Hospitality Principles Bar */
    .edit-guest-principles {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .75rem;
        padding: 1.15rem clamp(1rem, 3vw, 2rem);
        border: 1px solid var(--guest-line);
        border-top: 0;
        background: rgba(8, 14, 16, 0.94);
        color: #f3e7d2;
    }
    .edit-guest-principle {
        display: grid;
        grid-template-columns: 3.15rem 1fr;
        align-items: center;
        gap: .75rem;
        min-width: 0;
        padding: .35rem .45rem;
        border-radius: 6px;
        transition: background .25s ease;
    }
    .edit-guest-principle:hover {
        background: rgba(255, 255, 255, 0.03);
    }
    .edit-guest-principle-thumb {
        position: relative;
        width: 3.15rem;
        height: 3.15rem;
        border-radius: 50%;
        border: 1.5px solid rgba(215, 180, 119, 0.55);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.45);
        overflow: hidden;
        flex-shrink: 0;
        transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
    }
    .edit-guest-principle:hover .edit-guest-principle-thumb {
        transform: scale(1.06);
        border-color: #f3e7d2;
        box-shadow: 0 6px 18px rgba(215, 180, 119, 0.35);
    }
    .edit-guest-principle-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s ease;
    }
    .edit-guest-principle:hover .edit-guest-principle-img {
        transform: scale(1.12);
    }
    .edit-guest-principle-badge {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 1.15rem;
        height: 1.15rem;
        border-radius: 50%;
        background: rgba(8, 14, 16, 0.92);
        border: 1px solid rgba(215, 180, 119, 0.75);
        color: var(--guest-gold);
        font-size: .6rem;
        display: grid;
        place-items: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
    }
    .edit-guest-principle strong {
        display: block;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.2;
    }
    .edit-guest-principle span {
        display: block;
        color: var(--guest-muted);
        font-size: .64rem;
        line-height: 1.4;
        margin-top: .15rem;
    }

    /* Main Editor Layout */
    .guest-editor-layout {
        margin: 0;
        padding: clamp(1.25rem, 3.5vw, 2.5rem);
        background: rgba(8, 14, 16, 0.94);
        border: 1px solid var(--guest-line);
        border-top: 0;
        border-radius: 0 0 12px 12px;
    }

    /* Form Card */
    .guest-editor-card {
        background: var(--guest-dark-bg);
        border: 1px solid var(--guest-line);
        border-radius: 16px;
        padding: clamp(1.25rem, 2.5vw, 2.25rem);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
    }
    .guest-editor-card h4 {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.9rem;
        color: #ffffff;
        font-weight: 600;
        margin-bottom: .25rem;
    }
    .guest-editor-card p.form-subtitle {
        color: var(--guest-muted);
        font-size: .84rem;
        margin-bottom: 1.75rem;
    }
    .guest-form-label {
        color: #e0e8ec;
        font-size: .82rem;
        font-weight: 600;
        letter-spacing: .02em;
        margin-bottom: .45rem;
        display: flex;
        align-items: center;
        gap: .45rem;
    }
    .guest-form-label i {
        color: var(--guest-gold);
        font-size: .85rem;
    }
    .guest-form-control {
        min-height: 46px;
        border-radius: 8px !important;
        background-color: var(--guest-input-bg) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        font-size: .88rem;
        padding: .6rem 1rem;
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }
    .guest-form-control:focus {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border-color: var(--guest-gold) !important;
        box-shadow: 0 0 0 .2rem rgba(215, 180, 119, 0.2) !important;
        color: #ffffff !important;
    }
    .guest-form-control::placeholder {
        color: #6c7c80;
    }
    .guest-form-select {
        min-height: 46px;
        border-radius: 8px !important;
        background-color: var(--guest-input-bg) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        font-size: .88rem;
        padding: .6rem 1rem;
        cursor: pointer;
    }
    .guest-form-select:focus {
        border-color: var(--guest-gold) !important;
        box-shadow: 0 0 0 .2rem rgba(215, 180, 119, 0.2) !important;
    }
    .guest-form-select option {
        background-color: #12191b;
        color: #ffffff;
    }
    .guest-btn-save {
        background: var(--guest-gold);
        border: 1px solid var(--guest-gold);
        color: #0f2429;
        font-weight: 600;
        font-size: .88rem;
        border-radius: 30px;
        padding: .65rem 1.85rem;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: all .25s ease;
    }
    .guest-btn-save:hover {
        background: var(--guest-gold-hover);
        border-color: var(--guest-gold-hover);
        color: #0f2429;
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(215, 180, 119, 0.35);
    }
    .guest-btn-cancel {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #e0e8ec;
        font-size: .88rem;
        font-weight: 500;
        border-radius: 30px;
        padding: .65rem 1.6rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: all .2s ease;
    }
    .guest-btn-cancel:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.4);
    }

    /* Live Preview Column */
    .guest-preview-sticky {
        position: sticky;
        top: 1.5rem;
    }
    .guest-preview-box {
        background: var(--guest-dark-bg);
        border: 1px solid var(--guest-line);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
    }
    .preview-header-bar {
        padding: .85rem 1.25rem;
        background: rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid var(--guest-line);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .preview-kicker {
        color: var(--guest-gold);
        font-size: .74rem;
        letter-spacing: .15em;
        text-transform: uppercase;
        font-weight: 600;
    }
    .preview-live-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .7rem;
        color: #81c784;
        background: rgba(46, 125, 50, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }
    .preview-live-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #4caf50;
        box-shadow: 0 0 6px #4caf50;
    }

    /* Embedded Preview Card matching screenshot */
    .preview-card-replica {
        background: #111618;
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        margin: 1.25rem;
        overflow: hidden;
        text-align: center;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
    }
    .replica-banner {
        height: 90px;
        background-image: linear-gradient(180deg, rgba(8, 14, 16, 0.15) 0%, rgba(17, 22, 24, 0.98) 100%),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=75');
        background-size: cover;
        background-position: center;
    }
    .replica-avatar-holder {
        width: 80px;
        height: 80px;
        margin: -40px auto 8px;
        position: relative;
        border-radius: 50%;
        border: 3.5px solid #111618;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.5);
        background: #182022;
    }
    .replica-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .replica-green-dot {
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
    .replica-body {
        padding: 0 1.15rem 1.15rem;
    }
    .replica-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #ffffff;
        margin: 0 0 .35rem;
        word-break: break-word;
    }
    .replica-contact-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        color: var(--guest-muted);
        font-size: .8rem;
        margin-bottom: .25rem;
        word-break: break-all;
    }
    .replica-contact-row i {
        color: var(--guest-muted);
        font-size: .75rem;
        flex-shrink: 0;
    }
    .replica-status-pill {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        background: #143521;
        color: #4ade80;
        border: 1px solid rgba(74, 222, 128, 0.25);
        margin: .65rem 0;
    }
    .replica-action-row {
        padding-top: .85rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: center;
        gap: .5rem;
    }
    .replica-circle-icon {
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
    }
    .preview-meta-details {
        padding: 0 1.25rem 1.25rem;
    }
    .preview-detail-row {
        display: flex;
        justify-content: space-between;
        padding: .5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        font-size: .82rem;
    }
    .preview-detail-row:last-child {
        border-bottom: 0;
    }
    .preview-detail-label {
        color: var(--guest-muted);
    }
    .preview-detail-val {
        color: #ffffff;
        font-weight: 500;
    }

    @media (max-width: 991.98px) {
        .edit-guest-principles { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .guest-preview-sticky { position: static; margin-top: 1.5rem; }
    }
    @media (max-width: 575.98px) {
        .edit-guest-hero { min-height: 0; padding: 1.5rem 1.15rem; }
        .edit-guest-principles { grid-template-columns: 1fr; gap: .5rem; }
        .edit-guest-hero .hero-top { flex-direction: column; }
        .edit-guest-hero-note { display: none; }
        .guest-editor-layout { padding: 1rem .65rem; }
    }
</style>

<div class="guest-editor-page">
    <!-- Hero Header -->
    <header class="page-hero photo-hero photo-hero--guests form-photo-hero edit-guest-hero reveal">
        <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
        <div class="hero-top">
            <div class="edit-guest-heading">
                <div class="hero-kicker">Our Valued Guests</div>
                <div class="hero-rule"></div>
                <h1 class="hero-title">{{ $guest->exists ? 'Edit guest' : 'Register guest' }}</h1>
                <p class="hero-text">{{ $guest->exists ? 'Save accurate contact and identification details for each guest.' : 'Add a new guest profile to keep reservations and guest records organized.' }}</p>
            </div>
            <div class="hero-actions">
                <a href="{{ route('guests.index') }}" class="hero-btn hero-btn-ghost">
                    <i class="bi bi-arrow-left me-1"></i> Back to guests
                </a>
            </div>
        </div>
        <div class="edit-guest-hero-note">
            <i class="bi bi-star"></i> Dedicated to hospitality excellence
        </div>
    </header>

    <!-- Hospitality Principles Bar -->
    <section class="edit-guest-principles" aria-label="Guest service standards">
        <div class="edit-guest-principle">
            <div class="edit-guest-principle-thumb">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=480&q=80" alt="Warm welcome" class="edit-guest-principle-img" loading="lazy">
                <span class="edit-guest-principle-badge"><i class="bi bi-heart"></i></span>
            </div>
            <div>
                <strong>Warm welcome</strong>
                <span>Tailored hospitality upon arrival</span>
            </div>
        </div>
        <div class="edit-guest-principle">
            <div class="edit-guest-principle-thumb">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=480&q=80" alt="Attentive service" class="edit-guest-principle-img" loading="lazy">
                <span class="edit-guest-principle-badge"><i class="bi bi-gem"></i></span>
            </div>
            <div>
                <strong>Attentive care</strong>
                <span>Personalized touches for every visit</span>
            </div>
        </div>
        <div class="edit-guest-principle">
            <div class="edit-guest-principle-thumb">
                <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=480&q=80" alt="Guest privacy" class="edit-guest-principle-img" loading="lazy">
                <span class="edit-guest-principle-badge"><i class="bi bi-shield-check"></i></span>
            </div>
            <div>
                <strong>Guest privacy</strong>
                <span>Discreet passport & record keeping</span>
            </div>
        </div>
        <div class="edit-guest-principle">
            <div class="edit-guest-principle-thumb">
                <img src="https://images.unsplash.com/photo-1615874959474-d609969a20ed?auto=format&fit=crop&w=480&q=80" alt="Memorable stays" class="edit-guest-principle-img" loading="lazy">
                <span class="edit-guest-principle-badge"><i class="bi bi-stars"></i></span>
            </div>
            <div>
                <strong>Memorable stays</strong>
                <span>Experiences that inspire return visits</span>
            </div>
        </div>
    </section>

    <!-- Main Editor Layout (Form on Left, Live Preview on Right) -->
    <div class="row g-4 justify-content-center align-items-start guest-editor-layout">
        <!-- Left: Form Card -->
        <div class="col-lg-7">
            <div class="card guest-editor-card reveal" style="--i:1">
                <h4>{{ $guest->exists ? 'Edit Guest' : 'Add Guest' }}</h4>
                <p class="form-subtitle">Update contact information, identification numbers, and residential details.</p>

                <form method="POST" action="{{ $guest->exists ? route('guests.update', $guest) : route('guests.store') }}">
                    @csrf
                    @if ($guest->exists) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="guest-form-label"><i class="bi bi-person"></i> Guest Name</label>
                        <input id="inputGuestName" type="text" name="guest_name" value="{{ old('guest_name', $guest->guest_name) }}" class="form-control guest-form-control @error('guest_name') is-invalid @enderror" placeholder="e.g. Sal" required>
                        @error('guest_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="guest-form-label"><i class="bi bi-envelope"></i> Email</label>
                            <input id="inputGuestEmail" type="email" name="email" value="{{ old('email', $guest->email) }}" class="form-control guest-form-control @error('email') is-invalid @enderror" placeholder="sal@gmail.com" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="guest-form-label"><i class="bi bi-telephone"></i> Phone</label>
                            <input id="inputGuestPhone" type="text" name="phone" value="{{ old('phone', $guest->phone) }}" class="form-control guest-form-control @error('phone') is-invalid @enderror" placeholder="092 111 111 111">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="guest-form-label"><i class="bi bi-gender-ambiguous"></i> Gender</label>
                            <select id="inputGuestGender" name="gender" class="form-select guest-form-select @error('gender') is-invalid @enderror" required>
                                <option value="">-- Select Gender --</option>
                                @foreach (['Male', 'Female', 'Other'] as $g)
                                    <option value="{{ $g }}" @selected(old('gender', $guest->gender) == $g)>{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="guest-form-label"><i class="bi bi-card-text"></i> ID / Passport Number</label>
                            <input id="inputGuestIdNumber" type="text" name="id_number" value="{{ old('id_number', $guest->id_number) }}" class="form-control guest-form-control" placeholder="ID-99201">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="guest-form-label"><i class="bi bi-geo-alt"></i> Address</label>
                        <input id="inputGuestAddress" type="text" name="address" value="{{ old('address', $guest->address) }}" class="form-control guest-form-control" placeholder="Phnom Penh, Cambodia">
                    </div>

                    <div class="d-flex gap-2 flex-wrap pt-2">
                        <button type="submit" class="btn guest-btn-save">
                            <i class="bi bi-check2"></i> {{ $guest->exists ? 'Save changes' : 'Save guest' }}
                        </button>
                        <a href="{{ route('guests.index') }}" class="btn guest-btn-cancel">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Live Profile Preview Card -->
        <div class="col-lg-5">
            <div class="guest-preview-sticky reveal" style="--i:2">
                <div class="guest-preview-box">
                    <div class="preview-header-bar">
                        <span class="preview-kicker">Live Guest Profile</span>
                        <span class="preview-live-badge"><span class="preview-live-dot"></span> Real-time</span>
                    </div>

                    <!-- Exact Card Replica matching Guests Index -->
                    <div class="preview-card-replica">
                        <div class="replica-banner"></div>
                        <div class="replica-body">
                            <div class="replica-avatar-holder">
                                <img id="liveAvatarImg" src="{{ $guestAvatar }}" alt="" class="replica-avatar-img" onerror="this.src='https://ui-avatars.com/api/?name=Guest&background=111618&color=d7b477'">
                                <span class="replica-green-dot" title="Active"></span>
                            </div>
                            <h5 id="liveGuestName" class="replica-name">{{ old('guest_name', $guest->guest_name) ?: 'Guest Name' }}</h5>
                            <div class="replica-contact-row">
                                <i class="bi bi-envelope"></i>
                                <span id="liveGuestEmail">{{ old('email', $guest->email) ?: 'email@example.com' }}</span>
                            </div>
                            <div class="replica-contact-row">
                                <i class="bi bi-telephone"></i>
                                <span id="liveGuestPhone">{{ old('phone', $guest->phone) ?: 'No phone provided' }}</span>
                            </div>
                            <div>
                                <span class="replica-status-pill">Active</span>
                            </div>
                            <div class="replica-action-row">
                                <span class="replica-circle-icon"><i class="bi bi-eye"></i></span>
                                <span class="replica-circle-icon"><i class="bi bi-pencil"></i></span>
                                <span class="replica-circle-icon"><i class="bi bi-trash"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="preview-meta-details">
                        <div class="preview-detail-row">
                            <span class="preview-detail-label">Gender:</span>
                            <span id="liveGuestGender" class="preview-detail-val">{{ old('gender', $guest->gender) ?: 'Not set' }}</span>
                        </div>
                        <div class="preview-detail-row">
                            <span class="preview-detail-label">ID / Passport:</span>
                            <span id="liveGuestIdNumber" class="preview-detail-val">{{ old('id_number', $guest->id_number) ?: 'N/A' }}</span>
                        </div>
                        <div class="preview-detail-row">
                            <span class="preview-detail-label">Address:</span>
                            <span id="liveGuestAddress" class="preview-detail-val text-truncate" style="max-width: 220px;">{{ old('address', $guest->address) ?: 'N/A' }}</span>
                        </div>
                        @if ($guest->exists)
                            <div class="preview-detail-row">
                                <span class="preview-detail-label">Bookings on Record:</span>
                                <span class="preview-detail-val text-warning">{{ $guest->bookings()->count() }} stays</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var nameInput = document.getElementById('inputGuestName');
        var emailInput = document.getElementById('inputGuestEmail');
        var phoneInput = document.getElementById('inputGuestPhone');
        var genderInput = document.getElementById('inputGuestGender');
        var idInput = document.getElementById('inputGuestIdNumber');
        var addressInput = document.getElementById('inputGuestAddress');

        var liveName = document.getElementById('liveGuestName');
        var liveEmail = document.getElementById('liveGuestEmail');
        var livePhone = document.getElementById('liveGuestPhone');
        var liveGender = document.getElementById('liveGuestGender');
        var liveIdNumber = document.getElementById('liveGuestIdNumber');
        var liveAddress = document.getElementById('liveGuestAddress');

        if (nameInput && liveName) {
            nameInput.addEventListener('input', function () {
                liveName.textContent = nameInput.value.trim() || 'Guest Name';
            });
        }
        if (emailInput && liveEmail) {
            emailInput.addEventListener('input', function () {
                liveEmail.textContent = emailInput.value.trim() || 'email@example.com';
            });
        }
        if (phoneInput && livePhone) {
            phoneInput.addEventListener('input', function () {
                livePhone.textContent = phoneInput.value.trim() || 'No phone provided';
            });
        }
        if (genderInput && liveGender) {
            genderInput.addEventListener('change', function () {
                liveGender.textContent = genderInput.options[genderInput.selectedIndex]?.text || 'Not set';
            });
        }
        if (idInput && liveIdNumber) {
            idInput.addEventListener('input', function () {
                liveIdNumber.textContent = idInput.value.trim() || 'N/A';
            });
        }
        if (addressInput && liveAddress) {
            addressInput.addEventListener('input', function () {
                liveAddress.textContent = addressInput.value.trim() || 'N/A';
            });
        }
    })();
</script>
@endsection
