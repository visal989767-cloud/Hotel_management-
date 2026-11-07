@extends('layouts.app')
@section('title', $roomType->exists ? 'Edit Room Type' : 'Add Room Type')
@section('content')

<style>
    .form-hero { min-height: 210px; }
    .form-hero .hero-slide:nth-child(1) { background-image: url('https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1800&q=80'); }
    .form-hero .hero-slide:nth-child(2) { background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1800&q=80'); }
    .form-hero .hero-slide:nth-child(3) { background-image: url('https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1800&q=80'); }

    .form-body { padding: 1.75rem 1.75rem 1.5rem; }
    .form-body .form-label { font-weight: 500; font-size: .9rem; margin-bottom: .4rem; transition: color .2s ease; }
    .form-body .field:focus-within .form-label { color: var(--brass-dark); }
    .form-body .form-control.is-invalid { background-image: none; }
    .form-hint { color: var(--muted); font-size: .82rem; margin-top: .35rem; }

    .input-group-text { background: var(--ivory); border: 1px solid var(--line); color: var(--muted); font-weight: 500; }
    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control { border-color: var(--brass); }
    .input-group .form-control { border-left: 0; }
    .input-group > .input-group-text:first-child { border-radius: 6px 0 0 6px; }

    .stepper { display: flex; }
    .stepper .form-control { text-align: center; border-radius: 0; border-left: 0; border-right: 0; }
    .stepper button { width: 44px; border: 1px solid var(--line); background: var(--ivory); color: var(--pine); font-size: 1.1rem; transition: background .2s ease, color .2s ease; }
    .stepper button:first-child { border-radius: 6px 0 0 6px; }
    .stepper button:last-child { border-radius: 0 6px 6px 0; }
    .stepper button:hover { background: var(--pine); color: #fff; }
    .stepper button:focus-visible { outline: 2px solid var(--brass); outline-offset: -2px; }
    .stepper:focus-within button, .stepper:focus-within .form-control { border-color: var(--brass); }

    .btn-save { position: relative; overflow: hidden; padding: .75rem 1.6rem; transition: transform .2s var(--ease), box-shadow .2s ease, background .2s ease; }
    .btn-save::after { content: ""; position: absolute; top: 0; left: -75%; width: 50%; height: 100%; background: linear-gradient(100deg, transparent, rgba(255,255,255,.22), transparent); transform: skewX(-20deg); }
    .btn-save:hover, .btn-save:focus-visible { transform: translateY(-2px); box-shadow: 0 10px 22px -10px rgba(15,36,41,.65); }
    .btn-save:hover::after, .btn-save:focus-visible::after { left: 130%; transition: left .7s var(--ease); }
    .btn-save:active { transform: translateY(0); box-shadow: none; }
    .btn-save .spinner { display: none; width: 1rem; height: 1rem; margin-right: .5rem; border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; vertical-align: -2px; animation: spin .7s linear infinite; }
    .btn-save.is-loading { pointer-events: none; opacity: .9; }
    .btn-save.is-loading .spinner { display: inline-block; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Live preview */
    .preview { position: sticky; top: 1.5rem; }
    .preview-card {
        position: relative; isolation: isolate; overflow: hidden;
        min-height: 300px; padding: 2rem; border-radius: 6px; color: #fff;
        background: var(--pine-deep);
        display: flex; flex-direction: column; justify-content: space-between;
    }
    .preview-image {
        position: absolute; inset: 0; z-index: -2; width: 100%; height: 100%;
        object-fit: cover; object-position: center; opacity: .78;
        transition: transform .8s var(--ease), opacity .35s ease;
    }
    .preview-card::after {
        content: ""; position: absolute; inset: 0; z-index: -1;
        background: linear-gradient(180deg, rgba(15,36,41,.48) 0%, rgba(15,36,41,.88) 100%);
    }
    .preview-card:hover .preview-image { transform: scale(1.04); opacity: .9; }
    .preview-card > div { position: relative; }
    .preview-label { font-size: .85rem; color: rgba(255,255,255,.75); }
    .preview-name { font-family: 'Cormorant Garamond', serif; font-weight: 500; font-size: 2.4rem; line-height: 1.1; margin: .4rem 0 0; word-break: break-word; }
    .preview-meta { display: flex; justify-content: space-between; align-items: flex-end; gap: 1rem; }
    .preview-price { font-family: 'Cormorant Garamond', serif; font-weight: 600; font-size: 2rem; line-height: 1; }
    .preview-price small { font-family: 'Figtree', system-ui, sans-serif; font-weight: 400; font-size: .85rem; color: rgba(255,255,255,.75); }
    .preview-cap { padding: .3rem .8rem; border-radius: 999px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.28); font-size: .85rem; white-space: nowrap; }
    .preview-desc { margin: 1rem 0 0; color: rgba(255,255,255,.8); font-size: .9rem; max-width: 40ch; }
</style>

<header class="page-hero form-hero reveal" style="--i:0">
    <div class="hero-slides" aria-hidden="true">
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
    </div>

    <div class="hero-top">
        <div>
            <div class="hero-kicker">Room types</div>
            <div class="hero-rule"></div>
            <h1 class="hero-title">{{ $roomType->exists ? 'Edit '.$roomType->type_name : 'Add a room type' }}</h1>
            <p class="hero-text">{{ $roomType->exists ? 'Update the rate, capacity or description. Changes apply to every room of this type.' : 'Set a name, nightly rate and capacity. You can then assign rooms to it.' }}</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('room-types.index') }}" class="hero-btn hero-btn-ghost"><i class="bi bi-arrow-left"></i> Back to room types</a>
        </div>
    </div>

    <div class="hero-bottom">
        <div></div>
        <div class="hero-dots" aria-hidden="true"><span></span><span></span><span></span></div>
    </div>
