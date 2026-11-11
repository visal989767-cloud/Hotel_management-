<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Ocean Resort</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partials.bronze-stage-head')

    <style>
        :root {
            --pine: #17333a;
            --pine-deep: #0f2429;
            --brass: #b08d57;
            --brass-dark: #8f7142;
            --gold: #d7b477;
            --ivory: #fbfaf8;
            --ink: #1f2a2e;
            --muted: #6b7b7f;
            --line: #dfe5e4;
            --ease: cubic-bezier(.22, .8, .24, 1);
        }

        html, body { height: 100%; }

        body {
            font-family: 'Figtree', system-ui, sans-serif;
            color: var(--ink);
            background: #10191b;
            overflow-x: hidden;
            position: relative;
            isolation: isolate;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                linear-gradient(180deg, rgba(6,14,16,.5), rgba(6,14,16,.72)),
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=2000&q=85') center / cover no-repeat;
        }

        .container-fluid { position: relative; z-index: 2; }

        /* ---------- Entrance animation (one orchestrated sequence) ---------- */
        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-28px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes growRule {
            from { width: 0; }
            to   { width: 56px; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .reveal {
            opacity: 0;
            animation: rise 1.5s var(--ease) forwards;
            animation-delay: calc(var(--i, 0) * 220ms + 300ms);
        }

        /* ---------- Left: hotel photo panel ---------- */
        .hero {
            position: relative;
            min-height: 100vh;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3.5rem;
            overflow: hidden;
            background: var(--pine);
            isolation: isolate;
        }

        /* ---------- 3-photo slideshow (crossfade + slow drift) ---------- */
        .slides {
            position: absolute;
            inset: 0;
            z-index: -2;
            background: var(--pine);
        }

        .slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            will-change: opacity, transform;
            animation: slideFade 21s ease-in-out infinite, slideZoom 21s linear infinite;
        }

        .slide:nth-child(1) { background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1600&q=80'); animation-delay: 0s; }
        .slide:nth-child(2) { background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1600&q=80'); animation-delay: 7s; }
        .slide:nth-child(3) { background-image: url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1600&q=80'); animation-delay: 14s; }

        @keyframes slideFade {
            0%   { opacity: 0; }
            8%   { opacity: 1; }
            33%  { opacity: 1; }
            41%  { opacity: 0; }
            100% { opacity: 0; }
        }

        @keyframes slideZoom {
            from { transform: scale(1) translate3d(0, 0, 0); }
            to   { transform: scale(1.14) translate3d(-1.5%, -1%, 0); }
        }

        /* progress dots, one per photo */
        .dots {
            display: flex;
            gap: .5rem;
            margin-top: 2rem;
        }

        .dots span {
            height: 3px;
            width: 28px;
            border-radius: 2px;
            background: rgba(255,255,255,.3);
            overflow: hidden;
            position: relative;
        }

        .dots span::after {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--brass);
            transform: scaleX(0);
            transform-origin: left;
            animation: dotFill 21s linear infinite;
        }

        .dots span:nth-child(2)::after { animation-delay: 7s; }
        .dots span:nth-child(3)::after { animation-delay: 14s; }

        @keyframes dotFill {
            0%   { transform: scaleX(0); }
            33%  { transform: scaleX(1); }
            33.01%, 100% { transform: scaleX(0); }
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(180deg, rgba(15,36,41,.55) 0%, rgba(15,36,41,.25) 40%, rgba(15,36,41,.88) 100%);
        }

        /* ---------- Brand (logo icon + name + tagline) ---------- */
        .brand {
            display: flex;
            align-items: center;
            gap: .85rem;
            animation: slideInLeft 1.6s var(--ease) both;
        }
        .brand-logo {
            width: 46px;
            height: 46px;
            flex: none;
            color: var(--gold);
            transition: transform .4s var(--ease), filter .3s ease;
        }
        .brand:hover .brand-logo,
        .mobile-brand:hover .brand-logo {
            transform: translateY(-2px);
            filter: drop-shadow(0 4px 10px rgba(215, 180, 119, .45));
        }
        .brand-text { display: flex; flex-direction: column; line-height: 1.1; }
        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 600;
            letter-spacing: .03em;
            color: #fff;
        }
        .brand-tagline {
            font-family: 'Cormorant Garamond', serif;
            font-size: .78rem;
            color: var(--gold);
            letter-spacing: .2em;
            text-transform: uppercase;
            font-weight: 600;
            margin-top: .3rem;
        }

        .hero-copy { animation: slideInLeft 1.8s var(--ease) .6s both; }

        .hero h2 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
            font-size: clamp(2.2rem, 3.6vw, 3.4rem);
            line-height: 1.1;
            max-width: 15ch;
            margin-bottom: 1rem;
        }

        .hero p {
            max-width: 38ch;
            color: rgba(255,255,255,.8);
            margin: 0;
        }

        .hero-rule {
            height: 2px;
            width: 56px;
            background: var(--brass);
            margin-bottom: 1.5rem;
            animation: growRule 1.8s var(--ease) 1.4s both;
        }

        /* ---------- Right: form panel ---------- */
        .panel {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
        }

        .panel-inner {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border: 1px solid rgba(208,170,112,.24);
            border-radius: 18px;
            background: rgba(8,14,16,.6);
            color: #e7e9e7;
            backdrop-filter: blur(8px);
        }

        .mobile-brand {
            display: none;
            align-items: center;
            gap: .7rem;
            margin-bottom: 2rem;
        }

        .mobile-brand .brand-logo { width: 40px; height: 40px; }
        .mobile-brand .brand-name { font-size: 1.6rem; }
        .mobile-brand .brand-tagline { font-size: .68rem; }

        .panel h1 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            font-size: 2.4rem;
            color: #f3e7d2;
            margin-bottom: .35rem;
        }

        .panel .lead-text { color: #c1cbca; margin-bottom: 2rem; }

        .form-label {
            font-weight: 500;
            font-size: .9rem;
            margin-bottom: .4rem;
            transition: color .2s ease;
        }

        .field:focus-within .form-label { color: var(--brass-dark); }

        .form-control {
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: .75rem .9rem;
            background: #fff;
            color: var(--ink);
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s var(--ease);
        }

        .form-control:hover { border-color: #c9d3d1; }

        .form-control:focus {
            border-color: var(--brass);
            box-shadow: 0 0 0 .2rem rgba(176,141,87,.2), 0 6px 16px -8px rgba(23,51,58,.25);
            transform: translateY(-1px);
        }

        .form-control.is-invalid {
            background-image: none;
            animation: shake .45s ease;
        }

        .input-group .form-control { border-right: 0; }

        .toggle-pass {
            border: 1px solid var(--line);
            border-left: 0;
            background: #fff;
            color: var(--muted);
            font-size: .85rem;
            font-weight: 500;
            padding: 0 .9rem;
            min-width: 62px;
            border-radius: 0 6px 6px 0;
            transition: color .2s ease, border-color .2s ease;
        }

        .toggle-pass:hover { color: var(--brass-dark); }
        .toggle-pass:focus-visible { outline: 2px solid var(--brass); outline-offset: -2px; }

        .input-group:focus-within .form-control,
        .input-group:focus-within .toggle-pass { border-color: var(--brass); }

        .form-check-input { transition: background-color .2s ease, border-color .2s ease, transform .15s ease; }
        .form-check-input:active { transform: scale(.85); }

        .form-check-input:checked {
            background-color: var(--pine);
            border-color: var(--pine);
        }

        .form-check-input:focus {
            border-color: var(--brass);
            box-shadow: 0 0 0 .2rem rgba(176,141,87,.2);
        }

        /* ---------- Button: lift + light sweep + loading ---------- */
        .btn-signin {
            position: relative;
            overflow: hidden;
            background: var(--pine);
            border: 1px solid var(--pine);
            color: #fff;
            padding: .8rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            transition: transform .2s var(--ease), box-shadow .2s ease, background .2s ease;
        }

        .btn-signin::after {
            content: "";
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(100deg, transparent, rgba(255,255,255,.22), transparent);
            transform: skewX(-20deg);
        }

        .btn-signin:hover,
        .btn-signin:focus-visible {
            background: var(--pine-deep);
            border-color: var(--pine-deep);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 22px -10px rgba(15,36,41,.65);
        }

        .btn-signin:hover::after,
        .btn-signin:focus-visible::after {
            left: 130%;
            transition: left .7s var(--ease);
        }

        .btn-signin:active { transform: translateY(0); box-shadow: none; }
        .btn-signin:focus-visible { outline: 2px solid var(--brass); outline-offset: 2px; }

        .btn-signin .spinner {
            display: none;
            width: 1rem;
            height: 1rem;
            margin-right: .5rem;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: #fff;
            border-radius: 50%;
            vertical-align: -2px;
            animation: spin .7s linear infinite;
        }

        .btn-signin.is-loading { pointer-events: none; opacity: .9; }
        .btn-signin.is-loading .spinner { display: inline-block; }

        /* ---------- Links ---------- */
        a.link {
            position: relative;
            color: #e0c58f;
            font-weight: 500;
            text-decoration: none;
        }

        a.link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 1px;
            background: currentColor;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s var(--ease);
        }

        a.link:hover::after,
        a.link:focus-visible::after { transform: scaleX(1); }

        .form-text { color: var(--muted); font-size: .82rem; }

        .foot { margin-top: 2rem; color: #c1cbca; font-size: .9rem; }

        .alert-status {
            background: rgba(15,36,41,.9);
            border: 1px solid rgba(208,170,112,.35);
            color: #f3e7d2;
            border-radius: 6px;
            font-size: .9rem;
            padding: .7rem .9rem;
            margin-bottom: 1.25rem;
        }

        @media (max-width: 991.98px) {
            .mobile-brand { display: flex; }
            .container-fluid { position: relative; z-index: 1; }
            .panel { align-items: center; padding: 2rem 1.25rem; }
            .panel-inner { max-width: 430px; padding: 1.5rem 2.25rem; }
            .mobile-brand { margin-bottom: 1rem; }
            .panel .lead-text { margin-bottom: 1.1rem; }
            .panel .form-check.mb-4 { margin-bottom: .75rem !important; }
            .panel .foot { margin-top: 1rem; }
        }

        @media (max-width: 575.98px) {
            .panel { padding: 1.25rem; }
            .panel-inner { padding: 1.5rem 1.4rem; }
            .auth-page .panel-inner { background: rgba(8,14,16,.5); }
            .panel h1 { font-size: 2.1rem; }
        }

        /* Respect people who turn animations off */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
            .reveal { opacity: 1; }
            .slide:nth-child(1) { opacity: 1; }
        }

        /* ---------- Page loader ---------- */
        .page-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.1rem;
            background:
                radial-gradient(ellipse at 50% 40%, rgba(215, 180, 119, .12), transparent 55%),
                #0b1b26;
            transition: opacity .8s var(--ease), visibility .8s;
        }
        .page-loader.is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }

        .loader-logo {
            width: 84px;
            height: 84px;
            color: #d7b477;
            filter: drop-shadow(0 6px 18px rgba(215, 180, 119, .35));
        }
        .loader-logo path {
            stroke-dasharray: 1;
            stroke-dashoffset: 1;
            animation: loaderDraw 1.4s var(--ease) forwards;
        }
        .loader-logo path:nth-child(2) { animation-delay: .15s; }
        .loader-logo path:nth-child(3) { animation-delay: .3s; }
        .loader-logo path:nth-child(4) { animation-delay: .45s; }
        .loader-logo path:nth-child(5) { animation-delay: .6s; }
        @keyframes loaderDraw { to { stroke-dashoffset: 0; } }

        .loader-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.1rem;
            font-weight: 600;
            letter-spacing: .03em;
            color: #fff;
            opacity: 0;
            animation: rise 1.1s var(--ease) .6s forwards;
        }
        .loader-tagline {
            margin-top: -.8rem;
            font-family: 'Cormorant Garamond', serif;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #d7b477;
            opacity: 0;
            animation: rise 1.1s var(--ease) .8s forwards;
        }
        .loader-bar {
            width: 140px;
            height: 2px;
            margin-top: .6rem;
            overflow: hidden;
            border-radius: 2px;
            background: rgba(255, 255, 255, .18);
        }
        .loader-bar span {
            display: block;
            height: 100%;
            background: #d7b477;
            transform: scaleX(0);
            transform-origin: left;
            animation: loaderBar 1.3s var(--ease) forwards;
        }
        @keyframes loaderBar { to { transform: scaleX(1); } }

        /* Hold the page entrance animations until the loader is gone */
        body.is-loading { overflow: hidden; }
        body.is-loading .reveal,
        body.is-loading .brand,
        body.is-loading .hero-copy,
        body.is-loading .hero-rule { animation-play-state: paused; }
    </style>
