<x-app-layout :suppress-global-nav="false">
    <div class="pt-24 pb-32 bg-[#080808] min-h-screen text-white font-sans overflow-x-hidden" x-data="{}">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-12">
            <!-- Institutional Header -->
            <div class="flex items-center space-x-3">
                <span class="w-1.5 h-1.5 bg-gold rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em]">Asset Security Registry</span>
            </div>
            <h1 class="text-5xl font-black tracking-tighter uppercase leading-none mb-12">Profile <span class="text-gold">Intelligence</span></h1>

            <div class="space-y-12">
                <div class="luxury-card p-10 md:p-16">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="luxury-card p-10 md:p-16 text-white">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="luxury-card p-10 md:p-16 border-red-500/10 bg-red-500/[0.02]">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>

        <x-bottom-nav-dock />
    </div>
</x-app-layout>
