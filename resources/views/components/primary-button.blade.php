<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-8 py-4 bg-white border border-transparent rounded-xl font-black text-[10px] text-black uppercase tracking-[0.2em] hover:bg-gold focus:bg-gold active:bg-gold/80 focus:outline-none focus:ring-0 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
