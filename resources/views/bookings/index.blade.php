@extends('layouts.app')
@section('title', 'Bookings & Reservations')
@section('content')
@php
    $guestAvatars = [
        'sal@gmail.com' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=200&q=80',
        'lengmakara@gmail.com' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
        'povnita@gmail.com' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80',
        'lin@gmail.com' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=200&q=80',
        'kongsopheak@gmail.com' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=200&q=80',
    ];
    $fallbackAvatars = [
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
        'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80',
    ];
    $getGuestAvatar = function ($guest) use ($guestAvatars, $fallbackAvatars) {
        $email = strtolower(trim((string) $guest->email));
        if (isset($guestAvatars[$email])) {
            return $guestAvatars[$email];
        }
        $id = (int) ($guest->guest_id ?: 1);
        return $fallbackAvatars[($id - 1) % count($fallbackAvatars)];
    };

    $heroSlides = [
        [
            'title' => 'Your Comfort Outside Home',
            'room' => 'Executive Ocean Suite',
            'desc' => 'Embrace the comfort and warmth of a home away from home.',
            'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1600&q=85',
        ],
        [
            'title' => 'Tranquility In Every Detail',
            'room' => 'Deluxe Garden Haven',
            'desc' => 'Sunlit suites, bespoke amenities, and quiet serenity.',
            'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1600&q=85',
        ],
        [
            'title' => 'Warm Luxury & Pure Rest',
            'room' => 'Premier Sunrise Villa',
            'desc' => 'Curated spaces crafted for restorative getaways.',
            'image' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1600&q=85',
        ],
    ];
@endphp

