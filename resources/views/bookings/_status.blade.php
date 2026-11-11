@php
    $statusConfig = [
        'checked_in' => [
            'label' => 'Checked In',
            'dot' => '#22c55e',
            'bg' => 'rgba(34, 197, 94, 0.14)',
            'color' => '#4ade80',
            'border' => 'rgba(74, 222, 128, 0.28)',
            'icon' => 'bi-door-open-fill',
        ],
        'booked' => [
            'label' => 'Booked',
            'dot' => '#eab308',
            'bg' => 'rgba(234, 179, 8, 0.14)',
            'color' => '#fde047',
            'border' => 'rgba(250, 204, 21, 0.28)',
            'icon' => 'bi-calendar-check',
        ],
        'checked_out' => [
            'label' => 'Checked Out',
            'dot' => '#94a3b8',
            'bg' => 'rgba(148, 163, 184, 0.14)',
            'color' => '#cbd5e1',
            'border' => 'rgba(148, 163, 184, 0.25)',
            'icon' => 'bi-check2-circle',
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'dot' => '#ef4444',
            'bg' => 'rgba(239, 68, 68, 0.14)',
            'color' => '#f87171',
            'border' => 'rgba(239, 68, 68, 0.28)',
            'icon' => 'bi-x-circle',
        ],
    ];
    $cfg = $statusConfig[$status] ?? [
        'label' => ucwords(str_replace('_', ' ', $status)),
        'dot' => '#d7b477',
        'bg' => 'rgba(215, 180, 119, 0.14)',
        'color' => '#f3e7d2',
        'border' => 'rgba(215, 180, 119, 0.3)',
        'icon' => 'bi-circle',
    ];
@endphp
<span class="booking-status-pill" style="display: inline-flex; align-items: center; gap: .4rem; padding: 3px 10px; border-radius: 999px; font-size: .75rem; font-weight: 500; background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }}; border: 1px solid {{ $cfg['border'] }}; white-space: nowrap;">
    <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $cfg['dot'] }}; box-shadow: 0 0 5px {{ $cfg['dot'] }};"></span>
    <i class="bi {{ $cfg['icon'] }}" style="font-size: .72rem;"></i>
    {{ $cfg['label'] }}
</span>