</head>
<body class="auth-page is-loading">
<noscript><style>.page-loader { display: none; } .is-loading * { animation-play-state: running !important; }</style></noscript>
<div class="page-loader" id="pageLoader" role="status" aria-label="Loading">
    <svg class="loader-logo" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path pathLength="1" d="M24 3.5l1.7 4.3 4.3 1.7-4.3 1.7L24 15.5l-1.7-4.3L18 9.5l4.3-1.7z"/>
        <path pathLength="1" d="M5 27.5L24 14l19 13.5"/>
        <path pathLength="1" d="M9.5 25v16.5h29V25"/>
        <path pathLength="1" d="M19.5 41.5V32h9v9.5"/>
        <path pathLength="1" d="M3.5 41.5h41"/>
    </svg>
    <div class="loader-name">Ocean Resort</div>
    <div class="loader-tagline">Stay Better, Feel Home</div>
    <div class="loader-bar"><span></span></div>
</div>

<canvas id="hotel-bronze-stage" class="bronze-stage-canvas" aria-hidden="true"></canvas>
<div class="container-fluid p-0">
    <div class="row g-0">

        {{-- Photo panel (desktop only) --}}
        <div class="col-lg-6 d-none d-lg-block">
            <aside class="hero">
                <div class="slides" aria-hidden="true">
                    <div class="slide"></div>
                    <div class="slide"></div>
                    <div class="slide"></div>
                </div>

                <div class="brand">
                    <svg class="brand-logo" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M24 3.5l1.7 4.3 4.3 1.7-4.3 1.7L24 15.5l-1.7-4.3L18 9.5l4.3-1.7z"/>
                        <path d="M5 27.5L24 14l19 13.5"/>
                        <path d="M9.5 25v16.5h29V25"/>
                        <path d="M19.5 41.5V32h9v9.5"/>
                        <path d="M3.5 41.5h41"/>
                    </svg>
                    <div class="brand-text">
                        <div class="brand-name">Ocean Resort</div>
                        <div class="brand-tagline">Stay Better, Feel Home</div>
                    </div>
                </div>

                <div class="hero-copy">
                    <div class="hero-rule"></div>
                    <h2>Join the team behind every great stay.</h2>
                    <p>Create your staff account to handle reservations, check-ins and housekeeping.</p>
                    <div class="dots" aria-hidden="true"><span></span><span></span><span></span></div>
                </div>
            </aside>
        </div>

        {{-- Form panel --}}
        <div class="col-lg-6">
            <main class="panel">
                <div class="panel-inner">

                    <div class="mobile-brand reveal" style="--i:0">
                        <svg class="brand-logo" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M24 3.5l1.7 4.3 4.3 1.7-4.3 1.7L24 15.5l-1.7-4.3L18 9.5l4.3-1.7z"/>
                            <path d="M5 27.5L24 14l19 13.5"/>
                            <path d="M9.5 25v16.5h29V25"/>
                            <path d="M19.5 41.5V32h9v9.5"/>
                            <path d="M3.5 41.5h41"/>
                        </svg>
                        <div class="brand-text">
                            <div class="brand-name">Ocean Resort</div>
                            <div class="brand-tagline">Stay Better, Feel Home</div>
                        </div>
                    </div>

                    <h1 class="reveal" style="--i:1">Create your account</h1>
                    <p class="lead-text reveal" style="--i:2">Register to start managing your hotel.</p>

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="mb-3 field reveal" style="--i:3">
                            <label for="name" class="form-label">Full name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter your full name" autocomplete="name" required autofocus>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3 field reveal" style="--i:4">
                            <label for="email" class="form-label">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="you@example.com" autocomplete="email" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 field reveal" style="--i:5">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group has-validation">
                                <input id="password" type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       autocomplete="new-password" required>
                                <button type="button" class="toggle-pass" id="togglePass"
                                        aria-controls="password" aria-pressed="false">Show</button>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-text">Use at least 8 characters.</div>
                        </div>

                        <div class="reveal" style="--i:6">
                            <button class="btn btn-signin w-100" type="submit" id="submitBtn">
                                <span class="spinner" aria-hidden="true"></span>
                                <span class="label">Create account</span>
                            </button>
                        </div>
                    </form>

                    <p class="foot mb-0 reveal" style="--i:7">
                        Already have an account? <a class="link" href="{{ route('login') }}">Sign in</a>
                    </p>
                </div>
            </main>
        </div>

    </div>
