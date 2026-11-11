@extends('layouts.app')
@section('title', 'Rooms')
@section('content')
@php
    $roomImageFor = fn ($room) => $roomImages[($room->image_key ?: (($room->room_id - 1) % count($roomImages) + 1)) - 1];
    $featuredRoom = $rooms->first();
    $featuredImage = $featuredRoom ? $roomImageFor($featuredRoom) : $roomImages[0];
    $editorialRoom = $rooms->get(1);
    $editorialImage = $editorialRoom ? $roomImageFor($editorialRoom) : null;
    $roomTotal = $rooms->total();
    $roomStart = $rooms->firstItem() ?? 0;
    $roomEnd = $rooms->lastItem() ?? 0;
@endphp

<style>
    .rooms-page {
        --rooms-gold: #d7b477;
        --rooms-muted: #a9b5b6;
        color: #e7e9e7;
        padding-bottom: 2rem;
    }

    .rooms-hero {
        position: relative;
        min-height: 440px;
        margin-bottom: 0;
        padding: 1.25rem 1.5rem;
        overflow: hidden;
        border-radius: 0;
    }
    .rooms-hero .hero-slides { z-index: -2; }
    .rooms-hero .hero-slides { overflow: hidden; }
    .rooms-hero-image { width: 100%; height: 100%; object-fit: cover; object-position: center; transition: opacity .25s ease; }
    .rooms-hero::after {
        z-index: -1;
        background:
            linear-gradient(180deg, rgba(7,17,20,.18), rgba(7,17,20,.12) 38%, rgba(7,17,20,.78) 100%);
    }
    .rooms-hero-top {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }
    .rooms-hero-nav { display: flex; gap: 1.1rem; }
    .rooms-hero-nav a {
        color: rgba(255,255,255,.82);
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-decoration: none;
        text-transform: uppercase;
    }
    .rooms-hero-nav a:hover { color: var(--rooms-gold); }
    .rooms-hero-copy {
        position: absolute;
        top: 50%;
        right: 1.5rem;
        left: 1.5rem;
        z-index: 1;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        gap: .85rem;
        transform: translateY(-38%);
        text-align: center;
    }
    .rooms-hero-copy .hero-kicker { color: #e2c38d; letter-spacing: .22em; }
    .rooms-hero-copy .hero-title {
        margin: 0;
        font-size: clamp(3rem, 7vw, 5.6rem);
        letter-spacing: .025em;
        line-height: .96;
        text-shadow: 0 3px 24px rgba(0,0,0,.4);
    }
    .rooms-hero-copy .hero-text { max-width: 47ch; margin: .55rem auto 0; color: rgba(255,255,255,.9); }
    .rooms-hero-cta { margin-top: .5rem; border-radius: 999px; padding: .62rem 1.5rem; font-size: .78rem; letter-spacing: .04em; }
    .rooms-data-strip {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem 3.5rem;
        margin-bottom: 0;
        padding: 1.1rem 1rem;
        border-bottom: 1px solid rgba(224,232,236,.12);
        background: rgba(8,10,10,.94);
    }
    .rooms-data-item { display: flex; align-items: baseline; gap: .45rem; color: #c9c4bb; font-size: .68rem; letter-spacing: .06em; }
    .rooms-data-item strong { color: #e2c38d; font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.25rem; font-weight: 500; }
    .rooms-photo-index {
        padding: 1.35rem clamp(1rem, 5vw, 4rem) 1.6rem;
        border-bottom: 1px solid rgba(224,232,236,.12);
        background: rgba(8,10,10,.94);
    }
    .rooms-photo-index-head {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
        max-width: 1080px;
        margin: 0 auto .8rem;
    }
    .rooms-photo-index-head span { color: #c8c0b3; font-size: .65rem; letter-spacing: .13em; text-transform: uppercase; }
    .rooms-photo-index-head small { color: var(--rooms-muted); font-size: .68rem; }
    .rooms-photo-list {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: .7rem;
        max-width: 1080px;
        margin: 0 auto;
    }
    .rooms-photo-choice {
        min-width: 0;
        padding: 0;
        overflow: hidden;
        border: 1px solid rgba(224,232,236,.14);
        border-radius: 4px;
        background: rgba(255,255,255,.025);
        color: #e7e2d9;
        text-align: left;
        cursor: pointer;
        transition: border-color .2s ease, transform .2s ease, background .2s ease;
    }
    .rooms-photo-choice:hover { transform: translateY(-3px); border-color: rgba(215,180,119,.75); }
    .rooms-photo-choice[aria-pressed="true"] { border-color: var(--rooms-gold); background: rgba(215,180,119,.1); }
    .rooms-photo-choice:focus-visible { outline: 2px solid var(--rooms-gold); outline-offset: 3px; }
    .rooms-photo-choice img { display: block; width: 100%; aspect-ratio: 1.45; object-fit: cover; transition: transform .4s ease; }
    .rooms-photo-choice:hover img { transform: scale(1.04); }
    .rooms-photo-choice-copy { display: grid; gap: .12rem; padding: .5rem .55rem .55rem; }
    .rooms-photo-choice-copy strong { overflow: hidden; color: #f0e5d2; font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1rem; font-weight: 500; text-overflow: ellipsis; white-space: nowrap; }
    .rooms-photo-choice-copy span { color: #a9b5b6; font-size: .6rem; letter-spacing: .08em; text-transform: uppercase; }

    .rooms-spotlight {
        display: grid;
        grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
        align-items: stretch;
        gap: clamp(1.5rem, 6vw, 5rem);
        margin: 0;
        padding: clamp(2rem, 6vw, 4.5rem) clamp(1rem, 5vw, 4rem);
        background: rgba(8,10,10,.94);
    }
    .rooms-spotlight-copy { max-width: 39rem; }
    .rooms-spotlight-copy { display: flex; flex-direction: column; justify-content: center; padding: .25rem; }
    .rooms-eyebrow {
        margin-bottom: .45rem;
        color: var(--rooms-gold);
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .14em;
        text-transform: uppercase;
    }
    .rooms-spotlight h2 {
        margin: 0 0 .45rem;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2.4rem, 4vw, 3.45rem);
        font-weight: 500;
        line-height: 1;
    }
    .rooms-spotlight-copy p { margin: 0; color: #c1cbca; font-size: .82rem; line-height: 1.8; }
    .rooms-spotlight-meta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .55rem; margin-top: 1.25rem; }
    .rooms-detail-chip {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        min-height: 48px;
        padding: .55rem .7rem;
        border: 1px solid rgba(224,232,236,.12);
        border-radius: 3px;
        background: rgba(255,255,255,.035);
        color: #dedbd4;
        font-size: .72rem;
    }
    .rooms-detail-chip i { color: var(--rooms-gold); font-size: .9rem; }
    .rooms-spotlight-actions { display: flex; align-items: center; flex-wrap: wrap; gap: .7rem; margin-top: 1.25rem; }
    .rooms-spotlight-edit {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .65rem .95rem;
        border: 1px solid rgba(224,232,236,.22);
        border-radius: 3px;
        color: #eee8dd;
        font-size: .72rem;
        text-decoration: none;
        transition: border-color .2s ease, background .2s ease, color .2s ease;
    }
    .rooms-spotlight-edit:hover { border-color: var(--rooms-gold); background: rgba(215,180,119,.1); color: #f3e7d2; }
    .rooms-spotlight-edit:focus-visible { outline: 2px solid var(--rooms-gold); outline-offset: 3px; }
    .rooms-spotlight-image {
        position: relative;
        min-height: 280px;
        overflow: hidden;
        border-radius: 2px;
        background: #17333a;
    }
    .rooms-spotlight-image img { display: block; width: 100%; height: 100%; min-height: 280px; object-fit: cover; transition: opacity .25s ease, transform .6s ease; }
    .rooms-spotlight-image.is-changing img { opacity: .4; }
    .rooms-spotlight-image:hover img { transform: scale(1.025); }
    .rooms-spotlight-image span {
        position: absolute;
        right: .75rem;
        bottom: .75rem;
        padding: .32rem .6rem;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 5px;
        background: rgba(8,14,16,.85);
        color: #fff;
        font-size: .7rem;
        backdrop-filter: blur(6px);
    }
    .rooms-spotlight--reverse { grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr); background: rgba(8,10,10,.94); }
    .rooms-spotlight--reverse .rooms-spotlight-image { grid-column: 1; grid-row: 1; }
    .rooms-spotlight--reverse .rooms-spotlight-copy { grid-column: 2; grid-row: 1; }
    .rooms-spotlight--reverse h2 { font-size: clamp(2rem, 3.6vw, 3rem); }
    .rooms-spotlight--reverse .rooms-spotlight-image span { display: none; }
    .rooms-closing-feature {
        display: grid;
        grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr);
        align-items: center;
        gap: clamp(1.5rem, 5vw, 4.5rem);
        padding: clamp(2rem, 5vw, 3.5rem) clamp(1rem, 5vw, 4rem);
        border-top: 1px solid rgba(224,232,236,.12);
        background: rgba(8,10,10,.94);
    }
    .rooms-closing-copy { max-width: 32rem; }
    .rooms-closing-copy h2 {
        margin: 0 0 .75rem;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2.2rem, 4vw, 3.35rem);
        font-weight: 500;
        line-height: 1;
    }
    .rooms-closing-copy p { margin: 0; color: #c1cbca; font-size: .82rem; line-height: 1.8; }
    .rooms-closing-copy .rooms-spotlight-actions { margin-top: 1.2rem; }
    .rooms-closing-photo { position: relative; min-height: 250px; overflow: hidden; border-radius: 3px; background: #17333a; }
    .rooms-closing-photo img { display: block; width: 100%; height: 100%; min-height: 250px; object-fit: cover; transition: transform .6s ease; }
    .rooms-closing-photo:hover img { transform: scale(1.025); }
    .rooms-closing-caption {
        position: absolute;
        right: .75rem;
        bottom: .75rem;
        left: .75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        padding: .7rem .8rem;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 3px;
        background: rgba(8,14,16,.76);
        color: #f2ece1;
        font-size: .7rem;
        backdrop-filter: blur(7px);
    }
    .rooms-closing-caption span:last-child { color: #e2c38d; white-space: nowrap; }

    .rooms-portfolio {
        padding: clamp(1.7rem, 4vw, 3rem) clamp(1rem, 4vw, 3rem);
        border-top: 1px solid rgba(224,232,236,.12);
        background: rgba(8,10,10,.94);
    }
    .rooms-section-heading {
        display: grid;
        grid-template-columns: 1fr;
        justify-items: center;
        gap: 1rem;
        margin-bottom: 1.4rem;
        text-align: center;
    }
    .rooms-section-heading > div { grid-column: 1; }
    .rooms-section-heading h2 {
        margin: 0;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2rem, 3.5vw, 2.7rem);
        font-weight: 500;
    }
    .rooms-section-heading p { margin: .35rem 0 0; color: var(--rooms-muted); font-size: .74rem; }
    .rooms-count { grid-column: 1; color: #c1cbca; font-size: .7rem; text-align: center; }

    .rooms-filter {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(150px, .75fr) minmax(150px, .65fr) auto;
        align-items: end;
        gap: .65rem;
        margin-bottom: .9rem;
        padding: .75rem;
        border: 1px solid rgba(224,232,236,.13);
        border-radius: 2px;
        background: rgba(255,255,255,.025);
    }
    .rooms-filter .form-label { margin-bottom: .3rem; color: #a9b5b6; font-size: .68rem; }
    .rooms-filter .search-wrap { position: relative; }
    .rooms-filter .search-wrap .bi {
        position: absolute;
        top: 50%;
        left: .75rem;
        color: #78878a;
        pointer-events: none;
        transform: translateY(-50%);
    }
    .rooms-filter .form-control,
    .rooms-filter .form-select {
        min-height: 38px;
        border-color: rgba(224,232,236,.22);
        background-color: rgba(255,255,255,.96);
        color: #273033;
        font-size: .77rem;
    }
    .rooms-filter .search-wrap .form-control { padding-left: 2.1rem; }
    .rooms-filter .form-control:focus,
    .rooms-filter .form-select:focus { border-color: #d7b477; box-shadow: 0 0 0 .18rem rgba(176,141,87,.2); }
    .rooms-filter-actions { display: flex; gap: .4rem; }
    .rooms-filter-actions .btn { min-height: 38px; padding-inline: .7rem; font-size: .74rem; white-space: nowrap; }
    .rooms-filter-actions .btn-primary { background: #17333a; border-color: #17333a; }
    .rooms-filter-actions .btn-primary:hover { background: #244750; }
    .rooms-filter-actions .btn-outline-secondary { border-color: rgba(255,255,255,.4); color: #e7e9e7; }

    .room-card-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.1rem; }
    .room-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid rgba(224,232,236,.1);
        border-radius: 3px;
        background: rgba(255,255,255,.025);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .room-card:hover { transform: translateY(-3px); border-color: rgba(208,170,112,.48); box-shadow: 0 12px 26px -16px rgba(0,0,0,.85); }
    .room-card-image { position: relative; height: clamp(155px, 18vw, 220px); overflow: hidden; background: #17333a; }
    .room-card-image img { display: block; width: 100%; height: 100%; object-fit: cover; transition: transform .45s ease; }
    .room-card:hover .room-card-image img { transform: scale(1.045); }
    .room-status {
        position: absolute;
        top: .55rem;
        left: .55rem;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .28rem .48rem;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 5px;
        background: rgba(8,14,16,.85);
        color: #fff;
        font-size: .62rem;
        backdrop-filter: blur(6px);
    }
    .room-status::before { width: 6px; height: 6px; border-radius: 50%; background: currentColor; content: ""; }
    .room-status--available { color: #9de0c0; }
    .room-status--occupied { color: #ffaaa3; }
    .room-status--maintenance { color: #f3d48f; }
    .room-card-number {
        position: absolute;
        right: .55rem;
        bottom: .55rem;
        padding: .28rem .48rem;
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 5px;
        background: rgba(8,14,16,.85);
        color: #fff;
        font-size: .7rem;
        font-weight: 600;
        backdrop-filter: blur(6px);
    }
    .room-card-body { padding: .85rem .75rem; }
    .room-card-title-row { display: flex; align-items: baseline; justify-content: space-between; gap: .4rem; }
    .room-card-body h3 {
        overflow: hidden;
        margin: 0;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.45rem;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .room-card-price { color: #e4c995; font-size: .69rem; font-weight: 600; white-space: nowrap; }
    .room-card-price small { color: #a9b5b6; font-size: .6rem; font-weight: 400; }
    .room-card-description {
        min-height: 2.3em;
        margin: .15rem 0 .6rem;
        color: #a9b5b6;
        font-size: .72rem;
        line-height: 1.5;
    }
    .room-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem .7rem;
        padding: .5rem 0;
        border-top: 1px solid rgba(224,232,236,.12);
        color: #d8dddb;
        font-size: .63rem;
    }
    .room-card-meta i { margin-right: .18rem; color: var(--rooms-gold); }
    .room-card-guest { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .room-card-actions { display: flex; align-items: center; justify-content: space-between; gap: .4rem; padding-top: .5rem; border-top: 1px solid rgba(224,232,236,.12); }
    .room-card-actions .btn { padding: .28rem .48rem; font-size: .66rem; }
    .room-details-button {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        border-color: rgba(215,180,119,.75);
        border-radius: 999px;
        background: transparent;
        color: #e8cb97;
        transition: background .2s ease, color .2s ease, transform .2s ease, box-shadow .2s ease;
    }
    .room-details-button:hover,
    .room-details-button[aria-pressed="true"] { border-color: var(--rooms-gold); background: var(--rooms-gold); color: #171612; }
    .room-details-button:hover { transform: translateY(-1px); }
    .room-details-button:focus-visible { box-shadow: 0 0 0 .2rem rgba(215,180,119,.35); }
    .room-card-actions .action-buttons { gap: .28rem; }
    .room-card-actions .icon-btn { width: 29px; height: 29px; }
    .rooms-empty {
        grid-column: 1 / -1;
        padding: 2.5rem 1rem;
        border: 1px dashed rgba(224,232,236,.2);
        border-radius: 8px;
        color: #a9b5b6;
        text-align: center;
    }
    .rooms-pagination { display: flex; justify-content: center; margin-top: 1rem; }

    @media (max-width: 991.98px) {
        .room-card-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .rooms-filter { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .rooms-filter-actions { grid-column: 1 / -1; }
        .rooms-filter-actions .btn { flex: 1; }
    }
    @media (max-width: 767.98px) {
        .rooms-hero { min-height: 380px; }
        .rooms-hero-copy { right: 1rem; left: 1rem; }
        .rooms-photo-list { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .rooms-photo-choice:nth-child(n+4) { display: none; }
        .rooms-spotlight { grid-template-columns: 1fr; }
        .rooms-spotlight-image { grid-row: 1; min-height: 220px; }
        .rooms-spotlight-image img { min-height: 220px; }
        .rooms-spotlight-meta { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .rooms-spotlight--reverse .rooms-spotlight-image,
        .rooms-spotlight--reverse .rooms-spotlight-copy { grid-column: 1; }
        .rooms-spotlight--reverse .rooms-spotlight-image { grid-row: 1; }
        .rooms-spotlight--reverse .rooms-spotlight-copy { grid-row: 2; }
        .rooms-closing-feature { grid-template-columns: 1fr; }
        .rooms-closing-copy { grid-row: 2; }
        .rooms-closing-photo { grid-row: 1; }
    }
    @media (max-width: 575.98px) {
        .rooms-hero { min-height: 350px; padding: 1rem; }
        .rooms-hero-top { align-items: flex-start; flex-direction: column; }
        .rooms-hero-nav { align-self: flex-end; }
        .rooms-hero-copy { right: .75rem; left: .75rem; }
        .rooms-hero-copy .hero-title { font-size: clamp(2.65rem, 13vw, 4rem); }
        .rooms-data-strip { gap: .6rem 1.25rem; padding: .9rem .5rem; }
        .rooms-data-item { font-size: .6rem; }
        .rooms-photo-index { padding-inline: .75rem; }
        .rooms-photo-index-head { align-items: flex-start; flex-direction: column; gap: .25rem; }
        .rooms-photo-list { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .5rem; }
        .rooms-photo-choice:nth-child(n+3) { display: none; }
        .rooms-photo-choice-copy { padding: .4rem; }
        .rooms-spotlight { padding: 1.6rem 1rem; }
        .rooms-spotlight-image,
        .rooms-spotlight-image img { min-height: 210px; }
        .rooms-spotlight-meta { grid-template-columns: 1fr; }
        .rooms-spotlight-actions { align-items: stretch; flex-direction: column; }
        .rooms-spotlight-actions .rooms-spotlight-edit { justify-content: center; }
        .rooms-closing-feature { gap: 1.25rem; padding: 1.6rem 1rem; }
        .rooms-closing-photo,
        .rooms-closing-photo img { min-height: 205px; }
        .rooms-portfolio { padding: 1.5rem .85rem; }
        .rooms-section-heading h2 { font-size: 1.8rem; }
        .rooms-filter { grid-template-columns: 1fr; }
        .rooms-filter-actions { grid-column: auto; }
        .room-card-grid { gap: .5rem; }
        .room-card-image { height: 130px; }
        .room-card-body { padding: .55rem; }
        .room-card-title-row { align-items: flex-start; flex-direction: column; gap: .1rem; }
        .room-card-body h3 { font-size: 1.08rem; }
        .room-card-description { font-size: .63rem; }
        .room-card-actions { align-items: flex-start; flex-direction: column; }
        .room-card-actions .action-buttons { align-self: flex-end; }
    }
    @media (prefers-reduced-motion: reduce) {
        .rooms-page *, .rooms-page *::before, .rooms-page *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<main class="rooms-page">
    <header class="page-hero photo-hero photo-hero--rooms rooms-hero reveal" style="--i:0">
        <div class="hero-slides" aria-hidden="true">
            <img class="rooms-hero-image" id="rooms-hero-image" src="{{ $featuredImage }}" alt="">
        </div>
        <div class="rooms-hero-top">
            <nav class="rooms-hero-nav" aria-label="Room page sections">
                <a href="#rooms-portfolio">Rooms</a>
                @if ($featuredRoom)<a href="#room-spotlight">Featured stay</a>@endif
            </nav>
            <a href="{{ route('rooms.create') }}" class="hero-btn hero-btn-primary"><i class="bi bi-plus-lg me-1"></i> Add a room</a>
        </div>
        <div class="rooms-hero-copy">
            <div>
                <div class="hero-kicker">An invitation to unwind</div>
                <h1 class="hero-title">Rooms &amp; suites</h1>
                <p class="hero-text">Thoughtfully appointed spaces, designed for slower mornings and more memorable stays.</p>
            </div>
            <a class="hero-btn hero-btn-primary rooms-hero-cta" href="#rooms-portfolio">Explore the collection <i class="bi bi-arrow-down ms-1"></i></a>
        </div>
    </header>

    <div class="rooms-data-strip" aria-label="Room inventory summary">
        <div class="rooms-data-item"><strong>{{ number_format($totalRoomCount) }}</strong> Rooms</div>
        <div class="rooms-data-item"><strong>{{ number_format($availableRoomCount) }}</strong> Available</div>
        <div class="rooms-data-item"><strong>{{ number_format($occupiedRoomCount) }}</strong> Occupied</div>
        <div class="rooms-data-item"><strong>{{ number_format($maintenanceRoomCount) }}</strong> In service</div>
    </div>

    @if ($rooms->isNotEmpty())
        <section class="rooms-photo-index" aria-label="Browse room photographs">
            <div class="rooms-photo-index-head">
                <span>Spaces to discover</span>
                <small>Select a photograph to explore that room</small>
            </div>
            <div class="rooms-photo-list">
                @foreach ($rooms->take(5) as $photoRoom)
                    @php $photoRoomImage = $roomImageFor($photoRoom); @endphp
                    <button
                        class="rooms-photo-choice"
                        type="button"
                        data-room-gallery-number="{{ $photoRoom->room_number }}"
                        aria-pressed="{{ $featuredRoom?->room_id === $photoRoom->room_id ? 'true' : 'false' }}"
                        aria-label="View {{ $photoRoom->roomType->type_name }}, room {{ $photoRoom->room_number }}"
                    >
                        <img src="{{ $photoRoomImage }}" alt="" loading="lazy">
                        <span class="rooms-photo-choice-copy">
                            <strong>{{ $photoRoom->roomType->type_name }}</strong>
                            <span>Room {{ $photoRoom->room_number }} · {{ ucfirst($photoRoom->status) }}</span>
                        </span>
                    </button>
                @endforeach
            </div>
        </section>
    @endif

    @if ($featuredRoom)
        @php
            $featuredGuest = $featuredRoom->bookings->first()?->guest;
            $featuredStatus = str_replace('_', ' ', $featuredRoom->status);
        @endphp
        <section class="rooms-spotlight reveal" id="room-spotlight" style="--i:1" aria-labelledby="room-spotlight-heading" aria-live="polite">
            <div class="rooms-spotlight-copy">
                <div class="rooms-eyebrow">Featured room · Room <span id="room-spotlight-number">{{ $featuredRoom->room_number }}</span></div>
                <h2 id="room-spotlight-heading" tabindex="-1">{{ $featuredRoom->roomType->type_name }}</h2>
                <p id="room-spotlight-description">{{ $featuredRoom->description ?: $featuredRoom->roomType->description ?: 'A welcoming room with considered details for a restful stay.' }}</p>
                <div class="rooms-spotlight-meta">
                    <span class="rooms-detail-chip"><i class="bi bi-currency-dollar"></i><span>$<span id="room-spotlight-price">{{ number_format($featuredRoom->roomType->price_per_night, 2) }}</span> / night</span></span>
                    <span class="rooms-detail-chip"><i class="bi bi-people"></i><span>Up to <span id="room-spotlight-capacity">{{ $featuredRoom->roomType->capacity }}</span> <span id="room-spotlight-capacity-label">{{ \Illuminate\Support\Str::plural('guest', $featuredRoom->roomType->capacity) }}</span></span></span>
                    <span class="rooms-detail-chip"><i class="bi bi-layers"></i><span>Floor <span id="room-spotlight-floor">{{ $featuredRoom->floor }}</span></span></span>
                    <span class="rooms-detail-chip"><i class="bi bi-door-open"></i><span id="room-spotlight-status">{{ ucfirst($featuredStatus) }}</span></span>
                    <span class="rooms-detail-chip" id="room-spotlight-guest-chip" @if (!$featuredGuest) hidden @endif><i class="bi bi-person"></i><span id="room-spotlight-guest">{{ $featuredGuest?->guest_name }}</span></span>
                </div>
                <div class="rooms-spotlight-actions">
                    <a class="hero-btn hero-btn-primary rooms-hero-cta" href="{{ route('rooms.edit', $featuredRoom) }}" id="room-spotlight-edit">Manage this room <i class="bi bi-arrow-up-right ms-1"></i></a>
                    <a class="rooms-spotlight-edit" href="{{ route('bookings.create', ['room_id' => $featuredRoom->room_id]) }}" id="room-spotlight-book"><i class="bi bi-calendar-plus"></i> Create a booking</a>
                </div>
            </div>
            <div class="rooms-spotlight-image">
                <img id="room-spotlight-image" src="{{ $featuredImage }}" alt="{{ $featuredRoom->roomType->type_name }} room {{ $featuredRoom->room_number }}">
                <span id="room-spotlight-image-status">{{ ucfirst($featuredStatus) }}</span>
            </div>
        </section>
    @endif

    @if ($editorialRoom)
        @php
            $editorialGuest = $editorialRoom->bookings->first()?->guest;
            $editorialDescription = $editorialRoom->description ?: $editorialRoom->roomType->description ?: 'Discover a calm, considered space made for a restorative stay.';
        @endphp
        <section class="rooms-spotlight rooms-spotlight--reverse reveal" style="--i:2" aria-labelledby="editorial-room-heading">
            <div class="rooms-spotlight-copy">
                <div class="rooms-eyebrow">A quieter kind of luxury · Room {{ $editorialRoom->room_number }}</div>
                <h2 id="editorial-room-heading">{{ $editorialRoom->roomType->type_name }}</h2>
                <p>{{ $editorialDescription }}</p>
                <div class="rooms-spotlight-meta">
                    <span class="rooms-detail-chip"><i class="bi bi-currency-dollar"></i>${{ number_format($editorialRoom->roomType->price_per_night, 2) }} / night</span>
                    <span class="rooms-detail-chip"><i class="bi bi-people"></i> {{ $editorialRoom->roomType->capacity }} {{ \Illuminate\Support\Str::plural('guest', $editorialRoom->roomType->capacity) }}</span>
                    <span class="rooms-detail-chip"><i class="bi bi-door-open"></i> {{ ucfirst($editorialRoom->status) }}</span>
                    @if ($editorialGuest)
                        <span class="rooms-detail-chip"><i class="bi bi-person"></i> {{ $editorialGuest->guest_name }}</span>
                    @endif
                </div>
            </div>
            <div class="rooms-spotlight-image">
                <img src="{{ $editorialImage }}" alt="{{ $editorialRoom->roomType->type_name }} room {{ $editorialRoom->room_number }}" loading="lazy">
            </div>
        </section>
    @endif

    @if ($editorialRoom)
        <section class="rooms-closing-feature reveal" style="--i:3" aria-labelledby="rooms-closing-heading">
            <div class="rooms-closing-copy">
                <div class="rooms-eyebrow">A stay made personal</div>
                <h2 id="rooms-closing-heading">Room to slow down and feel at home.</h2>
                <p>Every stay begins with the right space. Explore our collection to find a room that fits your plans, with thoughtful comforts, considered details, and room to make the visit your own.</p>
                <div class="rooms-spotlight-actions">
                    <a class="hero-btn hero-btn-primary rooms-hero-cta" href="#rooms-portfolio">Discover the rooms <i class="bi bi-arrow-down ms-1"></i></a>
                    <span class="rooms-spotlight-edit"><i class="bi bi-stars"></i> {{ number_format($totalRoomCount) }} spaces · {{ $roomTypes->count() }} room {{ \Illuminate\Support\Str::plural('type', $roomTypes->count()) }}</span>
                </div>
            </div>
            <div class="rooms-closing-photo">
                <img src="{{ $editorialImage }}" alt="{{ $editorialRoom->roomType->type_name }} interior, room {{ $editorialRoom->room_number }}" loading="lazy">
                <div class="rooms-closing-caption">
                    <span>{{ $editorialRoom->roomType->type_name }} · Room {{ $editorialRoom->room_number }}</span>
                    <span>${{ number_format($editorialRoom->roomType->price_per_night, 2) }} / night</span>
                </div>
            </div>
        </section>
    @endif

    <section class="rooms-portfolio reveal" id="rooms-portfolio" style="--i:4" aria-labelledby="rooms-portfolio-heading">
        <div class="rooms-section-heading">
            <div>
                <div class="rooms-eyebrow">Find your place to pause</div>
                <h2 id="rooms-portfolio-heading">The collection</h2>
                <p>Browse the rooms and suites in your hotel.</p>
            </div>
            <span class="rooms-count">Showing {{ $roomStart }}&ndash;{{ $roomEnd }} of {{ number_format($roomTotal) }}</span>
        </div>

        <form method="GET" action="{{ route('rooms.index') }}" class="rooms-filter" role="search">
            <div>
                <label for="room-search" class="form-label">Room number</label>
                <div class="search-wrap">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input id="room-search" type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search rooms">
                </div>
            </div>
            <div>
                <label for="room-type-filter" class="form-label">Room type</label>
                <select id="room-type-filter" name="room_type_id" class="form-select">
                    <option value="">All room types</option>
                    @foreach ($roomTypes as $type)
                        <option value="{{ $type->room_type_id }}" @selected(request('room_type_id') == $type->room_type_id)>{{ $type->type_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="room-status-filter" class="form-label">Availability</label>
                <select id="room-status-filter" name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="rooms-filter-actions">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
            </div>
        </form>

        <div class="room-card-grid">
            @forelse ($rooms as $room)
                @php
                    $image = $roomImageFor($room);
                    $activeGuest = $room->bookings->first()?->guest;
                    $description = $room->description ?: $room->roomType->description ?: 'A comfortable room with everything needed for a relaxing stay.';
                @endphp
                <article class="room-card">
                    <div class="room-card-image">
                        <img src="{{ $image }}" alt="{{ $room->roomType->type_name }} room {{ $room->room_number }}" loading="lazy">
                        <span class="room-status room-status--{{ $room->status }}">{{ ucfirst($room->status) }}</span>
                        <span class="room-card-number">Room {{ $room->room_number }}</span>
                    </div>
                    <div class="room-card-body">
                        <div class="room-card-title-row">
                            <h3>{{ $room->roomType->type_name }}</h3>
                            <span class="room-card-price">${{ number_format($room->roomType->price_per_night, 2) }}<small> / night</small></span>
                        </div>
                        <p class="room-card-description">{{ $description }}</p>
                        <div class="room-card-meta">
                            <span><i class="bi bi-layers"></i> Floor {{ $room->floor }}</span>
                            <span><i class="bi bi-people"></i> {{ $room->roomType->capacity }} {{ \Illuminate\Support\Str::plural('guest', $room->roomType->capacity) }}</span>
                            @if ($activeGuest)
                                <span class="room-card-guest"><i class="bi bi-person"></i> {{ $activeGuest->guest_name }}</span>
                            @endif
                        </div>
                        <div class="room-card-actions">
                            <button
                                class="btn btn-outline-light room-details-button"
                                type="button"
                                data-room-name="{{ $room->roomType->type_name }}"
                                data-room-number="{{ $room->room_number }}"
                                data-room-description="{{ $description }}"
                                data-room-price="{{ number_format($room->roomType->price_per_night, 2) }}"
                                data-room-capacity="{{ $room->roomType->capacity }}"
                                data-room-floor="{{ $room->floor }}"
                                data-room-status="{{ ucfirst(str_replace('_', ' ', $room->status)) }}"
                                data-room-guest="{{ $activeGuest?->guest_name ?? '' }}"
                                data-room-image="{{ $image }}"
                                data-room-edit-url="{{ route('rooms.edit', $room) }}"
                                data-room-booking-url="{{ route('bookings.create', ['room_id' => $room->room_id]) }}"
                                aria-pressed="{{ $featuredRoom?->room_id === $room->room_id ? 'true' : 'false' }}"
                                aria-controls="room-spotlight"
                            >View details <i class="bi bi-arrow-right ms-1"></i></button>
                            <span class="action-buttons">
                                <a href="{{ route('rooms.edit', $room) }}" class="icon-btn icon-btn-edit" title="Edit room" aria-label="Edit room {{ $room->room_number }}"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn icon-btn-delete" title="Delete room" aria-label="Delete room {{ $room->room_number }}"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rooms-empty">
                    <i class="bi bi-door-closed fs-4 d-block mb-2"></i>
                    No rooms match these filters.
                    @if (request()->hasAny(['search', 'room_type_id', 'status']))
                        <div class="mt-3"><a href="{{ route('rooms.index') }}" class="btn btn-outline-light btn-sm">Clear filters</a></div>
                    @else
                        <div class="mt-3"><a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm">Add your first room</a></div>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($rooms->hasPages())
            <div class="rooms-pagination">{{ $rooms->links() }}</div>
        @endif
    </section>
</main>
<script>
    (function () {
        var detailsButtons = document.querySelectorAll('.room-details-button');
        var galleryButtons = document.querySelectorAll('.rooms-photo-choice');
        var heroImage = document.getElementById('rooms-hero-image');
        var spotlightImage = document.getElementById('room-spotlight-image');
        var spotlight = document.getElementById('room-spotlight');

        if (!heroImage || !spotlightImage || !spotlight || detailsButtons.length === 0) return;

        var fields = {
            number: document.getElementById('room-spotlight-number'),
            name: document.getElementById('room-spotlight-heading'),
            description: document.getElementById('room-spotlight-description'),
            price: document.getElementById('room-spotlight-price'),
            capacity: document.getElementById('room-spotlight-capacity'),
            capacityLabel: document.getElementById('room-spotlight-capacity-label'),
            floor: document.getElementById('room-spotlight-floor'),
            status: document.getElementById('room-spotlight-status'),
            imageStatus: document.getElementById('room-spotlight-image-status'),
            guest: document.getElementById('room-spotlight-guest'),
            guestChip: document.getElementById('room-spotlight-guest-chip'),
            editLink: document.getElementById('room-spotlight-edit'),
            bookingLink: document.getElementById('room-spotlight-book')
        };

        detailsButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var room = button.dataset;

                fields.number.textContent = room.roomNumber;
                fields.name.textContent = room.roomName;
                fields.description.textContent = room.roomDescription;
                fields.price.textContent = room.roomPrice;
                fields.capacity.textContent = room.roomCapacity;
                fields.capacityLabel.textContent = Number(room.roomCapacity) === 1 ? 'guest' : 'guests';
                fields.floor.textContent = room.roomFloor;
                fields.status.textContent = room.roomStatus;
                fields.imageStatus.textContent = room.roomStatus;
                fields.guest.textContent = room.roomGuest;
                fields.guestChip.hidden = !room.roomGuest;
                fields.editLink.href = room.roomEditUrl;
                fields.bookingLink.href = room.roomBookingUrl;
                heroImage.src = room.roomImage;
                spotlightImage.closest('.rooms-spotlight-image').classList.add('is-changing');
                var finishImageChange = function () {
                    spotlightImage.closest('.rooms-spotlight-image').classList.remove('is-changing');
                };
                spotlightImage.onload = finishImageChange;
                spotlightImage.onerror = finishImageChange;
                spotlightImage.src = room.roomImage;
                spotlightImage.alt = room.roomName + ' room ' + room.roomNumber;

                detailsButtons.forEach(function (otherButton) {
                    otherButton.setAttribute('aria-pressed', String(otherButton === button));
                });

                spotlight.scrollIntoView({
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                    block: 'center'
                });
                document.getElementById('room-spotlight-heading').focus({ preventScroll: true });
            });
        });

        galleryButtons.forEach(function (galleryButton) {
            galleryButton.addEventListener('click', function () {
                var matchingButton = Array.from(detailsButtons).find(function (detailsButton) {
                    return detailsButton.dataset.roomNumber === galleryButton.dataset.roomGalleryNumber;
                });

                if (matchingButton) {
                    matchingButton.click();
                    galleryButtons.forEach(function (otherButton) {
                        otherButton.setAttribute('aria-pressed', String(otherButton === galleryButton));
                    });
                }
            });
        });
    })();
</script>
@endsection
