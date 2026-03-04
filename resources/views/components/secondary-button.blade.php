<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-8 py-4 bg-transparent border border-white/10 rounded-xl font-black text-[10px] text-gray-400 uppercase tracking-[0.2em] hover:bg-white/5 hover:text-white focus:outline-none focus:ring-0 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
