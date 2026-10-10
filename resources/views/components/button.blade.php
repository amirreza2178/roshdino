@props([
    'href' => null,
    'variant' => 'primary',
])

@php
    $base = 'group inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold transition duration-200 focus:outline-none focus:ring-2 focus:ring-growth/30';

    $variants = [
        'primary' => 'bg-ink text-ivory hover:-translate-y-0.5 hover:bg-growth',
        'secondary' => 'border border-ink/10 bg-white/60 text-ink hover:-translate-y-0.5 hover:bg-white',
        'lime' => 'bg-lime text-ink hover:-translate-y-0.5 hover:shadow-[0_12px_35px_rgba(183,243,106,0.25)]',
        'light' => 'bg-ivory text-ink hover:-translate-y-0.5 hover:bg-white',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <span>{{ $slot }}</span>
        <span class="transition-transform duration-200 group-hover:-translate-x-1">
            ←
        </span>
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        <span>{{ $slot }}</span>
        <span class="transition-transform duration-200 group-hover:-translate-x-1">
            ←
        </span>
    </button>
@endif