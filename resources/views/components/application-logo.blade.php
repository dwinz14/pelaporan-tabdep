@props([
    'alt' => config('app.name'),
])

<img src="{{ asset('img/logo.png') }}" alt="{{ $alt }}" loading="lazy" decoding="async"
    {{ $attributes->class(['h-10', 'w-auto', 'select-none']) }}>
