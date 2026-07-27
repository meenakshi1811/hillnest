@php
    $styles = match($status) {
        'pending' => 'background:#F5E8C8;color:#92600A;border:1px solid #E8D9BC;',
        'confirmed', 'processing' => 'background:#DBEAFE;color:#1D4ED8;border:1px solid #BFDBFE;',
        'shipped' => 'background:#E0E7FF;color:#4338CA;border:1px solid #C7D2FE;',
        'delivered' => 'background:#D1FAE5;color:#047857;border:1px solid #A7F3D0;',
        'cancelled' => 'background:#FEE2E2;color:#B42318;border:1px solid #FECACA;',
        default => 'background:#F5E8C8;color:#92600A;border:1px solid #E8D9BC;',
    };
@endphp
<span style="display:inline-block;padding:9px 18px;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;{{ $styles }}">{{ $label }}</span>
