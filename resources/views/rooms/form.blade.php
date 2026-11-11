@extends('layouts.app')
@section('title', $room->exists ? 'Edit Room' : 'Add Room')
@section('content')
<div class="room-editor-page">
<header class="page-hero photo-hero photo-hero--rooms form-photo-hero edit-room-hero reveal">
    <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
    <div class="hero-top">
        <div class="edit-room-heading">
            <div class="hero-kicker">The art of a better stay</div>
            <div class="hero-rule"></div>
            <h1 class="hero-title">{{ $room->exists ? 'A room, refined.' : 'Make room for comfort.' }}</h1>
            <p class="hero-text">{{ $room->exists ? 'Thoughtful details make all the difference. Update this room’s details, atmosphere, and availability.' : 'Create a welcoming space with the right details, a considered photograph, and a place for every guest to unwind.' }}</p>
        </div>
        <div class="hero-actions"><a href="{{ route('rooms.index') }}" class="hero-btn hero-btn-ghost"><i class="bi bi-arrow-left me-1"></i> Back to rooms</a></div>
    </div>
    <div class="edit-room-hero-note"><i class="bi bi-flower1"></i> Comfort begins with the details</div>
</header>
<style>
    .room-editor-page { --editor-ink: #3b3028; --editor-muted: #786e65; --editor-line: #e8dfd3; --editor-brass: #a88355; }
    .edit-room-hero {
        min-height: 360px;
        margin-bottom: 0;
        padding: 2rem clamp(1.25rem, 5vw, 4rem);
        border: 1px solid rgba(255,255,255,.52);
        border-radius: 6px 6px 0 0;
        box-shadow: 0 22px 60px -38px rgba(0,0,0,.7);
    }
    .edit-room-hero::after {
        background:
            linear-gradient(180deg, rgba(247,241,231,.92) 0%, rgba(244,235,222,.83) 39%, rgba(35,25,18,.18) 100%),
            linear-gradient(90deg, rgba(243,234,220,.45), transparent 80%);
    }
    .edit-room-hero .hero-top { position: relative; z-index: 1; align-items: flex-start; }
    .edit-room-hero .edit-room-heading { max-width: 720px; padding-top: .8rem; }
    .edit-room-hero .hero-kicker { color: #806344; letter-spacing: .2em; }
    .edit-room-hero .hero-rule { background: var(--editor-brass); }
    .edit-room-hero .hero-title {
        max-width: 700px;
        color: #47372a;
        font-size: clamp(2.8rem, 6vw, 5.2rem);
        letter-spacing: -.025em;
        line-height: .96;
    }
    .edit-room-hero .hero-text { max-width: 50ch; color: #5e5145; font-size: .95rem; line-height: 1.7; }
    .edit-room-hero .hero-btn-ghost { border-color: rgba(71,55,42,.35); background: rgba(255,250,242,.5); color: #47372a; }
    .edit-room-hero .hero-btn-ghost:hover { border-color: #806344; background: #806344; color: #fffaf2; }
    .edit-room-hero .edit-room-hero-note {
        position: absolute;
        right: clamp(1.25rem, 5vw, 4rem);
        bottom: 1.25rem;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        color: #fffaf2;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.15rem;
        text-shadow: 0 2px 12px rgba(0,0,0,.55);
    }
    .edit-room-principles {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .75rem;
        padding: 1.2rem clamp(1rem, 3vw, 2rem);
        border: 1px solid rgba(232,223,211,.9);
        border-top: 0;
        background: rgba(250,246,239,.97);
        color: var(--editor-ink);
    }
    .edit-room-principle {
        display: grid;
        grid-template-columns: 3.15rem 1fr;
        align-items: center;
        gap: .75rem;
        min-width: 0;
        padding: .35rem .45rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background .25s ease, transform .25s ease;
    }
    .edit-room-principle:hover {
        background: rgba(255, 255, 255, .04);
        transform: translateY(-1px);
    }
    .edit-room-principle-thumb,
    .edit-room-principle-icon {
        position: relative;
        width: 3.15rem;
        height: 3.15rem;
        border-radius: 50%;
        border: 1.5px solid #d5c1a6;
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
        overflow: hidden;
        flex-shrink: 0;
        transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
    }
    .edit-room-principle:hover .edit-room-principle-thumb,
    .edit-room-principle:hover .edit-room-principle-icon {
        transform: scale(1.06);
        border-color: #886b49;
        box-shadow: 0 6px 16px rgba(0,0,0,.22);
    }
    .edit-room-principle-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .4s ease;
    }
    .edit-room-principle:hover .edit-room-principle-img {
        transform: scale(1.12);
    }
    .edit-room-principle-badge {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 1.15rem;
        height: 1.15rem;
        border-radius: 50%;
        background: #faf6ef;
        border: 1px solid #d5c1a6;
        color: #886b49;
        font-size: .6rem;
        display: grid;
        place-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,.2);
    }
    .edit-room-principle strong { display: block; color: #4a3a2d; font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.05rem; font-weight: 600; line-height: 1.2; }
    .edit-room-principle span { display: block; color: #786e65; font-size: .65rem; line-height: 1.4; margin-top: .15rem; }
    .room-editor-layout { align-items: flex-start !important; margin: 0; padding: clamp(1.25rem, 3.5vw, 2.75rem); background: rgba(250,246,239,.97); }
    .room-editor-layout > [class*="col-"] { display: flex; flex-direction: column; }
    .room-editor-card { height: 100%; overflow: hidden; border: 1px solid var(--editor-line); border-radius: 5px; background: #fffdf9; box-shadow: 0 16px 42px -32px rgba(53,38,25,.35); }
    .room-editor-card .card-body { padding: clamp(1.1rem, 2.5vw, 2rem) !important; }
    .room-editor-card h4 { color: var(--editor-ink); font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.8rem; font-weight: 600; }
    .room-editor-card .form-label { color: #584a3d; font-size: .8rem; font-weight: 600; }
    .room-editor-card .form-control,
    .room-editor-card .form-select { min-height: 43px; border-color: #ddd2c4; border-radius: 4px; background-color: #fff; color: #3b3028; font-size: .85rem; }
    .room-editor-card textarea.form-control { min-height: auto; line-height: 1.6; }
    .room-editor-card .form-control:focus,
    .room-editor-card .form-select:focus { border-color: #aa8a63; box-shadow: 0 0 0 .2rem rgba(168,131,85,.15); }
    .room-editor-card .btn-primary { border-color: #886b49; background: #886b49; }
    .room-editor-card .btn-primary:hover { border-color: #705439; background: #705439; }
    .room-editor-card .btn-outline-secondary { border-color: #c9bcad; color: #66594d; }
    .room-editor-card .btn-outline-secondary:hover { background: #eee7de; color: #43362b; }
    .room-image-section { margin: .25rem 0 1.25rem; padding: 1rem; border: 1px solid var(--editor-line); border-radius: 4px; background: #fbf8f2; }
    .room-image-section-head { display: flex; justify-content: space-between; gap: .75rem; align-items: baseline; margin-bottom: .75rem; }
    .room-image-section-head p { margin: 0; color: var(--editor-muted); font-size: .68rem; }
    .room-photo-count { flex: 0 0 auto; color: #806344; font-size: .68rem; }
    .room-photo-scroll-hint { display: flex; align-items: center; gap: .3rem; margin-top: .55rem; color: #85786a; font-size: .65rem; }
    .room-image-chooser {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: .5rem;
        max-height: 285px;
        overflow-y: auto;
        padding: .2rem .35rem .2rem .05rem;
        scrollbar-color: #b69a78 #eee7de;
        scrollbar-width: thin;
    }
    .room-image-chooser::-webkit-scrollbar { width: 7px; }
    .room-image-chooser::-webkit-scrollbar-track { border-radius: 6px; background: #eee7de; }
    .room-image-chooser::-webkit-scrollbar-thumb { border-radius: 6px; background: #b69a78; }
    .room-image-choice img { aspect-ratio: 1.45; }
    .room-preview-column { align-self: flex-start; }
    .room-type-preview {
        position: relative;
        isolation: isolate;
        height: min(520px, calc(100vh - 3rem));
        min-height: 400px;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
        padding: 1.5rem;
        border: 1px solid rgba(255,255,255,.7);
        border-radius: 5px;
        background: #8e7962;
        color: #fff;
        box-shadow: 0 16px 42px -32px rgba(53,38,25,.55);
    }
    .room-preview-column .room-type-preview { position: sticky; top: 1rem; }

    .room-type-preview img {
        position: absolute;
        inset: 0;
        z-index: -2;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .6s var(--ease);
    }

    .room-type-preview::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        background: linear-gradient(180deg, rgba(30,22,16,.02) 22%, rgba(30,22,16,.76) 100%);
    }

    .room-type-preview:hover img { transform: scale(1.04); }
    .room-type-preview-copy { width: 100%; text-shadow: 0 1px 8px rgba(0,0,0,.4); }
    .room-preview-kicker { display: block; margin-bottom: .35rem; color: #f3d9ae; font-size: .67rem; letter-spacing: .15em; text-transform: uppercase; }
    .room-type-preview-copy h2 {
        margin: .25rem 0 0;
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 3vw, 2.8rem);
        line-height: 1;
    }
    .room-preview-description { max-width: 34ch; margin: .55rem 0 0; color: rgba(255,255,255,.88); font-size: .76rem; line-height: 1.6; }
    .room-preview-meta { display: flex; flex-wrap: wrap; gap: .45rem; margin-top: .9rem; }
    .room-preview-meta span { padding: .3rem .5rem; border: 1px solid rgba(255,255,255,.32); border-radius: 3px; background: rgba(29,23,17,.36); color: #fff; font-size: .64rem; backdrop-filter: blur(5px); }
    .room-image-choice {
        position: relative;
        display: block;
        overflow: hidden;
        padding: 0;
        border: 2px solid transparent;
        border-radius: 5px;
        background: transparent;
        cursor: pointer;
    }
    .room-image-choice img { display: block; width: 100%; object-fit: cover; transition: transform .25s ease; }
    .room-image-choice:hover img { transform: scale(1.05); }
    .room-image-choice.is-selected { border-color: var(--brass); box-shadow: 0 0 0 2px rgba(176,141,87,.22); }
    .room-image-choice:focus-visible { outline: 2px solid var(--brass); outline-offset: 2px; }
    .room-image-choice span {
        position: absolute;
        right: .25rem;
        bottom: .25rem;
        padding: .12rem .35rem;
        border-radius: 3px;
        background: rgba(8,14,16,.82);
        color: #fff;
        font-size: .62rem;
    }

    @media (max-width: 991.98px) {
        .room-type-preview { position: relative !important; top: auto !important; height: 390px; min-height: 0; }
        .room-image-chooser { grid-template-columns: repeat(5, minmax(0, 1fr)); }
    }
    @media (max-width: 767.98px) {
        .edit-room-hero { min-height: 330px; }
        .edit-room-principles { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .room-type-preview { height: 340px; }
    }
    @media (max-width: 575.98px) {
        .edit-room-hero { min-height: 350px; padding: 1.25rem; }
        .edit-room-hero .hero-top { flex-direction: column; }
        .edit-room-hero .hero-title { font-size: clamp(2.7rem, 12vw, 3.8rem); }
        .edit-room-hero .edit-room-hero-note { right: 1rem; bottom: .8rem; font-size: 1rem; }
        .edit-room-principles { gap: .85rem .5rem; padding: 1rem .7rem; }
        .edit-room-principle { grid-template-columns: 2.5rem 1fr; gap: .5rem; padding: .2rem; }
        .edit-room-principle-thumb,
        .edit-room-principle-icon { width: 2.5rem; height: 2.5rem; }
        .edit-room-principle-badge { width: 1rem; height: 1rem; font-size: .52rem; }
        .edit-room-principle strong { font-size: .92rem; }
        .edit-room-principle span { font-size: .58rem; }
        .room-editor-layout { padding: 1rem .65rem; }
        .room-type-preview { height: 300px; }
        .room-image-chooser { grid-template-columns: repeat(4, minmax(0, 1fr)); max-height: 250px; }
        .room-image-section { padding: .7rem; }
    }
    .room-editor-page {
        --editor-ink: #f3e7d2;
        --editor-muted: #a9b5b6;
        --editor-line: rgba(224,232,236,.15);
        --editor-brass: #d7b477;
    }
    .edit-room-hero { border-color: rgba(224,232,236,.16); }
    .edit-room-hero::after {
        background:
            linear-gradient(180deg, rgba(8,14,16,.52), rgba(8,14,16,.37) 39%, rgba(8,14,16,.76)),
            linear-gradient(90deg, rgba(8,14,16,.58), transparent 85%);
    }
    .edit-room-hero .hero-kicker { color: #d7b477; }
    .edit-room-hero .hero-title { color: #fff; }
    .edit-room-hero .hero-text { color: rgba(255,255,255,.86); }
    .edit-room-hero .hero-btn-ghost { border-color: rgba(255,255,255,.42); background: rgba(8,14,16,.38); color: #fff; }
    .edit-room-hero .hero-btn-ghost:hover { border-color: #d7b477; background: #d7b477; color: #0f2429; }
    .edit-room-principles,
    .room-editor-layout { border-color: rgba(224,232,236,.14); background: rgba(8,14,16,.94); }
    .edit-room-principle:hover { background: rgba(255,255,255,.03); }
    .edit-room-principle-thumb,
    .edit-room-principle-icon { border-color: rgba(215,180,119,.55); box-shadow: 0 4px 14px rgba(0,0,0,.45); }
    .edit-room-principle:hover .edit-room-principle-thumb,
    .edit-room-principle:hover .edit-room-principle-icon { border-color: #f3e7d2; box-shadow: 0 6px 20px rgba(215,180,119,.35); }
    .edit-room-principle-badge { background: rgba(8,14,16,.92); border-color: #d7b477; color: #d7b477; box-shadow: 0 2px 6px rgba(0,0,0,.6); }
    .edit-room-principle strong { color: #f3e7d2; }
    .edit-room-principle span { color: #a9b5b6; }
    .room-editor-card { border-color: rgba(224,232,236,.15); background: rgba(15,24,25,.98); box-shadow: 0 16px 42px -32px rgba(0,0,0,.7); }
    .room-editor-card .text-muted { color: #a9b5b6 !important; }
    .room-editor-card .form-label { color: #e7e9e7; }
    .room-editor-card .form-control,
    .room-editor-card .form-select { border-color: rgba(224,232,236,.25); background-color: #f8f7f4; color: #273033; }
    .room-editor-card .form-control:focus,
    .room-editor-card .form-select:focus { border-color: #d7b477; box-shadow: 0 0 0 .2rem rgba(215,180,119,.2); }
    .room-editor-card .btn-primary { border-color: #b08d57; background: #b08d57; color: #0f2429; }
    .room-editor-card .btn-primary:hover { border-color: #c19d66; background: #c19d66; color: #0f2429; }
    .room-editor-card .btn-outline-secondary { border-color: rgba(255,255,255,.36); color: #e7e9e7; }
    .room-editor-card .btn-outline-secondary:hover { background: rgba(255,255,255,.12); color: #fff; }
    .room-image-section { border-color: rgba(224,232,236,.15); background: rgba(255,255,255,.035); }
    .room-image-section-head p,
    .room-photo-scroll-hint { color: #a9b5b6; }
    .room-photo-count,
    .room-preview-kicker { color: #e2c38d; }
    .room-image-chooser { scrollbar-color: #b08d57 rgba(255,255,255,.08); }
    .room-image-chooser::-webkit-scrollbar-track { background: rgba(255,255,255,.08); }
    .room-image-chooser::-webkit-scrollbar-thumb { background: #b08d57; }
    .room-type-preview { border-color: rgba(255,255,255,.24); background: #0f2429; box-shadow: 0 16px 42px -32px rgba(0,0,0,.7); }
    #principlePreviewModal {
        z-index: 1065 !important;
    }
    #principlePreviewModal .modal-dialog {
        z-index: 1066 !important;
        max-width: 520px;
    }
    .modal-backdrop {
        z-index: 1050 !important;
    }
    .principle-modal-close-btn {
        position: absolute;
        top: .85rem;
        right: .85rem;
        z-index: 20;
        width: 2.25rem;
        height: 2.25rem;
        padding: 0;
        border-radius: 50%;
        background: rgba(8,14,16,.85);
        border: 1px solid rgba(215,180,119,.6);
        color: #f3e7d2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .95rem;
        cursor: pointer;
        backdrop-filter: blur(4px);
        transition: all .2s ease;
    }
    .principle-modal-close-btn:hover {
        background: #d7b477;
        color: #0f2429;
        border-color: #d7b477;
        transform: scale(1.08);
    }
    .principle-modal-back-btn {
        border-color: rgba(215,180,119,.45) !important;
        color: #d7b477 !important;
        background: rgba(215,180,119,.08) !important;
        transition: all .2s ease !important;
    }
    .principle-modal-back-btn:hover {
        background: #d7b477 !important;
        color: #0f2429 !important;
        border-color: #d7b477 !important;
    }
</style>

<section class="edit-room-principles" aria-label="Room design principles">
    <div class="edit-room-principle" title="Restful comfort — Made for a better night’s sleep" data-principle-kicker="Rest & Recovery" data-principle-desc="Every bed is curated with premium breathable linens, layered down-alternative pillows, and pressure-relieving mattresses to guarantee profound sleep and restorative mornings.">
        <div class="edit-room-principle-thumb edit-room-principle-icon">
            <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=600&q=80" alt="Restful comfort" class="edit-room-principle-img" loading="lazy">
            <span class="edit-room-principle-badge" aria-hidden="true"><i class="bi bi-moon-stars"></i></span>
        </div>
        <div>
            <strong>Restful comfort</strong>
            <span>Made for a better night’s sleep</span>
        </div>
    </div>
    <div class="edit-room-principle" title="Gentle lighting — Set the mood for every moment" data-principle-kicker="Illumination & Mood" data-principle-desc="Warm amber bedside sconces and layered ambient fixtures let guests sculpt the visual warmth from morning clarity to relaxing evening unwinding.">
        <div class="edit-room-principle-thumb edit-room-principle-icon">
            <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=600&q=80" alt="Gentle lighting" class="edit-room-principle-img" loading="lazy">
            <span class="edit-room-principle-badge" aria-hidden="true"><i class="bi bi-brightness-high"></i></span>
        </div>
        <div>
            <strong>Gentle lighting</strong>
            <span>Set the mood for every moment</span>
        </div>
    </div>
    <div class="edit-room-principle" title="Calm atmosphere — A quiet place to recharge" data-principle-kicker="Sanctuary & Serenity" data-principle-desc="Neutral earth tones, sound-dampening architectural textures, and calming spatial arrangements cultivate an oasis of peaceful solitude.">
        <div class="edit-room-principle-thumb edit-room-principle-icon">
            <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=600&q=80" alt="Calm atmosphere" class="edit-room-principle-img" loading="lazy">
            <span class="edit-room-principle-badge" aria-hidden="true"><i class="bi bi-leaf"></i></span>
        </div>
        <div>
            <strong>Calm atmosphere</strong>
            <span>A quiet place to recharge</span>
        </div>
    </div>
    <div class="edit-room-principle" title="Thoughtful details — Small touches, memorable stays" data-principle-kicker="Artisanal Touches" data-principle-desc="From handcrafted ceramics and welcome amenities to bespoke room scents, every tactile touchpoint is designed to leave a lasting impression.">
        <div class="edit-room-principle-thumb edit-room-principle-icon">
            <img src="https://images.unsplash.com/photo-1615874959474-d609969a20ed?auto=format&fit=crop&w=600&q=80" alt="Thoughtful details" class="edit-room-principle-img" loading="lazy">
            <span class="edit-room-principle-badge" aria-hidden="true"><i class="bi bi-stars"></i></span>
        </div>
        <div>
            <strong>Thoughtful details</strong>
            <span>Small touches, memorable stays</span>
        </div>
    </div>
</section>

<div class="modal fade" id="principlePreviewModal" tabindex="-1" aria-labelledby="principleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(12,20,22,.98); border: 1px solid rgba(215,180,119,.35); color: #f3e7d2; border-radius: 8px; overflow: hidden; box-shadow: 0 24px 60px rgba(0,0,0,.85);">
            <div class="modal-body p-0">
                <div style="position: relative; max-height: 380px; overflow: hidden;">
                    <img id="principleModalImg" src="" alt="" style="width: 100%; height: 320px; object-fit: cover; display: block;">
                    <button type="button" class="principle-modal-close-btn" data-bs-dismiss="modal" aria-label="Close" title="Close"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="p-4">
                    <span id="principleModalKicker" style="color: #d7b477; font-size: .72rem; letter-spacing: .15em; text-transform: uppercase; font-weight: 600;">Room Design Principle</span>
                    <h4 id="principleModalTitle" style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.85rem; margin: .35rem 0 .25rem; color: #fff;"></h4>
                    <p id="principleModalSubtitle" style="color: #d7b477; font-size: .9rem; font-weight: 500; margin-bottom: .6rem;"></p>
                    <p id="principleModalDesc" style="color: #a9b5b6; font-size: .82rem; line-height: 1.6; margin: 0;"></p>

                    <div class="mt-4 pt-3 d-flex justify-content-between align-items-center" style="border-top: 1px solid rgba(224,232,236,.12);">
                        <span style="color: #788688; font-size: .75rem;"><i class="bi bi-info-circle me-1"></i> Press Esc or click outside to close</span>
                        <button type="button" class="btn btn-sm principle-modal-back-btn px-3" data-bs-dismiss="modal">
                            <i class="bi bi-arrow-left me-1"></i> Back to room
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 justify-content-center align-items-start room-editor-layout">
<div class="col-lg-7">
<div class="card room-editor-card reveal" style="--i:1"><div class="card-body">
    <h4 class="mb-1">{{ $room->exists ? 'Room details' : 'Create your room' }}</h4>
    <p class="text-muted small mb-4">Give this space its identity, set the details, and choose the photograph guests will see.</p>
    <form method="POST" action="{{ $room->exists ? route('rooms.update', $room) : route('rooms.store') }}">
        @csrf
        @if ($room->exists) @method('PUT') @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Room Number</label>
                <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}" class="form-control @error('room_number') is-invalid @enderror" required>
                @error('room_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Floor</label>
                <input type="number" min="0" name="floor" value="{{ old('floor', $room->floor) }}" class="form-control @error('floor') is-invalid @enderror" required>
                @error('floor') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Room Type</label>
                <select id="room_type_id" name="room_type_id" class="form-select @error('room_type_id') is-invalid @enderror" required>
                    <option value="">-- Select Room Type --</option>
                    @foreach ($roomTypes as $t)
                        <option value="{{ $t->room_type_id }}" data-room-price="{{ number_format($t->price_per_night, 2) }}" @selected(old('room_type_id', $room->room_type_id) == $t->room_type_id)>{{ $t->type_name }} (${{ number_format($t->price_per_night, 2) }})</option>
                    @endforeach
                </select>
                @error('room_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected(old('status', $room->status) == $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        @php
            $defaultImageKey = $room->image_key ?: (($room->room_id ? ($room->room_id - 1) : 0) % count($roomImages) + 1);
            $requestedImageKey = (int) old('image_key', $defaultImageKey);
            $selectedImageKey = isset($roomImages[$requestedImageKey - 1]) ? $requestedImageKey : $defaultImageKey;
        @endphp
        <div class="room-image-section">
            <div class="room-image-section-head">
                <div>
                    <label class="form-label mb-1" for="room-image-key">Choose the room photograph</label>
                    <p>Select the image that best captures this room.</p>
                </div>
                <span class="room-photo-count">{{ count($roomImages) }} photographs</span>
            </div>
            <input id="room-image-key" type="hidden" name="image_key" value="{{ $selectedImageKey }}">
            <div class="room-image-chooser" role="group" aria-label="Choose a room photograph">
                @foreach ($roomImages as $key => $roomImage)
                    @php $imageKey = $key + 1; @endphp
                    <button
                        class="room-image-choice {{ $selectedImageKey === $imageKey ? 'is-selected' : '' }}"
                        type="button"
                        data-image-key="{{ $imageKey }}"
                        data-image-url="{{ $roomImage }}"
                        aria-label="Choose room photograph {{ $imageKey }}"
                        aria-pressed="{{ $selectedImageKey === $imageKey ? 'true' : 'false' }}"
                    >
                        <img src="{{ $roomImage }}" alt="" loading="lazy">
                        <span>{{ $imageKey }}</span>
                    </button>
                @endforeach
            </div>
            <div class="room-photo-scroll-hint"><i class="bi bi-arrows-vertical"></i> Scroll inside the gallery to browse all photographs</div>
            @error('image_key') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description', $room->description) }}</textarea>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-1"></i>{{ $room->exists ? 'Save room details' : 'Create room' }}</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Cancel</a>
        </div>
    </form>
</div></div>
</div>
<div class="col-lg-5 room-preview-column reveal" style="--i:2">
    <aside class="room-type-preview" aria-live="polite" aria-label="Live room preview">
        <img id="roomTypePreviewImage" src="{{ $roomImages[$selectedImageKey - 1] }}" alt="" aria-hidden="true">
        <div class="room-type-preview-copy">
            <span class="room-preview-kicker">Your room preview</span>
            <h2 id="roomTypePreviewName">{{ $roomTypes->firstWhere('room_type_id', old('room_type_id', $room->room_type_id))?->type_name ?? 'Choose a room type' }}</h2>
            <p class="room-preview-description">A welcoming space, thoughtfully prepared for a more comfortable and memorable stay.</p>
            <div class="room-preview-meta">
                <span id="roomPreviewNumber">Room {{ old('room_number', $room->room_number) ?: 'not set' }}</span>
                <span id="roomPreviewStatus">{{ ucfirst(old('status', $room->status ?: 'available')) }}</span>
                <span id="roomPreviewPrice">${{ number_format((float) ($roomTypes->firstWhere('room_type_id', old('room_type_id', $room->room_type_id))?->price_per_night ?? 0), 2) }} / night</span>
            </div>
        </div>
    </aside>
</div>
</div>
</div>

<script>
    (function () {
        var select = document.getElementById('room_type_id');
        var image = document.getElementById('roomTypePreviewImage');
        var name = document.getElementById('roomTypePreviewName');
        var imageKeyInput = document.getElementById('room-image-key');
        var imageChoices = document.querySelectorAll('.room-image-choice');
        var roomNumber = document.querySelector('[name="room_number"]');
        var statusSelect = document.querySelector('[name="status"]');
        var roomPreviewNumber = document.getElementById('roomPreviewNumber');
        var roomPreviewStatus = document.getElementById('roomPreviewStatus');
        var roomPreviewPrice = document.getElementById('roomPreviewPrice');

        function updateRoomTypePreview() {
            var selectedOption = select.options[select.selectedIndex];
            var roomTypeName = selectedOption && selectedOption.value ? selectedOption.text.trim() : '';

            name.textContent = roomTypeName || 'Choose a room type';
            roomPreviewPrice.textContent = selectedOption && selectedOption.dataset.roomPrice
                ? '$' + selectedOption.dataset.roomPrice + ' / night'
                : 'Select a room type';
        }

        function updateRoomNumberPreview() {
            roomPreviewNumber.textContent = 'Room ' + (roomNumber.value.trim() || 'not set');
        }

        function updateRoomStatusPreview() {
            roomPreviewStatus.textContent = statusSelect.options[statusSelect.selectedIndex].text;
        }

        imageChoices.forEach(function (choice) {
            choice.addEventListener('click', function () {
                imageKeyInput.value = choice.dataset.imageKey;
                image.src = choice.dataset.imageUrl;
                imageChoices.forEach(function (otherChoice) {
                    var isSelected = otherChoice === choice;
                    otherChoice.classList.toggle('is-selected', isSelected);
                    otherChoice.setAttribute('aria-pressed', String(isSelected));
                });
            });
        });

        select.addEventListener('change', updateRoomTypePreview);
        roomNumber.addEventListener('input', updateRoomNumberPreview);
        statusSelect.addEventListener('change', updateRoomStatusPreview);
        updateRoomTypePreview();

        var principleItems = document.querySelectorAll('.edit-room-principle');
        var principleModalEl = document.getElementById('principlePreviewModal');
        if (principleModalEl) {
            // Relocate modal directly to document.body to prevent any container stacking-context issues
            if (principleModalEl.parentNode !== document.body) {
                document.body.appendChild(principleModalEl);
            }

            function dismissPrincipleModal() {
                if (window.bootstrap && window.bootstrap.Modal) {
                    var inst = bootstrap.Modal.getInstance(principleModalEl);
                    if (inst) {
                        try { inst.hide(); } catch (err) {}
                    }
                }
                principleModalEl.classList.remove('show');
                principleModalEl.style.display = 'none';
                principleModalEl.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
            }

            // Bind all close/back buttons
            principleModalEl.querySelectorAll('.principle-modal-close-btn, .principle-modal-back-btn, [data-bs-dismiss="modal"]').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    dismissPrincipleModal();
                });
            });

            // Dismiss when clicking backdrop area (outside modal-dialog)
            principleModalEl.addEventListener('click', function (e) {
                if (e.target === principleModalEl) {
                    dismissPrincipleModal();
                }
            });

            // Dismiss on Esc
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && (principleModalEl.classList.contains('show') || principleModalEl.style.display === 'block')) {
                    dismissPrincipleModal();
                }
            });

            principleItems.forEach(function (principle) {
                principle.addEventListener('click', function () {
                    var img = principle.querySelector('.edit-room-principle-img');
                    var strong = principle.querySelector('strong');
                    var span = principle.querySelector('span');
                    var kicker = principle.dataset.principleKicker || 'Room Design Principle';
                    var desc = principle.dataset.principleDesc || '';

                    var modalImg = document.getElementById('principleModalImg');
                    var modalTitle = document.getElementById('principleModalTitle');
                    var modalSub = document.getElementById('principleModalSubtitle');
                    var modalKicker = document.getElementById('principleModalKicker');
                    var modalDesc = document.getElementById('principleModalDesc');

                    if (modalImg && img) { modalImg.src = img.src; modalImg.alt = img.alt || ''; }
                    if (modalTitle && strong) { modalTitle.textContent = strong.textContent.trim(); }
                    if (modalSub && span) { modalSub.textContent = span.textContent.trim(); }
                    if (modalKicker) { modalKicker.textContent = kicker; }
                    if (modalDesc) { modalDesc.textContent = desc; }

                    if (window.bootstrap && window.bootstrap.Modal) {
                        bootstrap.Modal.getOrCreateInstance(principleModalEl).show();
                    } else {
                        principleModalEl.classList.add('show');
                        principleModalEl.style.display = 'block';
                        document.body.classList.add('modal-open');
                    }
                });
            });
        }
    })();
</script>
@endsection