</div>

<script>
    (function () {
        // Show / hide password
        var toggle = document.getElementById('togglePass');
        var input = document.getElementById('password');
        toggle.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            toggle.textContent = show ? 'Hide' : 'Show';
            toggle.setAttribute('aria-pressed', show);
        });

        // Loading state on submit (only when the browser says the form is valid)
        var form = document.getElementById('registerForm');
        var btn = document.getElementById('submitBtn');
        form.addEventListener('submit', function () {
            btn.classList.add('is-loading');
            btn.querySelector('.label').textContent = 'Creating account...';
        });

        // If the browser restores the page from cache (back button), reset the button
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                btn.classList.remove('is-loading');
                btn.querySelector('.label').textContent = 'Create account';
            }
        });
    })();
</script>
<script type="module" src="{{ asset('js/bronze-stage.js') }}"></script>
<script>
    (function () {
        var loader = document.getElementById('pageLoader');
        if (!loader) return;
        var start = Date.now();
        var finished = false;

        function finish() {
            if (finished) return;
            finished = true;
            // keep the loader up at least 1.3s so the logo animation can play
            var wait = Math.max(0, 1300 - (Date.now() - start));
            setTimeout(function () {
                document.body.classList.remove('is-loading');
                loader.classList.add('is-hidden');
                setTimeout(function () { loader.remove(); }, 900);
            }, wait);
        }

        if (document.readyState === 'complete') finish();
        else window.addEventListener('load', finish);

        // safety net: never keep people waiting more than 6s
        setTimeout(finish, 6000);
    })();
</script>
</body>
</html>