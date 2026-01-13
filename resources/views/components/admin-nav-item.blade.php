@props([
    'icon' => null,
    'active' => false,
    'href' => '#',
])

@php
    $baseClasses = 'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium bk-transition';
    $stateClasses = $active
        ? 'bk-nav-item-active'
        : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses.' '.$stateClasses]) }}>
    {{-- indikator samping kiri --}}
    <span class="bk-nav-indicator absolute inset-y-1 left-0
        {{ $active ? 'bg-sky-500' : 'bg-transparent group-hover:bg-slate-300' }}"></span>

    @if($icon)
        <i class="{{ $icon }} text-lg {{ $active ? 'text-sky-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
    @endif

    <span class="ml-1">{{ $slot }}</span>
</a>