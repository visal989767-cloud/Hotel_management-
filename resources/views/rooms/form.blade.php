@extends('layouts.app')
@section('title', $room->exists ? 'Edit Room' : 'Add Room')
@section('content')
<header class="page-hero photo-hero photo-hero--rooms form-photo-hero reveal">
    <div class="hero-slides" aria-hidden="true"><div class="hero-slide"></div><div class="hero-slide"></div><div class="hero-slide"></div></div>
    <div class="hero-top">
        <div><div class="hero-kicker">Accommodation</div><div class="hero-rule"></div><h1 class="hero-title">{{ $room->exists ? 'Edit room' : 'Add a room' }}</h1><p class="hero-text">Enter room details and set its availability status.</p></div>
        <div class="hero-actions"><a href="{{ route('rooms.index') }}" class="hero-btn hero-btn-ghost"><i class="bi bi-arrow-left me-1"></i> Back to rooms</a></div>
    </div>
</header>
<style>
    .room-type-preview {
        position: relative;
        isolation: isolate;
        min-height: 360px;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
        padding: 1.75rem;
        border-radius: 8px;
        background: var(--pine-deep);
        color: #fff;
    }

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
        background: linear-gradient(180deg, rgba(15,36,41,.05) 15%, rgba(15,36,41,.88) 100%);
    }

    .room-type-preview:hover img { transform: scale(1.04); }
    .room-type-preview-copy { text-shadow: 0 1px 8px rgba(0,0,0,.4); }
    .room-type-preview-copy span { color: rgba(255,255,255,.78); font-size: .82rem; }
    .room-type-preview-copy h2 {
        margin: .25rem 0 0;
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
    }

    @media (max-width: 991.98px) {
        .room-type-preview { min-height: 260px; }
    }
</style>

<div class="row g-4 justify-content-center align-items-stretch">
<div class="col-lg-7">
<div class="card reveal" style="--i:1"><div class="card-body p-4">
    <h4 class="mb-4">{{ $room->exists ? 'Edit Room' : 'Add Room' }}</h4>
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
                        <option value="{{ $t->room_type_id }}" @selected(old('room_type_id', $room->room_type_id) == $t->room_type_id)>{{ $t->type_name }} (${{ number_format($t->price_per_night, 2) }})</option>
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
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description', $room->description) }}</textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>{{ $room->exists ? 'Save changes' : 'Save room' }}</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Cancel</a>
        </div>
    </form>
</div></div>
</div>
<div class="col-lg-5 reveal" style="--i:2">
    <aside class="room-type-preview h-100" aria-live="polite">
        <img id="roomTypePreviewImage" src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1000&q=80" alt="" aria-hidden="true">
        <div class="room-type-preview-copy">
            <span>Selected room type</span>
            <h2 id="roomTypePreviewName">Choose a room type</h2>
        </div>
    </aside>
</div>
</div>

<script>
    (function () {
        var select = document.getElementById('room_type_id');
        var image = document.getElementById('roomTypePreviewImage');
        var name = document.getElementById('roomTypePreviewName');
        var roomImages = {
            single: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1000&q=80',
            double: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1000&q=80',
            deluxe: 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1000&q=80',
            suite: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1000&q=80',
            default: 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1000&q=80'
        };

        function updateRoomTypePreview() {
            var selectedOption = select.options[select.selectedIndex];
            var roomTypeName = selectedOption && selectedOption.value ? selectedOption.text.trim() : '';
            var normalizedName = roomTypeName.toLowerCase();
            var imageType = ['single', 'double', 'deluxe', 'suite'].find(function (type) {
                return normalizedName.includes(type);
            }) || 'default';

            name.textContent = roomTypeName || 'Choose a room type';
            image.src = roomImages[imageType];
        }

        select.addEventListener('change', updateRoomTypePreview);
        updateRoomTypePreview();
    })();
</script>
@endsection
