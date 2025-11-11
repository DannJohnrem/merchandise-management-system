<x-layouts.auth>
    <div class="flex flex-col gap-6"> <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" /> <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" /> <!-- Login Form -->
        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6" x-data="{ loading: false }"
            @submit="loading = true"> @csrf <!-- Email Address -->
            <flux:input name="email" :label="__('Email address')" type="email" required autofocus autocomplete="email"
                placeholder="email@example.com" /> <!-- Password -->
            <div class="relative">
                <flux:input name="password" :label="__('Password')" type="password" required
                    autocomplete="current-password" :placeholder="__('Password')" viewable />
                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }} </flux:link>
                    @endif
            </div> <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />
            <!-- Submit Button with Spinner Only -->
            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full flex items-center justify-center"
                    data-test="login-button" x-bind:disabled="loading"> <!-- Default text (hidden when loading) -->
                    <span x-show="!loading">{{ __('Log in') }}</span> </flux:button>
            </div>
        </form> <!-- Register Link -->
        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" wire:navigate> {{ __('Sign up') }} </flux:link>
            </div>
        @endif
    </div>
</x-layouts.auth>
