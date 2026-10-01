{{-- Percentage cell with a comparable bar. Shared by every report table.
     Thresholds: green >= 90% · amber 70-89% · red < 70%. --}}
@php
    $clamped = max(0, min(100, (float) $value));
    $tone = $clamped >= 90 ? 'att-pct-ok' : ($clamped >= 70 ? 'att-pct-warn' : 'att-pct-bad');
@endphp
<div class="att-pct-wrap">
    <div class="att-pct">
        <span class="{{ $tone }}">{{ number_format((float) $value, 1) }}%</span>
    </div>
    <div class="att-pct-bar">
        <div class="att-pct-fill {{ $tone }}" style="width:{{ $clamped }}%"></div>
    </div>
</div>
