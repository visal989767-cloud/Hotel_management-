@extends('layouts.app')
@section('title', $guest->exists ? 'Edit Guest' : 'Add Guest')
@section('content')
<header class="page-hero photo-hero photo-hero--guests form-photo-hero reveal">
    <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
    <div class="hero-top">
        <div><div class="hero-kicker">Guest directory</div><div class="hero-rule"></div><h1 class="hero-title">{{ $guest->exists ? 'Edit guest' : 'Add a guest' }}</h1><p class="hero-text">Save accurate contact and identification details for each guest.</p></div>
        <div class="hero-actions"><a href="{{ route('guests.index') }}" class="hero-btn hero-btn-ghost"><i class="bi bi-arrow-left me-1"></i> Back to guests</a></div>
    </div>
</header>
<div class="row justify-content-center"><div class="col-md-7">
<div class="card reveal" style="--i:1"><div class="card-body p-4">
    <h4 class="mb-4">{{ $guest->exists ? 'Edit Guest' : 'Add Guest' }}</h4>
    <form method="POST" action="{{ $guest->exists ? route('guests.update', $guest) : route('guests.store') }}">
        @csrf
        @if ($guest->exists) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Guest Name</label>
            <input type="text" name="guest_name" value="{{ old('guest_name', $guest->guest_name) }}" class="form-control @error('guest_name') is-invalid @enderror" required>
            @error('guest_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $guest->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $guest->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                    <option value="">-- Select --</option>
                    @foreach (['Male', 'Female', 'Other'] as $g)
                        <option value="{{ $g }}" @selected(old('gender', $guest->gender) == $g)>{{ $g }}</option>
                    @endforeach
                </select>
                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">ID / Passport Number</label>
                <input type="text" name="id_number" value="{{ old('id_number', $guest->id_number) }}" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" value="{{ old('address', $guest->address) }}" class="form-control">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>{{ $guest->exists ? 'Save changes' : 'Save guest' }}</button>
            <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Cancel</a>
        </div>
    </form>
</div></div>
</div></div>
@endsection
