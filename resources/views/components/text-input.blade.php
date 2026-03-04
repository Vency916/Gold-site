@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-black border-white/10 rounded-xl py-4 px-6 text-sm font-bold text-white focus:border-gold focus:ring-0 transition-all placeholder-gray-800']) !!}>
