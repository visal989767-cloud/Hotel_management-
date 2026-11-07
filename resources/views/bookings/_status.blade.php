@php
    $map = ['booked' => 'primary', 'checked_in' => 'success', 'checked_out' => 'secondary', 'cancelled' => 'danger'];
@endphp
<span class="badge bg-{{ $map[$status] ?? 'dark' }}">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