<style>
    /* Scope wrapper */
    .container:has(.icottage-page) {
        max-width: 1400px !important;
        padding-left: clamp(1rem, 3vw, 2.5rem) !important;
        padding-right: clamp(1rem, 3vw, 2.5rem) !important;
    }

    .icottage-page {
        --ic-bg: #ffffff;
        --ic-card-bg: #faf8f5;
        --ic-dark: #191b1d;
        --ic-dark-soft: #23272a;
        --ic-gold: #c59b58;
        --ic-gold-hover: #b08542;
        --ic-muted: #747c82;
        --ic-border: #e6e0d6;
        --ic-border-soft: rgba(0, 0, 0, 0.07);
        --ic-radius-lg: 26px;
        --ic-radius-md: 18px;
        --ic-radius-sm: 12px;
        font-family: 'Figtree', system-ui, -apple-system, sans-serif;
        color: #191b1d;
        padding-bottom: 3.5rem;
    }

    /* Outer Mockup Card Canvas */
    .icottage-hero-canvas {
        background: var(--ic-bg);
        border-radius: var(--ic-radius-lg);
        box-shadow: 0 30px 80px -25px rgba(0, 0, 0, 0.45);
        padding: clamp(1rem, 2vw, 1.5rem);
        margin-bottom: 2.25rem;
        border: 1px solid rgba(255, 255, 255, 0.6);
        position: relative;
    }

    /* Bento Grid Section */
    .icottage-bento-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.45fr) minmax(0, 1fr);
        gap: 1.25rem;
    }

    /* Left Big Bedroom Showcase */
    .icottage-hero-showcase {
        position: relative;
        border-radius: 22px;
        min-height: 520px;
        overflow: hidden;
        background: #1a2022;
        box-shadow: 0 16px 40px -15px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.75rem;
        transition: transform .3s ease;
    }
    .icottage-showcase-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        z-index: 0;
        transition: opacity .45s ease, transform .6s ease;
    }
    .icottage-hero-showcase::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background:
            linear-gradient(180deg, rgba(8, 14, 16, 0.22) 0%, rgba(8, 14, 16, 0.05) 40%, rgba(8, 14, 16, 0.82) 100%),
            linear-gradient(90deg, rgba(8, 14, 16, 0.45) 0%, transparent 60%);
    }

    /* Showcase Top Elements (Chips & Floating Search Capsule) */
    .icottage-showcase-top {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .icottage-filter-chips {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }
    .icottage-chip {
        padding: .45rem 1.05rem;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        font-size: .8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all .2s ease;
    }
    .icottage-chip:hover,
    .icottage-chip.active {
        background: #ffffff;
        color: #111416;
        border-color: #ffffff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    }

    /* Floating Search Capsule */
    .icottage-search-capsule {
        background: #ffffff;
        border-radius: 18px;
        padding: .95rem 1.15rem;
        max-width: 285px;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }
    .icottage-search-capsule p {
        margin: 0 0 .65rem;
        font-size: .78rem;
        line-height: 1.35;
        color: #4b555a;
        font-weight: 500;
    }
    .icottage-search-bar {
        display: flex;
        align-items: center;
        background: #f4f2ee;
        border-radius: 30px;
        padding: 3px 4px 3px 12px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .icottage-search-bar input {
        border: 0;
        background: transparent;
        font-size: .8rem;
        color: #191b1d;
        width: 100%;
        outline: none;
    }
    .icottage-search-bar input::placeholder {
        color: #8c969b;
    }
    .icottage-search-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #111416;
        color: #ffffff;
        border: 0;
        display: grid;
        place-items: center;
        font-size: .75rem;
        flex-shrink: 0;
        transition: transform .2s ease, background-color .2s ease;
    }
    .icottage-search-btn:hover {
        background: var(--ic-gold);
        color: #111416;
        transform: scale(1.05);
    }

    /* Showcase Bottom Elements (Headline & Carousel Dots) */
    .icottage-showcase-bottom {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1.5rem;
    }
    .icottage-showcase-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2.4rem, 4.2vw, 3.8rem);
        color: #ffffff;
        line-height: 1.02;
        letter-spacing: -.02em;
        margin: 0;
        text-shadow: 0 3px 18px rgba(0, 0, 0, 0.6);
    }
    .icottage-carousel-dots {
        display: flex;
        align-items: center;
        gap: .5rem;
        background: rgba(0, 0, 0, 0.4);
        padding: .45rem .75rem;
        border-radius: 20px;
        backdrop-filter: blur(6px);
    }
    .icottage-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        border: 0;
        padding: 0;
        cursor: pointer;
        transition: all .25s ease;
    }
    .icottage-dot.active {
        width: 22px;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
    }

    /* Right Column Bento Stack */
    .icottage-right-stack {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }

    /* Right Card 1: Homestay Preview Photo */
    .icottage-homestay-card {
        position: relative;
        height: 200px;
        border-radius: 20px;
        overflow: hidden;
        background: #111618;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    .icottage-homestay-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        z-index: 0;
        transition: transform .5s ease;
    }
    .icottage-homestay-card:hover .icottage-homestay-bg {
        transform: scale(1.05);
    }
    .icottage-homestay-card::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(180deg, rgba(8, 14, 16, 0.1) 0%, rgba(8, 14, 16, 0.78) 100%);
    }
    .icottage-homestay-top {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .icottage-media-pill {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: rgba(0, 0, 0, 0.45);
        color: #ffffff;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: .75rem;
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .icottage-arrow-pill {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #ffffff;
        color: #111416;
        display: grid;
        place-items: center;
        font-size: .85rem;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        transition: transform .2s ease, background-color .2s ease;
    }
    .icottage-arrow-pill:hover {
        background: var(--ic-gold);
        color: #111416;
        transform: scale(1.1);
    }
    .icottage-homestay-bottom {
        position: relative;
        z-index: 2;
    }
    .icottage-homestay-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 .15rem;
        letter-spacing: -.01em;
    }
    .icottage-homestay-location {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: .78rem;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
    }
    .icottage-homestay-location a {
        color: #f3d9ae;
        text-decoration: underline;
        font-weight: 500;
    }

    /* Right Card 2: Interactive Date & Guest Selector Box */
    .icottage-booking-box {
        background: #faf8f5;
        border: 1px solid var(--ic-border);
        border-radius: 18px;
        padding: 1.15rem 1.35rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }
    .icottage-booking-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .5rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        font-size: .84rem;
    }
    .icottage-booking-row:last-of-type {
        border-bottom: 0;
        margin-bottom: .65rem;
    }
    .icottage-booking-label {
        color: #555e63;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: .45rem;
    }
    .icottage-booking-val {
        color: #111416;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        border: 0;
        background: transparent;
        padding: 0;
        cursor: pointer;
        text-align: right;
    }
    .icottage-booking-input {
        border: 0;
        background: transparent;
        font-weight: 600;
        font-size: .84rem;
        color: #111416;
        text-align: right;
        outline: none;
        cursor: pointer;
        font-family: inherit;
        max-width: 145px;
    }
    .icottage-book-btn {
        width: 100%;
        background: #ffffff;
        color: #111416;
        border: 1.5px solid #111416;
        border-radius: 30px;
        padding: .65rem 1rem;
        font-size: .86rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        transition: all .25s ease;
        text-decoration: none;
    }
    .icottage-book-btn:hover {
        background: #111416;
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
    }

    /* Right Card 3: Two Bottom Destination / Room Thumbnails */
    .icottage-thumbs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .85rem;
    }
    .icottage-thumb-card {
        position: relative;
        height: 110px;
        border-radius: 16px;
        overflow: hidden;
        background: #1a2022;
        padding: .85rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        text-decoration: none;
    }
    .icottage-thumb-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
        transition: transform .4s ease;
    }
    .icottage-thumb-card:hover .icottage-thumb-img {
        transform: scale(1.08);
    }
    .icottage-thumb-card::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(180deg, rgba(8, 14, 16, 0.15) 0%, rgba(8, 14, 16, 0.72) 100%);
    }
    .icottage-thumb-top {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: flex-end;
    }
    .icottage-thumb-arrow {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        color: #111416;
        display: grid;
        place-items: center;
        font-size: .75rem;
        transition: transform .2s ease;
    }
    .icottage-thumb-card:hover .icottage-thumb-arrow {
        transform: scale(1.15);
        background: var(--ic-gold);
    }
    .icottage-thumb-name {
        position: relative;
        z-index: 2;
        color: #ffffff;
        font-weight: 600;
        font-size: .84rem;
        margin: 0;
        text-shadow: 0 1px 6px rgba(0, 0, 0, 0.5);
    }

    /* KPI Stats Bar */
    .icottage-stats-bar {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .icottage-stat-card {
        background: rgba(14, 20, 22, 0.96);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--ic-radius-md);
        padding: 1.25rem 1.5rem;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
        display: flex;
        align-items: center;
        gap: 1.15rem;
        backdrop-filter: blur(10px);
    }
    .icottage-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(197, 155, 88, 0.15);
        border: 1px solid rgba(197, 155, 88, 0.35);
        color: var(--ic-gold);
        display: grid;
        place-items: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .icottage-stat-num {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 2.1rem;
        color: #ffffff;
        font-weight: 700;
        line-height: 1;
        margin: 0 0 .2rem;
    }
    .icottage-stat-label {
        font-size: .78rem;
        color: #9ba6aa;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: .08em;
        font-weight: 500;
    }

    /* Live Database Bookings Directory Section */
    .icottage-directory-container {
        background: rgba(14, 20, 22, 0.97);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--ic-radius-lg);
        padding: clamp(1.25rem, 2.5vw, 2.25rem);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(12px);
    }

    .directory-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.75rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .directory-heading h3 {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.95rem;
        color: #ffffff;
        font-weight: 600;
        margin: 0 0 .25rem;
    }
    .directory-heading p {
        color: #9ba6aa;
        font-size: .84rem;
        margin: 0;
    }

    /* Filter Pill Toolbar */
    .directory-controls {
        display: flex;
        align-items: center;
        gap: .65rem;
        flex-wrap: wrap;
    }
    .dir-search-wrap {
        position: relative;
        min-width: 260px;
    }
    .dir-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #7d8b90;
        font-size: .85rem;
    }
    .dir-search-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #ffffff;
        padding: .55rem 1rem .55rem 2.35rem;
        border-radius: 30px;
        font-size: .84rem;
        outline: none;
        transition: border-color .2s ease;
    }
    .dir-search-input:focus {
        border-color: var(--ic-gold);
        background: rgba(255, 255, 255, 0.08);
    }
    .dir-select {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #ffffff;
        padding: .55rem 2rem .55rem 1rem;
        border-radius: 30px;
        font-size: .84rem;
        outline: none;
        cursor: pointer;
    }
    .dir-select option {
        background: #111618;
        color: #ffffff;
    }
    .dir-btn-filter {
        background: var(--ic-gold);
        border: 1px solid var(--ic-gold);
        color: #111416;
        font-weight: 600;
        font-size: .84rem;
        padding: .55rem 1.25rem;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: all .2s ease;
    }
    .dir-btn-filter:hover {
        background: var(--ic-gold-hover);
        color: #111416;
        transform: translateY(-1px);
    }
    .dir-btn-reset {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #e0e8ec;
        font-size: .84rem;
        padding: .55rem 1.15rem;
        border-radius: 30px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
    }
    .dir-btn-reset:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }
    .dir-view-toggle {
        display: inline-flex;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 30px;
        padding: 3px;
        gap: 3px;
    }
    .dir-view-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: transparent;
        border: 0;
        color: #8c989c;
        display: grid;
        place-items: center;
        font-size: .85rem;
        cursor: pointer;
        transition: all .2s ease;
    }
    .dir-view-btn.active {
        background: var(--ic-gold);
        color: #111416;
    }

    /* Bento Cards Grid View */
    .bookings-grid-view {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
    }
    .booking-card {
        background: #111618;
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 18px;
        padding: 1.35rem;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform .25s ease, border-color .25s ease;
    }
    .booking-card:hover {
        transform: translateY(-2px);
        border-color: rgba(197, 155, 88, 0.45);
    }
    .booking-card-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: .75rem;
        margin-bottom: 1rem;
        padding-bottom: .85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    .booking-guest-info {
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .booking-guest-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(197, 155, 88, 0.45);
    }
    .booking-guest-name {
        color: #ffffff;
        font-size: .95rem;
        font-weight: 600;
        margin: 0;
    }
    .booking-guest-email {
        color: #8c989c;
        font-size: .76rem;
        margin: 0;
    }
    .booking-id-tag {
        font-size: .72rem;
        color: var(--ic-gold);
        background: rgba(197, 155, 88, 0.12);
        padding: 2px 8px;
        border-radius: 10px;
        border: 1px solid rgba(197, 155, 88, 0.25);
    }

    .booking-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1.15rem;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        padding: .85rem;
        border: 1px solid rgba(255, 255, 255, 0.04);
    }
    .booking-detail-item {
        display: flex;
        flex-direction: column;
    }
    .booking-detail-label {
        font-size: .68rem;
        color: #7d8b90;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 2px;
    }
    .booking-detail-val {
        color: #f3f5f6;
        font-size: .84rem;
        font-weight: 600;
    }
    .booking-card-foot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: .75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }
    .booking-price-tag {
        display: flex;
        flex-direction: column;
    }
    .booking-price-label {
        font-size: .66rem;
        color: #7d8b90;
        text-transform: uppercase;
    }
    .booking-price-val {
        color: var(--ic-gold);
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.45rem;
        font-weight: 700;
        line-height: 1;
    }
    .booking-card-actions {
        display: flex;
        gap: .4rem;
    }
    .booking-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #c4d0d3;
        display: grid;
        place-items: center;
        font-size: .8rem;
        text-decoration: none;
        transition: all .2s ease;
    }
    .booking-action-btn:hover {
        background: var(--ic-gold);
        color: #111416;
        border-color: var(--ic-gold);
        transform: translateY(-1px);
    }
    .booking-action-btn.btn-delete:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
    }

    /* Refined Table View */
    .bookings-table-view {
        overflow-x: auto;
    }
    .icottage-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        color: #e7e9e7;
    }
    .icottage-table thead th {
        background: rgba(255, 255, 255, 0.03);
        color: #c0b49f;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        font-weight: 600;
        padding: .85rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        white-space: nowrap;
    }
    .icottage-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        font-size: .86rem;
        vertical-align: middle;
    }
    .icottage-table tbody tr:hover td {
        background: rgba(197, 155, 88, 0.05);
    }

    /* Modal Styling */
    .icottage-modal .modal-content {
        background: #0f1618 !important;
        border: 1px solid rgba(215, 180, 119, 0.35) !important;
        border-radius: 20px !important;
        color: #ffffff !important;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85) !important;
    }
    .icottage-modal .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 1.25rem 1.75rem;
    }
    .icottage-modal .modal-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.75rem;
        color: #ffffff;
    }
    .icottage-modal .modal-body {
        padding: 1.75rem;
    }
    .icottage-modal .form-label {
        font-size: .82rem;
        font-weight: 600;
        color: #e0e8ec;
        margin-bottom: .4rem;
    }
    .icottage-modal .form-control,
    .icottage-modal .form-select {
        background-color: rgba(255, 255, 255, 0.06) !important;
        border: 1px solid rgba(255, 255, 255, 0.14) !important;
        color: #ffffff !important;
        border-radius: 8px;
        min-height: 44px;
        font-size: .88rem;
    }
    .icottage-modal .form-control:focus,
    .icottage-modal .form-select:focus {
        border-color: var(--ic-gold) !important;
        box-shadow: 0 0 0 .2rem rgba(197, 155, 88, 0.25) !important;
    }
    .icottage-modal .form-select option {
        background: #111618;
        color: #fff;
    }

    @media (max-width: 991.98px) {
        .icottage-bento-grid { grid-template-columns: 1fr; }
        .icottage-stats-bar { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .icottage-hero-showcase { min-height: 420px; }
        .icottage-header-bar { flex-direction: column; align-items: flex-start; }
        .icottage-nav-pills { flex-wrap: wrap; gap: 1rem; }
    }
    @media (max-width: 575.98px) {
        .icottage-stats-bar { grid-template-columns: 1fr; }
        .icottage-search-capsule { max-width: 100%; width: 100%; margin-top: .75rem; }
        .icottage-showcase-title { font-size: 2rem; }
        .icottage-hero-canvas { padding: 1rem; }
        .bookings-grid-view { grid-template-columns: 1fr; }
    }
</style>

<div class="icottage-page">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 text-white" style="background: rgba(34, 197, 94, 0.25); border: 1px solid rgba(74, 222, 128, 0.4) !important; border-radius: 14px;" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 text-white" style="background: rgba(239, 68, 68, 0.25); border: 1px solid rgba(248, 113, 113, 0.4) !important; border-radius: 14px;" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Hero Section (Figma Mockup Replica) -->
    <div class="icottage-hero-canvas reveal">

        <!-- Bento Grid Content -->
        <div class="icottage-bento-grid">
            <!-- Left Large Bedroom Card -->
            <div class="icottage-hero-showcase">
                <img id="showcaseHeroImage" src="{{ $heroSlides[0]['image'] }}" alt="Featured Room" class="icottage-showcase-bg">

                <!-- Top Floating Elements: Category Pills & Search Capsule -->
                <div class="icottage-showcase-top">
                    <div class="icottage-filter-chips">
                        <a href="{{ route('bookings.index') }}" class="icottage-chip {{ !request('status') ? 'active' : '' }}">All Stays</a>
                        <a href="{{ route('bookings.index', ['status' => 'checked_in']) }}" class="icottage-chip {{ request('status') === 'checked_in' ? 'active' : '' }}">Staycation</a>
                        <a href="{{ route('bookings.index', ['status' => 'booked']) }}" class="icottage-chip {{ request('status') === 'booked' ? 'active' : '' }}">Homestay</a>
                    </div>

                    <!-- Floating Search Pill Capsule -->
                    <div class="icottage-search-capsule">
                        <p id="showcaseDesc">{{ $heroSlides[0]['desc'] }}</p>
                        <form method="GET" action="{{ route('bookings.index') }}" class="icottage-search-bar">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guest or room...">
                            <button type="submit" class="icottage-search-btn" title="Search"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                </div>

                <!-- Bottom Floating Headline & Carousel Indicators -->
                <div class="icottage-showcase-bottom">
                    <div>
                        <h1 id="showcaseTitle" class="icottage-showcase-title">Your Comfort<br>Outside Home</h1>
                    </div>
                    <div class="icottage-carousel-dots" role="tablist" aria-label="Showcase carousel">
                        <button type="button" class="icottage-dot active" onclick="switchShowcaseSlide(0)" title="Slide 1"></button>
                        <button type="button" class="icottage-dot" onclick="switchShowcaseSlide(1)" title="Slide 2"></button>
                        <button type="button" class="icottage-dot" onclick="switchShowcaseSlide(2)" title="Slide 3"></button>
                    </div>
                </div>
            </div>

            <!-- Right Column Stack -->
            <div class="icottage-right-stack">
                <!-- Top Card: Homestay Room Feature -->
                <div class="icottage-homestay-card">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80" alt="Homestay Room" class="icottage-homestay-bg">
                    <div class="icottage-homestay-top">
                        <span class="icottage-media-pill"><i class="bi bi-play-fill"></i> 360° Tour</span>
                        <a href="{{ route('rooms.index') }}" class="icottage-arrow-pill" title="Explore Rooms"><i class="bi bi-arrow-up-right"></i></a>
                    </div>
                    <div class="icottage-homestay-bottom">
                        <h4 class="icottage-homestay-title">Homestay</h4>
                        <p class="icottage-homestay-location">
                            <span><i class="bi bi-geo-alt-fill text-warning me-1"></i> AB Street, Ocean Wing</span>
                            <a href="{{ route('rooms.index') }}">Directions</a>
                        </p>
                    </div>
                </div>

                <!-- Middle Card: Interactive Date & Guest Booking Widget -->
                <div class="icottage-booking-box">
                    <form method="POST" action="{{ route('bookings.store') }}" id="quickBookingForm">
                        @csrf
                        <div class="icottage-booking-row">
                            <span class="icottage-booking-label"><i class="bi bi-calendar-event text-warning"></i> Check In</span>
                            <input type="date" name="check_in" id="quickCheckIn" value="{{ now()->format('Y-m-d') }}" class="icottage-booking-input" required>
                        </div>
                        <div class="icottage-booking-row">
                            <span class="icottage-booking-label"><i class="bi bi-calendar2-check text-warning"></i> Check Out</span>
                            <input type="date" name="check_out" id="quickCheckOut" value="{{ now()->addDays(3)->format('Y-m-d') }}" class="icottage-booking-input" required>
                        </div>
                        <div class="icottage-booking-row">
                            <span class="icottage-booking-label"><i class="bi bi-person text-warning"></i> Guest</span>
                            <select name="guest_id" class="icottage-booking-input" style="max-width: 175px;" required>
                                @foreach ($guests as $g)
                                    <option value="{{ $g->guest_id }}">{{ $g->guest_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="icottage-booking-row">
                            <span class="icottage-booking-label"><i class="bi bi-door-closed text-warning"></i> Room</span>
                            <select name="room_id" class="icottage-booking-input" style="max-width: 175px;" required>
                                @foreach ($rooms as $r)
                                    <option value="{{ $r->room_id }}">
                                        Room {{ $r->room_number }} ({{ $r->roomType->type_name }} - ${{ number_format($r->roomType->price_per_night, 0) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="status" value="booked">
                        <button type="submit" class="icottage-book-btn">
                            <i class="bi bi-check2-circle"></i> Book Homestay
                        </button>
                    </form>
                </div>

                <!-- Bottom Cards: Two Destination / Room Thumbnails -->
                <div class="icottage-thumbs-grid">
                    <a href="{{ route('rooms.index') }}" class="icottage-thumb-card">
                        <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=500&q=80" alt="Bali Suite" class="icottage-thumb-img">
                        <div class="icottage-thumb-top"><span class="icottage-thumb-arrow"><i class="bi bi-arrow-up-right"></i></span></div>
                        <p class="icottage-thumb-name">Bali Villa, Cabana</p>
                    </a>
                    <a href="{{ route('rooms.index') }}" class="icottage-thumb-card">
                        <img src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=500&q=80" alt="Tokyo Suite" class="icottage-thumb-img">
                        <div class="icottage-thumb-top"><span class="icottage-thumb-arrow"><i class="bi bi-arrow-up-right"></i></span></div>
                        <p class="icottage-thumb-name">Tokyo Suite, Zen</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Live KPI Stats Bar -->
    <div class="icottage-stats-bar reveal">
        <div class="icottage-stat-card">
            <div class="icottage-stat-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
            <div>
                <h4 class="icottage-stat-num">{{ $totalBookings }}</h4>
                <p class="icottage-stat-label">Total Bookings</p>
            </div>
        </div>
        <div class="icottage-stat-card">
            <div class="icottage-stat-icon" style="background: rgba(34, 197, 94, 0.15); border-color: rgba(34, 197, 94, 0.35); color: #4ade80;">
                <i class="bi bi-door-open-fill"></i>
            </div>
            <div>
                <h4 class="icottage-stat-num">{{ $checkedInCount }}</h4>
                <p class="icottage-stat-label">Checked In / Stays</p>
            </div>
        </div>
        <div class="icottage-stat-card">
            <div class="icottage-stat-icon" style="background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.35); color: #facc15;">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <h4 class="icottage-stat-num">{{ $bookedCount }}</h4>
                <p class="icottage-stat-label">Upcoming Booked</p>
            </div>
        </div>
        <div class="icottage-stat-card">
            <div class="icottage-stat-icon" style="background: rgba(215, 180, 119, 0.18); border-color: rgba(215, 180, 119, 0.45); color: var(--ic-gold);">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <h4 class="icottage-stat-num">${{ number_format($totalRevenue, 0) }}</h4>
                <p class="icottage-stat-label">Total Revenue</p>
            </div>
        </div>
    </div>

    <!-- Live Database Bookings Directory Section -->
    <div id="bookingDirectory" class="icottage-directory-container reveal">
        <!-- Toolbar & Filter Header -->
        <div class="directory-toolbar">
            <div class="directory-heading">
                <h3>Reservation Directory</h3>
                <p>Showing {{ $bookings->total() }} total guest stays and reservations on record.</p>
            </div>

            <div class="directory-controls">
                <form method="GET" action="{{ route('bookings.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="dir-search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" class="dir-search-input" placeholder="Search guest or room #...">
                    </div>

                    <select name="status" class="dir-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="dir-btn-filter"><i class="bi bi-funnel"></i> Filter</button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('bookings.index') }}" class="dir-btn-reset"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                    @endif
                </form>

                <div class="dir-view-toggle">
                    <button type="button" id="btnViewGrid" class="dir-view-btn active" onclick="switchView('grid')" title="Card Grid View"><i class="bi bi-grid-fill"></i></button>
                    <button type="button" id="btnViewTable" class="dir-view-btn" onclick="switchView('table')" title="Table View"><i class="bi bi-list-ul"></i></button>
                </div>

                <button type="button" class="dir-btn-filter ms-lg-2" data-bs-toggle="modal" data-bs-target="#newBookingModal">
                    <i class="bi bi-plus-lg"></i> New Booking
                </button>
            </div>
        </div>

        <!-- 1. Bento Card Grid View (Default) -->
        <div id="bookingsGridView" class="bookings-grid-view">
            @forelse ($bookings as $b)
                <div class="booking-card">
                    <div>
                        <div class="booking-card-head">
                            <div class="booking-guest-info">
                                <img src="{{ $getGuestAvatar($b->guest) }}" alt="{{ $b->guest->guest_name }}" class="booking-guest-avatar">
                                <div>
                                    <h5 class="booking-guest-name">{{ $b->guest->guest_name }}</h5>
                                    <p class="booking-guest-email">{{ $b->guest->email }}</p>
                                </div>
                            </div>
                            <span class="booking-id-tag">#{{ $b->booking_id }}</span>
                        </div>

                        <div class="booking-card-details">
                            <div class="booking-detail-item">
                                <span class="booking-detail-label">Room</span>
                                <span class="booking-detail-val">Room {{ $b->room->room_number }}</span>
                                <small class="text-muted" style="font-size: .72rem;">{{ $b->room->roomType->type_name }}</small>
                            </div>
                            <div class="booking-detail-item">
                                <span class="booking-detail-label">Duration</span>
                                <span class="booking-detail-val">{{ $b->nights }} Night{{ $b->nights > 1 ? 's' : '' }}</span>
                                <small class="text-muted" style="font-size: .72rem;">${{ number_format($b->room->roomType->price_per_night, 0) }}/night</small>
                            </div>
                            <div class="booking-detail-item">
                                <span class="booking-detail-label">Check-in</span>
                                <span class="booking-detail-val">{{ $b->check_in->format('M d, Y') }}</span>
                            </div>
                            <div class="booking-detail-item">
                                <span class="booking-detail-label">Check-out</span>
                                <span class="booking-detail-val">{{ $b->check_out->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            @include('bookings._status', ['status' => $b->status])
                        </div>
                    </div>

                    <div class="booking-card-foot">
                        <div class="booking-price-tag">
                            <span class="booking-price-label">Total Amount</span>
                            <span class="booking-price-val">${{ number_format($b->total_price, 2) }}</span>
                        </div>
                        <div class="booking-card-actions">
                            <a href="{{ route('bookings.edit', $b) }}" class="booking-action-btn" title="Edit Booking">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('bookings.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this reservation record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="booking-action-btn btn-delete" title="Delete Booking">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5" style="color: #9ba6aa;">
                    <i class="bi bi-calendar-x fs-1 d-block mb-3" style="color: var(--ic-gold);"></i>
                    <h5>No reservations found</h5>
                    <p class="small">Try adjusting your search criteria or create a new booking.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. Refined Table View -->
        <div id="bookingsTableView" class="bookings-table-view d-none">
            <table class="icottage-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Nights</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $b)
                        <tr>
                            <td><span class="booking-id-tag">#{{ $b->booking_id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $getGuestAvatar($b->guest) }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold text-white">{{ $b->guest->guest_name }}</div>
                                        <div class="text-muted small" style="font-size: .74rem;">{{ $b->guest->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-white">Room {{ $b->room->room_number }}</span>
                                <div class="text-muted small" style="font-size: .74rem;">{{ $b->room->roomType->type_name }}</div>
                            </td>
                            <td>{{ $b->check_in->format('M d, Y') }}</td>
                            <td>{{ $b->check_out->format('M d, Y') }}</td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $b->nights }} nts</span></td>
                            <td class="fw-bold" style="color: var(--ic-gold); font-size: 1.05rem;">${{ number_format($b->total_price, 2) }}</td>
                            <td>@include('bookings._status', ['status' => $b->status])</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('bookings.edit', $b) }}" class="booking-action-btn" title="Edit Booking">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('bookings.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this reservation?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="booking-action-btn btn-delete" title="Delete Booking">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No reservations found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} reservations</span>
            <div>{{ $bookings->links() }}</div>
        </div>
    </div>
</div>

<!-- Modal: New Booking -->
<div class="modal fade icottage-modal" id="newBookingModal" tabindex="-1" aria-labelledby="newBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="text-warning text-uppercase small" style="letter-spacing: .15em; font-weight: 600;">Ocean Resort Reservations</span>
                    <h5 class="modal-title" id="newBookingModalLabel">Create New Booking</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('bookings.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-person text-warning me-1"></i> Guest</label>
                            <select name="guest_id" class="form-select" required>
                                <option value="">-- Select Guest --</option>
                                @foreach ($guests as $g)
                                    <option value="{{ $g->guest_id }}">{{ $g->guest_name }} ({{ $g->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-door-closed text-warning me-1"></i> Room</label>
                            <select name="room_id" id="modalRoomSelect" class="form-select" required onchange="updateModalPrice()">
                                <option value="">-- Select Room --</option>
                                @foreach ($rooms as $r)
                                    <option value="{{ $r->room_id }}" data-price="{{ $r->roomType->price_per_night }}">
                                        Room {{ $r->room_number }} - {{ $r->roomType->type_name }} (${{ number_format($r->roomType->price_per_night, 2) }}/night)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-calendar-event text-warning me-1"></i> Check-in Date</label>
                            <input type="date" name="check_in" id="modalCheckIn" value="{{ now()->format('Y-m-d') }}" class="form-control" required onchange="updateModalPrice()">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-calendar2-check text-warning me-1"></i> Check-out Date</label>
                            <input type="date" name="check_out" id="modalCheckOut" value="{{ now()->addDays(2)->format('Y-m-d') }}" class="form-control" required onchange="updateModalPrice()">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-tag text-warning me-1"></i> Initial Status</label>
                            <select name="status" class="form-select" required>
                                @foreach ($statuses as $s)
                                    <option value="{{ $s }}" @selected($s === 'booked')>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="bi bi-card-text text-warning me-1"></i> Special Requests & Notes</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Airport shuttle, early check-in, extra bed, etc."></textarea>
                    </div>

                    <!-- Live Calculation Card -->
                    <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small">Estimated Duration:</span>
                                <strong id="modalNightsCount" class="text-white d-block">2 Nights</strong>
                            </div>
                            <div class="text-end">
                                <span class="text-muted small">Estimated Total Price:</span>
                                <strong id="modalEstimatedTotal" class="d-block fs-4 text-warning">$0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255, 255, 255, 0.08); padding: 1.25rem 1.75rem;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background: var(--ic-gold); color: #111416; font-weight: 600;">
                        <i class="bi bi-check-lg me-1"></i> Confirm & Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Hero Showcase Carousel
    var heroSlidesData = @json($heroSlides);
    var currentSlideIndex = 0;
    var showcaseHeroImage = document.getElementById('showcaseHeroImage');
    var showcaseTitle = document.getElementById('showcaseTitle');
    var showcaseDesc = document.getElementById('showcaseDesc');
    var dots = document.querySelectorAll('.icottage-dot');

    function switchShowcaseSlide(index) {
        if (!heroSlidesData[index]) return;
        currentSlideIndex = index;
        if (showcaseHeroImage) {
            showcaseHeroImage.style.opacity = '0.3';
            setTimeout(function () {
                showcaseHeroImage.src = heroSlidesData[index].image;
                showcaseHeroImage.style.opacity = '1';
            }, 180);
        }
        if (showcaseTitle) {
            showcaseTitle.innerHTML = heroSlidesData[index].title.replace(' ', '<br>');
        }
        if (showcaseDesc) {
            showcaseDesc.textContent = heroSlidesData[index].desc;
        }
        dots.forEach(function (d, i) {
            if (i === index) d.classList.add('active');
            else d.classList.remove('active');
        });
    }

    // Auto rotate every 8 seconds
    setInterval(function () {
        var next = (currentSlideIndex + 1) % heroSlidesData.length;
        switchShowcaseSlide(next);
    }, 8000);

    // View Switcher (Grid vs Table)
    function switchView(view) {
        var grid = document.getElementById('bookingsGridView');
        var table = document.getElementById('bookingsTableView');
        var btnGrid = document.getElementById('btnViewGrid');
        var btnTable = document.getElementById('btnViewTable');

        if (view === 'grid') {
            grid.classList.remove('d-none');
            table.classList.add('d-none');
            btnGrid.classList.add('active');
            btnTable.classList.remove('active');
        } else {
            grid.classList.add('d-none');
            table.classList.remove('d-none');
            btnTable.classList.add('active');
            btnGrid.classList.remove('active');
        }
    }

    // Real-time Modal Price Calculation
    function updateModalPrice() {
        var roomSelect = document.getElementById('modalRoomSelect');
        var checkInInput = document.getElementById('modalCheckIn');
        var checkOutInput = document.getElementById('modalCheckOut');
        var nightsSpan = document.getElementById('modalNightsCount');
        var totalSpan = document.getElementById('modalEstimatedTotal');

        if (!roomSelect || !checkInInput || !checkOutInput) return;

        var selectedOption = roomSelect.options[roomSelect.selectedIndex];
        var price = selectedOption ? parseFloat(selectedOption.getAttribute('data-price') || 0) : 0;

        var d1 = new Date(checkInInput.value);
        var d2 = new Date(checkOutInput.value);

        var nights = 1;
        if (d1 && d2 && d2 > d1) {
            var diffTime = Math.abs(d2 - d1);
            nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }

        nightsSpan.textContent = nights + (nights === 1 ? ' Night' : ' Nights');
        var total = nights * price;
        totalSpan.textContent = '$' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
</script>
@endsection
