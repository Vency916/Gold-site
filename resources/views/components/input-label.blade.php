@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-black text-[10px] text-gray-500 uppercase tracking-[0.3em] mb-2']) }}>
    {{ $value ?? $slot }}
</label>
