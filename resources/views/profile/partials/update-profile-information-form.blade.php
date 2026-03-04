<section>
    <header class="space-y-2 mb-10">
        <h2 class="text-2xl font-black uppercase tracking-tighter">
            {{ __('Asset Registry Identity') }}
        </h2>

        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">
            {{ __("Manage your account's institutional identification and digital credentials.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" name="name" type="text" class="w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-[10px] font-bold uppercase tracking-widest text-red-500" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <x-input-label for="email" :value="__('Registry Email')" />
            <x-text-input id="email" name="email" type="email" class="w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-[10px] font-bold uppercase tracking-widest text-red-500" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-white/5 border border-white/10 rounded-xl">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        {{ __('Registry credentials unverified.') }}

                        <button form="send-verification" class="text-gold hover:underline underline-offset-4 focus:outline-none ml-2">
                            {{ __('Authorize Verification Transmission') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-[10px] font-black text-green-500 uppercase tracking-widest leading-loose">
                            {{ __('Verification protocol initiated. Check your digital inbox.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="space-y-2">
            <x-input-label for="currency" :value="__('Institutional Currency')" />
            <select id="currency" name="currency" class="w-full bg-black border border-white/10 rounded-xl py-3 px-4 text-sm font-bold focus:border-gold focus:ring-0 transition-all">
                <option value="USD" {{ old('currency', $user->currency) === 'USD' ? 'selected' : '' }}>USD - United States Dollar</option>
                <option value="GBP" {{ old('currency', $user->currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound Sterling</option>
            </select>
            <x-input-error class="mt-2 text-[10px] font-bold uppercase tracking-widest text-red-500" :messages="$errors->get('currency')" />
        </div>

            <x-primary-button>{{ __('Authorize Identification Update') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-gold uppercase tracking-widest"
                >{{ __('Registry Updated.') }}</p>
            @endif
        </div>
    </form>
</section>
