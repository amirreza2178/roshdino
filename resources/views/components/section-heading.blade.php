@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'dark' => false,
])

<div {{ $attributes->merge(['class' => 'max-w-3xl']) }}>
    @if ($eyebrow)
        <span
            class="mb-4 inline-flex items-center gap-2 text-xs font-black tracking-[0.18em] {{ $dark ? 'text-lime' : 'text-growth' }}"
        >
            <span class="h-1.5 w-1.5 rounded-full {{ $dark ? 'bg-lime' : 'bg-growth' }}"></span>
            {{ $eyebrow }}
        </span>
    @endif

    <h2
        class="text-3xl font-black leading-[1.2] tracking-tight sm:text-4xl lg:text-5xl {{ $dark ? 'text-ivory' : 'text-ink' }}"
    >
        {{ $title }}
    </h2>

    @if ($description)
        <p
            class="mt-5 max-w-2xl text-base leading-8 sm:text-lg {{ $dark ? 'text-ivory/60' : 'text-ink/60' }}"
        >
            {{ $description }}
        </p>
    @endif
</div>