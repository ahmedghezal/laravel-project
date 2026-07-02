@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-3 w-full px-4 py-3 rounded-xl text-base font-medium text-white bg-gradient-to-r from-indigo-500/20 to-purple-500/20 border border-indigo-500/30 transition-all duration-300'
            : 'inline-flex items-center gap-3 w-full px-4 py-3 rounded-xl text-base font-medium text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
