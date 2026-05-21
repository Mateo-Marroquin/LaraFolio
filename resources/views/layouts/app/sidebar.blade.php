<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-brand-bg dark:bg-brand-bg">
<flux:sidebar sticky collapsible="mobile"
              class="border-e border-zinc-200 bg-brand-accent dark:border-zinc-700 dark:bg-zinc-900 m-5 rounded-xl">
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate/>
        <flux:sidebar.collapse class="lg:hidden"/>
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Explora')" class="grid">

            @php
                $currentUsername = session('active_search_username') ?? (auth()->user()->githubProvider->username ?? null);
            @endphp

            @if($currentUsername)
                <flux:sidebar.item icon="user" :href="route('public.profile', $currentUsername)" :current="request()->routeIs('public.profile')">
                    {{ __('Perfil') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="folder" :href="route('public.repositories', $currentUsername)" :current="request()->routeIs('public.repositories')">
                    {{ __('Repositorios') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="chart-bar" :href="route('metrics', $currentUsername)" :current="request()->routeIs('metrics')">
                    {{ __('Métricas') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="envelope" :href="route('public.contact', $currentUsername)" :current="request()->routeIs('public.contact')">
                    {{ __('Contactame') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="document" :href="route('public.profile', $currentUsername)" :current="request()->routeIs('public.profile')">
                    {{ __('Resumen PDF') }}
                </flux:sidebar.item>
            @else
                <p class="text-xs text-zinc-500 px-3">Busca un usuario para comenzar</p>
            @endif
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer/>

    @auth
        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name"/>
    @else
        <div class="hidden lg:block px-4 py-3">
            <flux:button variant="subtle" icon="cloud-arrow-down" href="#" class="w-full">
                {{ __('Iniciar Sesión') }}
            </flux:button>
            <flux:button variant="subtle" icon="chevron-left" href="{{route('home')}}" class="w-full">
                {{ __('Buscar otro perfil') }}
            </flux:button>
        </div>
    @endauth
</flux:sidebar>

<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <flux:spacer/>

    @auth
        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
            />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                            />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button"
                    >
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    @else
        <flux:button variant="subtle" size="sm" icon="cloud-arrow-down" href="#">
            {{ __('Login') }}
        </flux:button>
    @endauth
</flux:header>

{{ $slot }}

@persist('toast')
<flux:toast.group>
    <flux:toast/>
</flux:toast.group>
@endpersist

@fluxScripts
</body>
</html>
