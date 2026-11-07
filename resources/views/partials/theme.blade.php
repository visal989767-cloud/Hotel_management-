<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Figtree:wght@400;500;600&display=swap');

    :root {
        --pine: #17333a;
        --pine-deep: #0f2429;
        --brass: #b08d57;
        --brass-dark: #8f7142;
        --ivory: #fbfaf8;
        --ink: #1f2a2e;
        --muted: #6b7b7f;
        --line: #dfe5e4;
        --ease: cubic-bezier(.22, .8, .24, 1);
    }

    body {
        background: var(--ivory) !important;
        font-family: 'Figtree', system-ui, sans-serif;
        color: var(--ink);
    }

    body:not(.cinematic-dashboard) {
        position: relative;
        isolation: isolate;
        background: transparent !important;
    }

    body:not(.cinematic-dashboard)::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background:
            linear-gradient(180deg, rgba(6,14,16,.48), rgba(6,14,16,.7)),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=85') center / cover no-repeat;
    }

    body:not(.cinematic-dashboard) > .navbar,
    body:not(.cinematic-dashboard) > .container {
        position: relative;
        z-index: 1;
    }

    .navbar {
        background: var(--pine-deep) !important;
        border-bottom: 2px solid var(--brass);
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
    .navbar .navbar-brand i { color: var(--brass); }

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
        background: var(--brass);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .3s var(--ease);
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
        background: var(--brass);
        border-color: var(--brass);
        color: var(--pine-deep);
        transform: translateY(-1px);
    }

    .navbar .navbar-toggler { border-color: rgba(255,255,255,.35); }
    .navbar .navbar-toggler:focus { box-shadow: 0 0 0 .2rem rgba(176,141,87,.35); }
    .nav-account { display: flex; align-items: center; gap: 1rem; }
    .nav-account .navbar-text { color: rgba(255,255,255,.8); }

    .page-hero {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        min-height: 260px;
        margin-bottom: 1.75rem;
        padding: 2.25rem 2.5rem 1.75rem;
        border-radius: 22px;
        color: #fff;
        background:
            linear-gradient(90deg, rgba(15,36,41,.78), rgba(15,36,41,.48)),
            url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1800&q=80') center / cover;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 2rem;
        box-shadow: 0 24px 48px -28px rgba(15,36,41,.65);
    }

    .page-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        background:
            linear-gradient(90deg, rgba(15,36,41,.88) 0%, rgba(15,36,41,.55) 55%, rgba(15,36,41,.35) 100%),
            linear-gradient(180deg, rgba(15,36,41,.1) 0%, rgba(15,36,41,.7) 100%);
    }

    .hero-slides { position: absolute; inset: 0; z-index: -2; background: var(--pine); }

    .hero-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        will-change: opacity, transform;
        animation: themeHeroFade 21s ease-in-out infinite, themeHeroZoom 21s linear infinite;
    }

    .photo-hero--rooms .hero-slide:nth-child(1) {
        background-image: url('https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1800&q=80');
    }

    .photo-hero--rooms .hero-slide:nth-child(2) { background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1800&q=80'); }
    .photo-hero--rooms .hero-slide:nth-child(3) { background-image: url('https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1800&q=80'); }
    .photo-hero--guests .hero-slide:nth-child(1) { background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1800&q=80'); }
    .photo-hero--guests .hero-slide:nth-child(2) { background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1800&q=80'); }
    .photo-hero--guests .hero-slide:nth-child(3) { background-image: url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1800&q=80'); }
    .photo-hero--bookings .hero-slide:nth-child(1) { background-image: url('https://images.unsplash.com/photo-1564501049412-61c2a3083791?auto=format&fit=crop&w=1800&q=80'); }
    .photo-hero--bookings .hero-slide:nth-child(2) { background-image: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1800&q=80'); }
    .photo-hero--bookings .hero-slide:nth-child(3) { background-image: url('https://images.unsplash.com/photo-1563911302283-d2bc129e7570?auto=format&fit=crop&w=1800&q=80'); }

    .hero-slide:nth-child(2) { animation-delay: 7s; }
    .hero-slide:nth-child(3) { animation-delay: 14s; }
    .form-photo-hero { min-height: 220px; }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .hero-kicker {
        margin-bottom: .6rem;
        color: var(--brass);
        font-size: .8rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .hero-rule {
        width: 56px;
        height: 2px;
        margin-bottom: 1.1rem;
        background: var(--brass);
    }

    .hero-title {
        margin: 0 0 .75rem;
        color: #fff;
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2.2rem, 3.6vw, 3.3rem);
        font-weight: 500;
        line-height: 1.08;
    }

    .hero-text { max-width: 44ch; margin: 0; color: rgba(255,255,255,.82); }
    .hero-actions { display: flex; gap: .65rem; flex-wrap: wrap; }

    .hero-btn {
        position: relative;
        overflow: hidden;
        display: inline-block;
        padding: .7rem 1.25rem;
        border-radius: 6px;
        font-size: .95rem;
        font-weight: 600;
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
        background: linear-gradient(100deg, transparent, rgba(255,255,255,.3), transparent);
        transform: skewX(-20deg);
        transition: left .65s var(--ease);
    }

    .hero-btn:hover,
    .hero-btn:focus-visible { transform: translateY(-2px); }
    .hero-btn:hover::after,
    .hero-btn:focus-visible::after { left: 130%; }
    .hero-btn:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }

    .hero-btn-primary {
        background: var(--brass);
        border: 1px solid var(--brass);
        color: var(--pine-deep);
    }

    .hero-btn-primary:hover,
    .hero-btn-primary:focus-visible { background: #c19d66; border-color: #c19d66; color: var(--pine-deep); }

    .hero-btn-ghost {
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.35);
        color: #fff;
    }

    .hero-btn-ghost:hover,
    .hero-btn-ghost:focus-visible { background: rgba(255,255,255,.2); color: #fff; }

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
        border: 1px solid rgba(255,255,255,.22);
        border-radius: 6px;
        background: rgba(255,255,255,.12);
        display: flex;
        flex-direction: column;
    }

    .hero-chip strong {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 600;
        line-height: 1.1;
    }

    .hero-chip span { color: rgba(255,255,255,.8); font-size: .85rem; }
    .hero-dots { display: flex; gap: .5rem; }
    .hero-dots span { position: relative; height: 3px; width: 28px; overflow: hidden; border-radius: 2px; background: rgba(255,255,255,.3); }
    .hero-dots span::after {
        content: "";
        position: absolute;
        inset: 0;
        background: var(--brass);
        transform: scaleX(0);
        transform-origin: left;
        animation: themeDotFill 21s linear infinite;
    }
    .hero-dots span:nth-child(2)::after { animation-delay: 7s; }
    .hero-dots span:nth-child(3)::after { animation-delay: 14s; }

    .panel-card {
        overflow: hidden;
        border: 1px solid rgba(208,170,112,.24);
        border-radius: 18px;
        background: rgba(8,14,16,.76);
        color: #f5f2ed;
        box-shadow: 0 18px 50px -35px rgba(0,0,0,.8);
        backdrop-filter: blur(8px);
        transition: box-shadow .25s ease, border-color .25s ease, transform .25s var(--ease);
    }

    .panel-card:hover { border-color: rgba(208,170,112,.42); box-shadow: 0 16px 34px -26px rgba(0,0,0,.7); }
    .panel-card-head {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid rgba(224,232,236,.15);
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .panel-card-head small { float: right; color: #c1cbca; font: 400 .85rem 'Figtree', system-ui, sans-serif; }
    .themed-table { margin-bottom: 0; --bs-table-bg: transparent; --bs-table-color: #e7e9e7; --bs-table-hover-color: #fff; --bs-table-hover-bg: rgba(196,154,90,.08); }
    .themed-table thead th { padding: .8rem 1rem; border-bottom: 1px solid rgba(224,232,236,.15); background: rgba(255,255,255,.045); color: #c0b49f; font-size: .85rem; font-weight: 500; white-space: nowrap; }
    .themed-table tbody td { padding: .95rem 1rem; border-color: rgba(224,232,236,.15); color: #e7e9e7; transition: background-color .2s ease; }
    .themed-table tbody tr:last-child td { border-bottom: 0; }
    .empty-state { padding: 3rem 1rem !important; color: #a9b5b6 !important; text-align: center; }

    .pill { display: inline-flex; align-items: center; gap: .35rem; padding: .3rem .7rem; border-radius: 999px; font-size: .8rem; white-space: nowrap; }
    .pill::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .icon-btn { display: inline-grid; width: 34px; height: 34px; place-items: center; border: 1px solid var(--line); border-radius: 6px; background: #fff; text-decoration: none; transition: transform .2s var(--ease), background .2s ease, color .2s ease, box-shadow .2s ease; }
    .icon-btn-edit { color: var(--pine); }
    .icon-btn-edit:hover { transform: translateY(-2px); background: var(--pine); color: #fff; box-shadow: 0 5px 12px -7px rgba(15,36,41,.8); }
    .icon-btn-delete { color: #a33b37; }
    .icon-btn-delete:hover { transform: translateY(-2px); background: #a33b37; color: #fff; box-shadow: 0 5px 12px -7px rgba(163,59,55,.8); }
    .icon-btn:focus-visible { outline: 2px solid var(--brass); outline-offset: 2px; }

    .form-body { padding: 1.75rem 1.75rem 1.5rem; }
    .form-body .form-label { font-size: .9rem; font-weight: 500; }
    .form-body .form-control,
    .form-body .form-select { border-color: var(--line); border-radius: 6px; }
    .form-body .form-control:focus,
    .form-body .form-select:focus { border-color: var(--brass); box-shadow: 0 0 0 .2rem rgba(176,141,87,.15); }

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
        padding: 1.5rem 1.75rem;
        border: 1px solid rgba(208,170,112,.24);
        border-radius: 16px;
        background: rgba(8,14,16,.76);
        backdrop-filter: blur(8px);
        box-shadow: 0 14px 32px -28px rgba(0,0,0,.7);
    }

    .page-heading .eyebrow,
    .form-page-heading .eyebrow {
        display: block;
        margin-bottom: .35rem;
        color: #d7b477;
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .13em;
        text-transform: uppercase;
    }

    .page-heading h1,
    .form-page-heading h1 {
        margin: 0;
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 3vw, 2.7rem);
        font-weight: 600;
    }

    .page-heading p,
    .form-page-heading p { margin: .25rem 0 0; color: #c1cbca; }

    .form-page-heading {
        margin-bottom: 1.25rem;
        padding: 0 .25rem;
    }

    .card {
        overflow: hidden;
        border: 1px solid rgba(208,170,112,.24);
        border-radius: 18px;
        background: rgba(8,14,16,.8);
        color: #e7e9e7;
        box-shadow: 0 14px 32px -28px rgba(0,0,0,.7);
        backdrop-filter: blur(8px);
        transition: box-shadow .25s ease, border-color .25s ease, transform .25s var(--ease);
    }

    .card:hover { border-color: rgba(208,170,112,.42); box-shadow: 0 18px 38px -28px rgba(0,0,0,.75); }
    .card-body { padding: 1.5rem; }

    .card .card-body > h4 {
        margin: -1.5rem -1.5rem 1.5rem !important;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(224,232,236,.15);
        color: #f3e7d2;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.6rem;
        font-weight: 600;
    }

    .form-label { color: #f3e7d2; font-size: .9rem; font-weight: 500; }
    .form-control, .form-select {
        min-height: 44px;
        border-color: var(--line);
        border-radius: 6px;
        color: var(--ink);
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .form-select option { background: #fff; color: var(--ink); }

    textarea.form-control { min-height: auto; }
    .form-control::placeholder { color: #97a2a3; }
    .form-control:hover, .form-select:hover { border-color: #c9d3d1; }
    .form-control:focus, .form-select:focus {
        border-color: var(--brass);
        box-shadow: 0 0 0 .2rem rgba(176,141,87,.15);
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: transform .2s var(--ease), box-shadow .2s ease, color .2s ease, background-color .2s ease, border-color .2s ease;
    }

    .btn:hover { transform: translateY(-1px); box-shadow: 0 7px 16px -12px rgba(15,36,41,.75); }
    .btn:active { transform: translateY(0); }
    .btn:focus-visible { outline: 2px solid var(--brass); outline-offset: 2px; box-shadow: none; }
    .btn-primary {
        background-color: var(--pine);
        border-color: var(--pine);
        color: #fff;
    }

    .btn-primary:hover,
    .btn-primary:focus-visible {
        background-color: var(--pine-deep);
        border-color: var(--pine-deep);
        color: #fff;
        box-shadow: 0 8px 18px -10px rgba(15,36,41,.8);
    }

    .btn-outline-secondary {
        border-color: #cbd4d2;
        color: var(--muted);
    }

    .btn-outline-secondary:hover,
    .btn-outline-secondary:focus-visible {
        border-color: var(--pine);
        background: var(--pine);
        color: #fff;
    }

    .btn-outline-success { color: #28735c; border-color: #a9cbbd; }
    .btn-outline-success:hover { background: #28735c; border-color: #28735c; color: #fff; }
    .btn-outline-danger { color: #a33b37; border-color: #e0b5b3; }
    .btn-outline-danger:hover { background: #a33b37; border-color: #a33b37; color: #fff; }

    .table { --bs-table-color: var(--ink); --bs-table-hover-bg: var(--ivory); }
    .table thead th {
        padding: .85rem 1rem;
        border-bottom: 1px solid var(--line);
        background: var(--ivory);
        color: var(--muted);
        font-size: .82rem;
        font-weight: 600;
        letter-spacing: .025em;
        white-space: nowrap;
    }

    .table tbody td { padding: .9rem 1rem; border-color: var(--line); vertical-align: middle; }
    .table tbody tr:last-child td { border-bottom: 0; }
    .table .btn-sm { width: 34px; height: 34px; padding: .25rem; }
    .table .badge { padding: .45rem .65rem; border-radius: 999px; font-weight: 500; }

    body:not(.cinematic-dashboard) .table {
        --bs-table-bg: transparent;
        --bs-table-color: #e7e9e7;
        --bs-table-hover-color: #fff;
        --bs-table-hover-bg: rgba(196,154,90,.08);
    }

    body:not(.cinematic-dashboard) .table thead th {
        border-color: rgba(224,232,236,.15);
        background: rgba(255,255,255,.045);
        color: #c0b49f;
    }

    body:not(.cinematic-dashboard) .table tbody td {
        border-color: rgba(224,232,236,.15);
        color: #e7e9e7;
    }

    body:not(.cinematic-dashboard) .card .text-muted,
    body:not(.cinematic-dashboard) .panel-card .text-muted { color: #a9b5b6 !important; }

    .pagination { gap: .3rem; }
    .page-link { border-color: rgba(224,232,236,.18); border-radius: 6px !important; background: rgba(8,14,16,.76); color: #f3e7d2; }
    .page-link:hover { border-color: var(--brass); background: rgba(196,154,90,.18); color: #fff; }
    .page-item.active .page-link { border-color: var(--brass); background: var(--pine); color: #fff; }
    .page-item.disabled .page-link { color: #a2adad; }

    .alert { border: 0; border-left: 4px solid currentColor; border-radius: 6px; box-shadow: 0 8px 22px -18px rgba(15,36,41,.5); }

    .action-buttons { display: inline-flex; align-items: center; justify-content: flex-end; gap: .4rem; }
    .action-buttons .icon-btn { width: 36px; height: 36px; }

    .reveal { animation: themeRise .8s var(--ease) both; animation-delay: calc(var(--i, 0) * 120ms); }

    @keyframes themeRise {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes themeHeroFade {
        0%, 100% { opacity: 0; }
        8%, 33% { opacity: 1; }
        41% { opacity: 0; }
    }

    @keyframes themeHeroZoom {
        from { transform: scale(1); }
        to { transform: scale(1.14) translate3d(-1.5%, -1%, 0); }
    }

    @keyframes themeDotFill {
        0% { transform: scaleX(0); }
        33% { transform: scaleX(1); }
        33.01%, 100% { transform: scaleX(0); }
    }

    @media (max-width: 767.98px) {
        .page-hero { min-height: 0; padding: 1.75rem 1.5rem 1.5rem; }
        .form-photo-hero { min-height: 0; }
        .hero-chip { min-width: 0; flex: 1 1 100px; }
        .panel-card-head small { float: none; display: block; margin-top: .25rem; }
        .nav-account { align-items: flex-start; flex-direction: column; gap: .5rem; padding: .75rem 0; }
        .page-heading { align-items: flex-start; flex-direction: column; padding: 1.25rem; }
        .page-heading .btn { width: 100%; }
        .form-page-heading { margin-top: .25rem; }
        .card-body { padding: 1.15rem; }
        .card .card-body > h4 { margin: -1.15rem -1.15rem 1.25rem !important; padding: .9rem 1.15rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        .reveal { opacity: 1; }
        .hero-slide:nth-child(1) { opacity: 1; }
    }
</style>
