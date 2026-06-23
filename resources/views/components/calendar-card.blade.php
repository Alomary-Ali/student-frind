@props([
    'event' => null,
])

<div class="unit-card p-4 hover:shadow-md transition-shadow">
    @if($event)
        <div class="flex items-start justify-between mb-2">
            <h3 class="font-semibold text-base text-text-primary">{{ $event->title }}</h3>
            @if($event->event_type)
                <span class="text-xs px-2 py-1 rounded-full badge-primary">{{ $event->event_type }}</span>
            @endif
        </div>
        <div class="flex items-center gap-2 text-sm mb-2 text-text-muted">
            @if($event->start_date)
                <span><svg class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d H:i') }}</span>
            @endif
            @if($event->end_date)
                <span><svg class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> {{ \Carbon\Carbon::parse($event->end_date)->format('Y-m-d H:i') }}</span>
            @endif
        </div>
        @if($event->location)
            <p class="text-sm text-text-muted"><svg class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> {{ $event->location }}</p>
        @endif
        @if($event->description)
            <p class="text-sm mt-2 text-text-muted">{{ $event->description }}</p>
        @endif
    @endif
</div>
