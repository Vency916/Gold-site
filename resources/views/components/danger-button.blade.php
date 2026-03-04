<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-8 py-4 bg-red-500/10 border border-red-500/20 rounded-xl font-black text-[10px] text-red-500 uppercase tracking-[0.2em] hover:bg-red-500 hover:text-white focus:outline-none focus:ring-0 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