</header>

<div class="row g-4">
    <div class="col-lg-7 reveal" style="--i:1">
        <div class="panel-card">
            <div class="panel-card-head">Details</div>
            <div class="form-body">
                <form method="POST" id="typeForm"
                      action="{{ $roomType->exists ? route('room-types.update', $roomType) : route('room-types.store') }}">
                    @csrf
                    @if ($roomType->exists) @method('PUT') @endif

                    <div class="mb-3 field">
                        <label for="type_name" class="form-label">Type name</label>
                        <input id="type_name" type="text" name="type_name" value="{{ old('type_name', $roomType->type_name) }}"
                               class="form-control @error('type_name') is-invalid @enderror" placeholder="e.g. Deluxe Suite" required autofocus>
                        @error('type_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 field">
                            <label for="price_per_night" class="form-label">Price per night</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text">$</span>
                                <input id="price_per_night" type="number" step="0.01" min="0" name="price_per_night"
                                       value="{{ old('price_per_night', $roomType->price_per_night) }}"
                                       class="form-control @error('price_per_night') is-invalid @enderror" placeholder="0.00" required>
                                @error('price_per_night') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 field">
                            <label for="capacity" class="form-label">Capacity (guests)</label>
                            <div class="stepper has-validation">
                                <button type="button" data-step="-1" aria-label="Decrease capacity">&minus;</button>
                                <input id="capacity" type="number" min="1" max="20" name="capacity"
                                       value="{{ old('capacity', $roomType->capacity ?? 2) }}"
                                       class="form-control @error('capacity') is-invalid @enderror" required>
                                <button type="button" data-step="1" aria-label="Increase capacity">+</button>
                            </div>
                            @error('capacity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4 field">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4" class="form-control"
                                  placeholder="Bed type, view, amenities">{{ old('description', $roomType->description) }}</textarea>
                        <div class="form-hint">Optional. Shown in the room types list.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-save" type="submit" id="saveBtn">
                            <span class="spinner" aria-hidden="true"></span>
                            <span class="label">{{ $roomType->exists ? 'Update room type' : 'Save room type' }}</span>
                        </button>
                        <a href="{{ route('room-types.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5 reveal" style="--i:2">
        <div class="preview">
            <div class="preview-card">
                <img
                    class="preview-image"
                    id="pvImage"
                    src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1000&q=80"
                    alt=""
                    aria-hidden="true"
                >
                <div>
                    <div class="preview-label">Preview</div>
                    <h2 class="preview-name" id="pvName">{{ old('type_name', $roomType->type_name) ?: 'Room type name' }}</h2>
                    <p class="preview-desc" id="pvDesc">{{ old('description', $roomType->description) ?: 'Your description will appear here.' }}</p>
                </div>
                <div class="preview-meta">
                    <div class="preview-price"><span id="pvPrice">${{ number_format((float) old('price_per_night', $roomType->price_per_night ?? 0), 2) }}</span> <small>/ night</small></div>
                    <div class="preview-cap"><i class="bi bi-people"></i> <span id="pvCap">{{ old('capacity', $roomType->capacity ?? 2) }}</span> guests</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var name = document.getElementById('type_name');
        var price = document.getElementById('price_per_night');
        var cap = document.getElementById('capacity');
        var desc = document.getElementById('description');
        var image = document.getElementById('pvImage');
        var roomImages = {
            single: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1000&q=80',
            double: 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1000&q=80',
            deluxe: 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1000&q=80',
            suite: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1000&q=80',
            default: 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1000&q=80'
        };

        function render() {
            var roomTypeName = name.value.trim();
            var normalizedName = roomTypeName.toLowerCase();
            var imageType = ['single', 'double', 'deluxe', 'suite'].find(function (type) {
                return normalizedName.includes(type);
            }) || 'default';

            document.getElementById('pvName').textContent = roomTypeName || 'Room type name';
            document.getElementById('pvDesc').textContent = desc.value.trim() || 'Your description will appear here.';
            var p = parseFloat(price.value);
            document.getElementById('pvPrice').textContent = '$' + (isNaN(p) ? '0.00' : p.toFixed(2));
            document.getElementById('pvCap').textContent = cap.value || '0';
            if (image.src !== roomImages[imageType]) {
                image.src = roomImages[imageType];
            }
        }

        [name, price, cap, desc].forEach(function (el) { el.addEventListener('input', render); });
        render();

        // +/- capacity stepper
        document.querySelectorAll('.stepper [data-step]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var next = (parseInt(cap.value, 10) || 0) + parseInt(btn.dataset.step, 10);
                cap.value = Math.min(parseInt(cap.max, 10), Math.max(parseInt(cap.min, 10), next));
                render();
            });
        });

        // Loading state on submit
        var form = document.getElementById('typeForm');
        var save = document.getElementById('saveBtn');
        form.addEventListener('submit', function () {
            save.classList.add('is-loading');
            save.querySelector('.label').textContent = 'Saving...';
        });
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                save.classList.remove('is-loading');
                save.querySelector('.label').textContent = {!! json_encode($roomType->exists ? 'Update room type' : 'Save room type') !!};
            }
        });
    })();
</script>
@endsection