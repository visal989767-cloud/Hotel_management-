<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Ocean Resort</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @include('partials.bronze-stage-head')
    @include('partials.theme')
    @if (request()->routeIs('dashboard') && session('show_login_loader'))
        <style>
            .login-loading-screen {
                position: fixed;
                inset: 0;
                z-index: 2000;
                display: grid;
                place-content: center;
                justify-items: center;
                gap: 1.25rem;
                padding: 2rem;
                overflow: hidden;
                background:
                    radial-gradient(ellipse at 50% 42%, rgba(215, 180, 119, .16), transparent 42%),
                    rgba(3, 8, 11, .88);
                backdrop-filter: blur(6px);
                -webkit-backdrop-filter: blur(6px);
                color: #f5f2ed;
                text-align: center;
                opacity: 1;
                transition: opacity .5s ease;
            }

            .login-loading-screen.is-hidden {
                opacity: 0;
                pointer-events: none;
            }

            .login-loading-logo {
                width: 104px;
                height: 104px;
                color: #d7b477;
                filter: drop-shadow(0 6px 18px rgba(215, 180, 119, .35));
                animation: login-loading-glow 2.6s ease-in-out 1.8s infinite;
            }

            .login-loading-logo .ll-sparkle {
                fill: #d7b477;
                opacity: 0;
                animation: login-loading-fade .9s ease .5s forwards;
            }

            .login-loading-logo .ll-draw {
                fill: none;
                stroke: #d7b477;
                stroke-linecap: round;
                stroke-linejoin: round;
                stroke-dasharray: 1;
                stroke-dashoffset: 1;
                animation: login-loading-draw 1.5s cubic-bezier(.22, .8, .24, 1) forwards;
            }

            .login-loading-logo .ll-draw:nth-of-type(2) { animation-delay: .15s; }
            .login-loading-logo .ll-draw:nth-of-type(3) { animation-delay: .4s; }
            .login-loading-logo .ll-draw:nth-of-type(4) { animation-delay: .6s; }
            .login-loading-logo .ll-draw:nth-of-type(5) { animation-delay: .75s; }

            .login-loading-copy {
                display: grid;
                gap: .45rem;
                justify-items: center;
            }

            .login-loading-copy strong {
                color: #fff;
                font-family: 'Cormorant Garamond', Georgia, serif;
                font-size: 2rem;
                font-weight: 600;
                letter-spacing: .03em;
                opacity: 0;
                animation: login-loading-rise 1s ease .6s forwards;
            }

            .login-loading-tagline {
                color: #d7b477;
                font-family: 'Cormorant Garamond', Georgia, serif;
                font-size: .8rem;
                font-weight: 600;
                letter-spacing: .2em;
                text-transform: uppercase;
                opacity: 0;
                animation: login-loading-rise 1s ease .85s forwards;
            }

            .login-loading-status {
                margin-top: .35rem;
                color: rgba(245, 242, 237, .72);
                font-size: .9rem;
                opacity: 0;
                animation: login-loading-rise 1s ease 1.1s forwards;
            }

            .login-loading-progress {
                width: min(14rem, 65vw);
                height: 2px;
                margin: .25rem auto 0;
                overflow: hidden;
                border-radius: 2px;
                background: rgba(255, 255, 255, .18);
            }

            .login-loading-progress::after {
                content: "";
                display: block;
                width: 100%;
                height: 100%;
                background: #d7b477;
                transform: scaleX(0);
                transform-origin: left;
                animation: login-loading-progress 5s linear forwards;
            }

            @keyframes login-loading-draw {
                to { stroke-dashoffset: 0; }
            }

            @keyframes login-loading-fade {
                to { opacity: 1; }
            }

            @keyframes login-loading-rise {
                from { opacity: 0; transform: translateY(12px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes login-loading-glow {
                0%, 100% { filter: drop-shadow(0 6px 18px rgba(215, 180, 119, .3)); }
                50% { filter: drop-shadow(0 8px 28px rgba(215, 180, 119, .65)); }
            }

            @keyframes login-loading-progress {
                to { transform: scaleX(1); }
            }

            @media (prefers-reduced-motion: reduce) {
                .login-loading-logo,
                .login-loading-logo .ll-draw,
                .login-loading-logo .ll-sparkle,
                .login-loading-copy strong,
                .login-loading-tagline,
                .login-loading-status,
                .login-loading-progress::after { animation: none; }
                .login-loading-logo .ll-draw { stroke-dashoffset: 0; }
                .login-loading-logo .ll-sparkle,
                .login-loading-copy strong,
                .login-loading-tagline,
                .login-loading-status { opacity: 1; }
                .login-loading-progress::after { transform: scaleX(1); }
                .login-loading-screen { transition-duration: .01ms; }
            }
        </style>
    @endif
</head>
<body class="{{ request()->routeIs('dashboard') ? 'cinematic-dashboard' : '' }}">
<canvas id="hotel-bronze-stage" class="bronze-stage-canvas" aria-hidden="true"></canvas>
@if (request()->routeIs('dashboard') && session('show_login_loader'))
    <div class="login-loading-screen" id="loginLoadingScreen" role="status" aria-live="polite">
        <svg class="login-loading-logo" viewBox="0 0 36 36" fill="none" aria-hidden="true">
            <path class="ll-sparkle" d="M18 4C18 4 19 11 25 12C25 12 19 13.5 18 19C18 19 17 13.5 11 12C11 12 17 11 18 4Z"/>
            <path class="ll-draw" pathLength="1" stroke-width="1.8" d="M9 21L18 13L27 21V32H9V21Z"/>
            <path class="ll-draw" pathLength="1" stroke-width="1.8" d="M15 32V24H21V32"/>
            <path class="ll-draw" pathLength="1" stroke-width="1.4" d="M4 22C7 19 11 21 14 20"/>
            <path class="ll-draw" pathLength="1" stroke-width="1.4" d="M22 20C25 21 29 19 32 22"/>
        </svg>
        <div class="login-loading-copy">
            <strong>Ocean Resort</strong>
            <span class="login-loading-tagline">Stay Better, Feel Home</span>
            <span class="login-loading-status">Preparing your dashboard...</span>
        </div>
        <div class="login-loading-progress" aria-hidden="true"></div>
    </div>
@endif
<nav class="navbar navbar-expand-lg navbar-dark ocean-navbar mb-2">
    <div class="container-fluid px-lg-5 px-3">
        <a class="navbar-brand ocean-brand" href="{{ route('dashboard') }}">
            <span class="ocean-brand-icon">
                <svg width="34" height="34" viewBox="0 0 36 36" fill="none"><path d="M18 4C18 4 19 11 25 12C25 12 19 13.5 18 19C18 19 17 13.5 11 12C11 12 17 11 18 4Z" fill="#d7b477"/><path d="M9 21L18 13L27 21V32H9V21Z" stroke="#d7b477" stroke-width="1.8" stroke-linejoin="round"/><path d="M15 32V24H21V32" stroke="#d7b477" stroke-width="1.8" stroke-linejoin="round"/><path d="M4 22C7 19 11 21 14 20" stroke="#d7b477" stroke-width="1.4" stroke-linecap="round"/><path d="M22 20C25 21 29 19 32 22" stroke="#d7b477" stroke-width="1.4" stroke-linecap="round"/></svg>
            </span>
            <span class="ocean-brand-text">
                <span class="ocean-brand-name">Ocean Resort</span>
                <span class="ocean-brand-tagline">Stay Better, Feel Home</span>
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav mx-auto ocean-nav-links">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">DASHBOARD</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}">ROOMS</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}" href="{{ route('guests.index') }}">GUESTS</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">BOOKINGS</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">SETTINGS</a></li>
            </ul>
            <div class="ocean-nav-actions d-flex align-items-center gap-3">
                @if (request()->routeIs('guests.*'))
                    <a href="{{ route('guests.create') }}" class="btn ocean-nav-add-btn">
                        <i class="bi bi-plus me-1"></i> Add Guest
                    </a>
                @elseif (request()->routeIs('bookings.*'))
                    <a href="{{ route('bookings.create') }}" class="btn ocean-nav-add-btn">
                        <i class="bi bi-calendar-plus me-1"></i> New Booking
                    </a>
                @endif
                <div class="dropdown">
                    <button class="ocean-admin-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=120&q=80" alt="Admin" class="ocean-admin-avatar">
                        <span class="ocean-admin-name">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end ocean-dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('rooms.index') }}"><i class="bi bi-door-closed me-2"></i> Rooms</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .ocean-navbar {
        background: transparent !important;
        border-bottom: 0 !important;
        padding: 1.25rem 0 .5rem !important;
        box-shadow: none !important;
        position: relative;
        z-index: 10;
    }
    .ocean-brand {
        display: inline-flex;
        align-items: center;
        gap: .65rem;
        text-decoration: none;
    }
    .ocean-brand-icon {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ocean-brand-text {
        display: flex;
        flex-direction: column;
    }
    .ocean-brand-name {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.55rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.1;
        letter-spacing: .02em;
        text-shadow: 0 2px 12px rgba(0, 0, 0, .45);
    }
    .ocean-brand-tagline {
        font-size: .68rem;
        color: #e9c98f;
        letter-spacing: .17em;
        text-transform: uppercase;
        font-weight: 600;
        text-shadow: 0 1px 8px rgba(0, 0, 0, .45);
    }
    .ocean-nav-links .nav-link {
        font-size: .78rem !important;
        font-weight: 600 !important;
        letter-spacing: .14em !important;
        color: rgba(255, 255, 255, .75) !important;
        padding: .5rem 1rem !important;
        position: relative;
        text-transform: uppercase;
        transition: color .2s ease;
    }
    .ocean-nav-links .nav-link:hover,
    .ocean-nav-links .nav-link.active {
        color: #fff !important;
    }
    .ocean-nav-links .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 1rem;
        right: 1rem;
        height: 2px;
        background: #d7b477;
        border-radius: 2px;
    }
    .ocean-nav-add-btn {
        background: #d7b477 !important;
        color: #0f2429 !important;
        border: 1px solid #d7b477 !important;
        border-radius: 30px !important;
        font-size: .84rem !important;
        font-weight: 600 !important;
        padding: .45rem 1.25rem !important;
        transition: all .2s ease !important;
        text-decoration: none;
    }
    .ocean-nav-add-btn:hover {
        background: #e5c78f !important;
        border-color: #e5c78f !important;
        color: #0f2429 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(215, 180, 119, .3);
    }
    .ocean-admin-btn {
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 30px;
        padding: 4px 14px 4px 5px;
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        font-size: .84rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s ease;
    }
    .ocean-admin-btn:hover {
        background: rgba(255, 255, 255, .12);
        border-color: rgba(255, 255, 255, .25);
        color: #fff;
    }
    .ocean-admin-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(215, 180, 119, .45);
        background: #0b1b26;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .5);
        transition: all .35s ease;
    }
    .ocean-admin-btn:hover .ocean-admin-avatar {
        border-color: #d7b477;
        transform: scale(1.12) rotate(-3deg);
        box-shadow: 0 8px 18px rgba(215, 180, 119, .22);
    }
    .ocean-admin-name {
        color: #fff;
        font-weight: 600;
        text-shadow: 0 1px 6px rgba(0, 0, 0, .45);
    }
    .ocean-dropdown-menu {
        background: #0f181a !important;
        border: 1px solid rgba(215, 180, 119, .3) !important;
        border-radius: 10px !important;
        box-shadow: 0 16px 36px rgba(0, 0, 0, .7) !important;
    }
    .ocean-dropdown-menu .dropdown-item {
        color: #e0e8ec !important;
        font-size: .85rem !important;
        padding: .5rem 1rem !important;
    }
    .ocean-dropdown-menu .dropdown-item:hover {
        background: rgba(215, 180, 119, .15) !important;
        color: #d7b477 !important;
    }
</style>

<div class="container pb-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="{{ asset('js/bronze-stage.js') }}"></script>
@if (request()->routeIs('dashboard') && session('show_login_loader'))
    <script>
        window.setTimeout(function () {
            var screen = document.getElementById('loginLoadingScreen');
            if (!screen) return;

            screen.classList.add('is-hidden');
            window.setTimeout(function () {
                screen.remove();
            }, 500);
        }, 5000);
    </script>
@endif
</body>
</html>