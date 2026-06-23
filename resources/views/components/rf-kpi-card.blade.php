@props([
    'title' => '',
    'value' => '',
    'trend' => null,
    'trendLabel' => null,
    'icon' => null,
    'variant' => 'default',
    'loading' => false,
])

@php
$trendDirection = $trend > 0 ? 'up' : ($trend < 0 ? 'down' : 'flat');
$trendColor = $trend > 0 ? 'text-accent' : ($trend < 0 ? 'text-error' : 'text-text-muted');
$trendIcon = $trend > 0
    ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>'
    : ($trend < 0
        ? '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>'
        : '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>');
@endphp

<div {{ $attributes->merge(['class' => 'rf-kpi-card card-elevated p-4 md:p-5 dashboard-card']) }}>
    @if($loading)
        <div class="rf-kpi-loading animate-pulse space-y-3">
            <div class="h-3 w-24 bg-border-light rounded"></div>
            <div class="h-8 w-16 bg-border-light rounded"></div>
            <div class="h-3 w-32 bg-border-light rounded"></div>
        </div>
    @else
        <div class="rf-kpi-inner">
            <div class="rf-kpi-top flex items-start justify-between mb-2">
                <span class="rf-kpi-label section-label">{{ $title }}</span>
                @if($icon)
                    <span class="rf-kpi-icon {{ match($variant) {
                        'primary' => 'text-primary',
                        'accent' => 'text-accent',
                        'warning' => 'text-warning',
                        'error' => 'text-error',
                        default => 'text-text-muted',
                    } }}" aria-hidden="true">{!! $icon !!}</span>
                @endif
            </div>

            <div class="rf-kpi-value stat-number text-text-primary">{{ $value }}</div>

            @if($trend !== null)
                <div class="rf-kpi-trend flex items-center gap-1 mt-2 {{ $trendColor }}">
                    <span class="rf-kpi-trend-icon shrink-0">{!! $trendIcon !!}</span>
                    <span class="rf-kpi-trend-value text-xs font-bold">{{ $trend > 0 ? '+' : '' }}{{ $trend }}%</span>
                    @if($trendLabel)
                        <span class="rf-kpi-trend-label text-xs text-text-muted">{{ $trendLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
