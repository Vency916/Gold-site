<nav x-data="{ open: false }" class="fixed w-full z-[100] transition-all duration-500 py-3 px-6 md:px-12 bg-black/60 backdrop-blur-2xl border-b border-white/5 font-sans">
    <div class="max-w-screen-2xl mx-auto flex justify-between items-center h-12">
        <!-- Left Nav (Desktop) -->
        <div class="hidden md:flex space-x-12 text-[10px] font-bold uppercase tracking-[0.3em] text-gray-500">
            <a href="{{ url('/#marketplace') }}" class="hover:text-gold transition-colors">Market</a>
            <a href="{{ route('dashboard') }}" class="hover:text-gold transition-colors {{ request()->routeIs('dashboard') ? 'text-gold' : '' }}">Vault</a>
        </div>

        <!-- Center Logo -->
        <div class="md:absolute md:left-1/2 md:-translate-x-1/2 flex items-center space-x-3 group">
            <a href="{{ url('/') }}" class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full border border-gold flex items-center justify-center p-1 group-hover:rotate-180 transition-transform duration-1000">
                    <div class="w-full h-full rounded-full bg-gold"></div>
                </div>
                @php
                    $words = explode(' ', $siteName, 2);
                @endphp
                @if(count($words) > 1)
                    <span class="text-2xl font-bold tracking-tighter text-white uppercase">{{ $words[0] }} <span class="text-gold">{{ $words[1] }}</span></span>
                @else
                    <span class="text-2xl font-bold tracking-tighter text-white uppercase text-gold">{{ $siteName }}</span>
                @endif
            </a>
        </div>
        
        <!-- Right Utilities -->
        <div class="flex items-center space-x-6 md:space-x-8">
            <!-- Cart Icon -->
            <a href="{{ route('cart.index') }}" class="relative group flex items-center">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gold transition-colors md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="hidden md:block text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 group-hover:text-gold transition-colors">Cart</span>
                @if(count(Session::get('cart', [])) > 0)
                <span class="absolute -top-2 -right-2 text-[8px] font-black text-gold">({{ count(Session::get('cart', [])) }})</span>
                @endif
            </a>

            <!-- Account Dropdown -->
            <div class="hidden md:flex items-center">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center text-[10px] font-bold uppercase tracking-[0.3em] text-gold border border-gold/20 px-4 py-2 hover:bg-gold hover:text-black transition-all">
                                <div>{{ Auth::user()->name }}</div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-gold">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-red-500"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 hover:text-white transition-colors">Account</a>
                @endauth
            </div>

            <!-- Hamburger (Mobile Only) -->
            <button @click="open = !open" class="md:hidden text-gray-400 p-2 -mr-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M4 16h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden pt-8 pb-12 space-y-8 text-center bg-black/95 absolute top-full left-0 w-full border-b border-white/5">
        <a href="{{ url('/#marketplace') }}" @click="open = false" class="block text-[12px] font-bold uppercase tracking-[0.4em] text-gray-400 hover:text-gold transition-colors">Market</a>
        <a href="{{ route('dashboard') }}" class="block text-[12px] font-bold uppercase tracking-[0.4em] text-gray-400 hover:text-gold transition-colors">Vault</a>
        <div class="h-[1px] w-12 bg-white/10 mx-auto"></div>
        @auth
            <div class="text-[10px] font-bold uppercase tracking-widest text-gold mb-4">{{ Auth::user()->name }}</div>
            <a href="{{ route('profile.edit') }}" class="block text-[10px] font-bold uppercase tracking-widest text-gray-400">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Log Out</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="inline-block text-[10px] font-bold uppercase tracking-[0.4em] text-white border border-white/20 px-8 py-3">Sign In</a>
        @endauth
    </div>
</nav>
