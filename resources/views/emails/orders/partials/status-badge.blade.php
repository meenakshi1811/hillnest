@php
    $styles = match($status) {
        'pending' => 'background:#F5E8C8;color:#92600A;',
        'confirmed', 'processing' => 'background:#DBEAFE;color:#1D4ED8;',
        'shipped' => 'background:#E0E7FF;color:#4338CA;',
        'delivered' => 'background:#D1FAE5;color:#047857;',
        'cancelled' => 'background:#FEE2E2;color:#B42318;',
        default => 'background:#F5E8C8;color:#92600A;',
    };
@endphp
<span style="display:inline-block;padding:8px 16px;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;{{ $styles }}">{{ $label }}</span>
