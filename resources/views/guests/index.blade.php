@extends('layouts.app')
@section('title', 'Guests')
@section('content')
<header class="page-hero photo-hero photo-hero--guests reveal">
    <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
    <div class="hero-top">
        <div>
            <div class="hero-kicker">Guest directory</div>
            <div class="hero-rule"></div>
            <h1 class="hero-title">Guests</h1>
            <p class="hero-text">Keep guest profiles and booking history up to date.</p>
        </div>
        <div class="hero-actions"><a href="{{ route('guests.create') }}" class="hero-btn hero-btn-primary"><i class="bi bi-person-plus me-1"></i> Add guest</a></div>
    </div>
    <div class="hero-bottom"><div></div><div class="hero-dots" aria-hidden="true"><span></span><span></span><span></span></div></div>
</header>

<div class="panel-card mb-3 reveal" style="--i:1"><div class="card-body">
    <form method="GET" class="row g-2">
        <div class="col-md-6"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, email or phone..."></div>
        <div class="col-md-3">
            <select name="gender" class="form-select">
                <option value="">All Genders</option>
                @foreach (['Male', 'Female', 'Other'] as $g)
                    <option value="{{ $g }}" @selected(request('gender') == $g)>{{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i> Filter</button>
            <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
        </div>
    </form>
</div></div>

<div class="panel-card reveal" style="--i:2">
    <div class="table-responsive">
        <table class="table table-hover align-middle themed-table">
            <thead><tr><th>ID</th><th>Guest</th><th>Contact</th><th>ID Number</th><th>Bookings</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            @forelse ($guests as $g)
                <tr>
                    <td>{{ $g->guest_id }}</td>
                    <td><div class="fw-semibold">{{ $g->guest_name }}</div><small class="text-muted">{{ $g->gender }}</small></td>
                    <td>{{ $g->email }}<br><small class="text-muted">{{ $g->phone ?: 'N/A' }}</small></td>
                    <td>{{ $g->id_number ?: 'N/A' }}</td>
                    <td><span class="badge bg-info">{{ $g->bookings_count }}</span></td>
                    <td class="text-end">
                        <span class="action-buttons">
                        <a href="{{ route('guests.edit', $g) }}" class="icon-btn icon-btn-edit" title="Edit guest" aria-label="Edit {{ $g->guest_name }}"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('guests.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this guest?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn icon-btn-delete" title="Delete guest" aria-label="Delete {{ $g->guest_name }}"><i class="bi bi-trash"></i></button>
                        </form>
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-state">No guests found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $guests->links() }}</div>
@endsection
