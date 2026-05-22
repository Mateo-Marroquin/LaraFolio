<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'LaraFolio') }} - Analizador de GitHub</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-zinc-900 dark:text-zinc-100 flex flex-col min-h-screen">

<header class="w-full p-6 flex justify-end items-center absolute top-0 left-0 right-0 gap-4">

    <div x-data="{}">
        <flux:button
            variant="subtle"
            square
            icon="moon"
            class="hidden dark:inline-flex shadow-sm"
            @click="localStorage.setItem('theme', 'dark'); document.documentElement.classList.add('dark'); window.dispatchEvent(new CustomEvent('theme-changed', { detail: 'dark' }))"
            aria-label="{{ __('Cambiar a modo oscuro') }}"
        />

        <flux:button
            variant="subtle"
            square
            icon="sun"
            class="hidden dark:inline-flex shadow-sm"
            @click="localStorage.setItem('theme', 'light'); document.documentElement.classList.remove('dark'); window.dispatchEvent(new CustomEvent('theme-changed', { detail: 'light' }))"
            aria-label="{{ __('Cambiar a modo claro') }}"
        />
    </div>

    <div class="h-4 w-px bg-zinc-200 dark:bg-zinc-800 hidden sm:block"></div>

    @auth
        <span class="hidden sm:inline text-xs md:text-sm text-zinc-500 dark:text-zinc-400 font-medium">
            {{ __('¡Hola de nuevo, :name!', ['name' => auth()->user()->name]) }}
        </span>

        <flux:button
            variant="primary"
            icon="user"
            href="/user/{{ auth()->user()->githubProvider->username ?? auth()->user()->name }}"
            class="shadow-sm"
        >
            {{ __('Ir a mi perfil') }}
        </flux:button>
    @endauth

    @guest
        <span class="hidden sm:inline text-xs md:text-sm text-zinc-500 dark:text-zinc-400 font-medium">
            ¿Quieres acceder a más información de tu perfil?
        </span>

        <flux:button
            variant="primary"
            icon="cloud-arrow-down"
            href="{{ route('auth.github.redirect') }}"
            class="shadow-sm"
        >
            {{ __('Inicia sesión con GitHub') }}
        </flux:button>
    @endguest
</header>

<main class="flex-1 flex flex-col items-center justify-center w-full px-6 relative mt-16">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-blue-500/10 dark:bg-blue-500/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-3xl w-full space-y-10 text-center relative z-10">

        <div class="space-y-4">
            <flux:heading size="xl" level="1" class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight">
                Explora el código detrás <br class="hidden sm:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500 dark:from-blue-400 dark:to-indigo-400">
                    del desarrollador.
                </span>
            </flux:heading>

            <p class="text-lg md:text-xl text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                Ingresa cualquier nombre de usuario de GitHub para descubrir sus lenguajes más utilizados, estadísticas y repositorios públicos.
            </p>
        </div>

        <div x-data="{ username: '' }">
            <form
                @submit.prevent="window.location.href = `/user/${username}`"
                class="flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto"
            >
                <div class="flex-1">
                    <flux:input
                        name="username"
                        autocomplete="username"
                        x-model="username"
                        icon="magnifying-glass"
                        placeholder="Ej. Mateo-Marroquin"
                        size="lg"
                        class="w-full shadow-sm"
                        required
                    />
                </div>

                <flux:button type="submit" variant="primary" class="w-full sm:w-auto shadow-sm">
                    {{ __('Analizar perfil') }}
                </flux:button>
            </form>
        </div>

        <div class="pt-8 text-sm text-zinc-500 dark:text-zinc-500">
            <p>No se requiere registro para consultar perfiles públicos.</p>
        </div>
    </div>
</main>

@fluxScripts
</body>
</html>
