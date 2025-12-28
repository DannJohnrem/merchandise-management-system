<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @livewireStyles
    @rappasoftTableStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Pages')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navlist.item>
            </flux:navlist.group>

            {{-- IT Leasing --}}
            <flux:navlist.group class="grid">
                <flux:navlist.item icon="computer-desktop" :href="route('it-leasing.index')"
                    :current="request()->routeIs('it-leasing.*')" wire:navigate>
                    {{ __('Leasing Management') }}
                </flux:navlist.item>
            </flux:navlist.group>

            {{-- Fixed Asset --}}
            <flux:navlist.group class="grid">
                <flux:navlist.item icon="rectangle-stack" :href="route('fixed-asset.index')"
                    :current="request()->routeIs('fixed-asset.*')" wire:navigate>
                    {{ __('Fixed Asset') }}
                </flux:navlist.item>
            </flux:navlist.group>

            {{-- Administration --}}
            <flux:navlist.group expandable heading="Administration" class="grid">

                @can('view users')
                    <flux:navlist.item icon="users" :href="route('admin.users.index')"
                        :current="request()->routeIs('admin.users.*')" wire:navigate>
                        {{ __('Users') }}
                    </flux:navlist.item>
                @endcan

                @can('view roles')
                    <flux:navlist.item icon="shield-check" :href="route('admin.roles.index')"
                        :current="request()->routeIs('admin.roles.*')" wire:navigate>
                        {{ __('Roles') }}
                    </flux:navlist.item>
                @endcan

                @can('view permissions')
                    <flux:navlist.item icon="key" :href="route('admin.permissions.index')"
                        :current="request()->routeIs('admin.permissions.*')" wire:navigate>
                        {{ __('Permissions') }}
                    </flux:navlist.item>
                @endcan

                <flux:navlist.item icon="user-group" :href="route('admin.class.index')"
                    :current="request()->routeIs('admin.class.*')" wire:navigate>
                    {{ __('Class') }}
                </flux:navlist.item>

                <flux:navlist.item icon="clipboard-document-list" :href="route('admin.activity-log.index')"
                    :current="request()->routeIs('admin.activity-log.*')" wire:navigate>
                    {{ __('Activity Logs') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    {{-- Scripts --}}
    @fluxScripts
    @livewireScripts
    @rappasoftTableScripts

    <script>
        if (!window.__toastListenerAdded) {
            window.__toastListenerAdded = true;

            // ✅ Listen for Livewire toast event
            Livewire.on('toast', ({
                message,
                type = 'success'
            }) => {
                if (message && typeof window.toast === 'function') {
                    window.toast(message, type);
                }
            });

            // ✅ Keep toast container persistent after navigation
            document.addEventListener('livewire:navigate', () => {
                const container = document.getElementById('toast-container');
                if (container) document.body.appendChild(container);
            });

            document.addEventListener('inertia:navigate', () => {
                const container = document.getElementById('toast-container');
                if (container) document.body.appendChild(container);
            });
        }
    </script>
</body>

</html>
