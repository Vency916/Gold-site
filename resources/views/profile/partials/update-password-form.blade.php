<section>
    <header class="space-y-2 mb-10">
        <h2 class="text-2xl font-black uppercase tracking-tighter">
            {{ __('Security Protocols') }}
        </h2>

        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">
            {{ __('Maintain high-entropy cryptographic credentials for institutional asset security.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-input-label for="update_password_current_password" :value="__('Current Authorization Code')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-[10px] font-bold uppercase tracking-widest text-red-500" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password" :value="__('New Security String')" />
            <x-text-input id="update_password_password" name="password" type="password" class="w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-[10px] font-bold uppercase tracking-widest text-red-500" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation" :value="__('Re-Verify Security String')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-[10px] font-bold uppercase tracking-widest text-red-500" />
        </div>

        <div class="flex items-center gap-6 pt-4 border-t border-white/5">
            <x-primary-button>{{ __('Rotate Security Credentials') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-gold uppercase tracking-widest"
                >{{ __('Credentials Rotated.') }}</p>
            @endif
        </div>
    </form>
</section>
