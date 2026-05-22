<x-layouts::app :title="__('Perfil de GitHub')">
    <div class="flex h-full w-full flex-1 flex-col gap-8 rounded-xl">

        <flux:card class="max-w-3xl w-full mx-auto p-6 sm:p-8 border-t-4 border-t-blue-500 bg-gradient-to-b from-blue-500/5 via-transparent to-transparent">
            <div class="flex flex-col sm:flex-row gap-8 sm:gap-10 items-center sm:items-start text-center sm:text-left">

                <flux:avatar
                    circle
                    class="w-40 h-40 shrink-0 shadow-sm"
                    src="{{ $profileInfo->avatar_url }}"
                    initials="{{ Str::of($profileInfo->name ?? $profileInfo->username)->substr(0, 2)->upper() }}"
                />

                <div class="flex-1 space-y-4 w-full">

                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                        <div class="space-y-1.5">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                                <flux:heading size="xl" level="2" class="text-2xl sm:text-3xl">
                                    {{ $profileInfo->name ?? $profileInfo->username }}
                                </flux:heading>

                                @if($profileInfo->hireable)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 px-2.5 py-1 rounded-md text-xs font-semibold w-fit mx-auto sm:mx-0 border border-emerald-200/50 dark:border-emerald-800/30">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        {{ __('Abierto a proyectos') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-zinc-100 text-zinc-600 dark:bg-zinc-800/60 dark:text-zinc-400 px-2.5 py-1 rounded-md text-xs font-medium w-fit mx-auto sm:mx-0 border border-zinc-200/40 dark:border-zinc-700/30">
                                        {{ __('Trabajando en proyecto') }}
                                    </span>
                                @endif
                            </div>

                            <flux:subheading class="font-mono text-base pt-1">
                                <a href="https://github.com/{{ $profileInfo->username }}" target="_blank"
                                   class="hover:text-blue-500 hover:underline flex items-center justify-center sm:justify-start gap-1.5 transition-colors">
                                    @github {{ $profileInfo->username }}
                                    <flux:icon.arrow-top-right-on-square variant="micro" class="text-zinc-400 opacity-70"/>
                                </a>
                            </flux:subheading>
                        </div>

                        <flux:button
                            href="https://github.com/{{ $profileInfo->username }}"
                            target="_blank"
                            variant="filled"
                            size="sm"
                            class="mt-2 sm:mt-0"
                        >
                            Ver en GitHub
                        </flux:button>
                    </div>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-6 gap-y-3 mt-6 pt-2 text-sm text-zinc-500 dark:text-zinc-400">

                        <div class="flex items-center gap-2">
                            <flux:icon.map-pin variant="mini" class="text-zinc-400 dark:text-zinc-500"/>
                            <span>{{ $profileInfo->location ?? __('Remoto / Global') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:icon.briefcase variant="mini" class="text-zinc-400 dark:text-zinc-500"/>
                            <span>{{ $profileInfo->company ?? __('Desarrollador Independiente') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:icon.link variant="mini" class="text-zinc-400 dark:text-zinc-500"/>
                            @if($profileInfo->blog)
                                <a href="{{ Str::startsWith($profileInfo->blog, ['http://', 'https://']) ? $profileInfo->blog : 'https://' . $profileInfo->blog }}" target="_blank" class="hover:text-blue-500 hover:underline truncate max-w-[180px] transition-colors">
                                    {{ str_replace(['https://', 'http://', 'www.'], '', $profileInfo->blog) }}
                                </a>
                            @else
                                <span class="text-zinc-400 dark:text-zinc-600 italic text-xs">{{ __('Sin sitio web') }}</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:icon.chat-bubble-left-right variant="mini" class="text-zinc-400 dark:text-zinc-500"/>
                            @if($profileInfo->twitter_username)
                                <a href="https://x.com/{{ $profileInfo->twitter_username }}" target="_blank" class="hover:text-blue-500 hover:underline truncate max-w-[150px] transition-colors">
                                    @{{ $profileInfo->twitter_username }}
                                </a>
                            @else
                                <span class="text-zinc-400 dark:text-zinc-600 italic text-xs">{{ __('Sin Twitter') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($profileInfo->bio)
                        <p class="text-sm md:text-base text-zinc-600 dark:text-zinc-300 mt-6 leading-relaxed">
                            {{ $profileInfo->bio }}
                        </p>
                    @endif

                    <flux:separator class="my-8"/>

                    <div class="grid grid-cols-1 {{ isset($isOwner) && $isOwner ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }} gap-6 pt-2">

                        <div class="p-5 rounded-xl border border-zinc-200/60 dark:border-zinc-800/50 bg-gradient-to-b from-blue-500/[0.03] to-transparent dark:from-blue-500/[0.06] flex items-center gap-4 transition-all hover:shadow-sm">
                            <div class="p-3 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-lg">
                                <flux:icon.folder variant="mini" class="size-5" />
                            </div>
                            <div class="space-y-0.5">
                                <flux:subheading size="sm" class="block font-medium">{{ __('Repos públicos') }}</flux:subheading>
                                <span class="text-xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight">{{ number_format($profileInfo->public_repos) }}</span>
                            </div>
                        </div>

                        @if(isset($isOwner) && $isOwner)
                            <div class="p-5 rounded-xl border border-zinc-200/60 dark:border-zinc-800/50 bg-gradient-to-b from-red-500/[0.03] to-transparent dark:from-red-500/[0.06] flex items-center gap-4 transition-all hover:shadow-sm">
                                <div class="p-3 bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 rounded-lg">
                                    <flux:icon.lock-closed variant="mini" class="size-5" />
                                </div>
                                <div class="space-y-0.5">
                                    <flux:subheading size="sm" class="block font-medium">{{ __('Repos privados') }}</flux:subheading>
                                    <span class="text-xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight">{{ number_format($privateReposCount) }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="p-5 rounded-xl border border-zinc-200/60 dark:border-zinc-800/50 bg-gradient-to-b from-purple-500/[0.03] to-transparent dark:from-purple-500/[0.06] flex items-center gap-4 transition-all hover:shadow-sm">
                            <div class="p-3 bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 rounded-lg">
                                <flux:icon.users variant="mini" class="size-5" />
                            </div>
                            <div class="space-y-0.5">
                                <flux:subheading size="sm" class="block font-medium">{{ __('Seguidores') }}</flux:subheading>
                                <span class="text-xl font-bold text-zinc-800 dark:text-zinc-100 tracking-tight">{{ number_format($profileInfo->followers) }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </flux:card>

        <div class="max-w-3xl w-full mx-auto mt-4 space-y-4">
            <div class="px-2">
                <flux:heading size="lg" level="3" class="font-bold tracking-tight">{{ __('Métricas de Productividad') }}</flux:heading>
                <flux:subheading size="sm">{{ __('Indicadores automatizados calculados en base a tu volumen de desarrollo real.') }}</flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <flux:card class="p-5 flex flex-col justify-between border-t-4 border-t-indigo-500 bg-gradient-to-b from-indigo-500/5 to-transparent dark:from-indigo-500/10">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-lg">
                            <flux:icon.academic-cap variant="mini" class="size-5" />
                        </div>
                        <flux:subheading size="sm" class="font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Enfoque Técnico') }}</flux:subheading>
                    </div>
                    <div class="mt-4">
                        <span class="text-lg font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight block">
                            {{ $specialtyRole }}
                        </span>
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">
                            {{ __('Determinado por la densidad de bytes de tu lenguaje principal.') }}
                        </p>
                    </div>
                </flux:card>

                <flux:card class="p-5 flex flex-col justify-between border-t-4 border-t-blue-500 bg-gradient-to-b from-blue-500/5 to-transparent dark:from-blue-500/10">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-lg">
                            <flux:icon.cpu-chip variant="mini" class="size-5" />
                        </div>
                        <flux:subheading size="sm" class="font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Código Analizado') }}</flux:subheading>
                    </div>
                    <div class="mt-4">
                        <span class="text-lg font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight block">
                            @if($totalBytes >= 1024 * 1024)
                                {{ number_format($totalBytes / (1024 * 1024), 2) }} <span class="text-sm font-normal text-zinc-500">MB</span>
                            @else
                                {{ number_format($totalBytes / 1024, 2) }} <span class="text-sm font-normal text-zinc-500">KB</span>
                            @endif
                        </span>
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">
                            {{ __('Peso total del árbol de archivos fuente indexados localmente.') }}
                        </p>
                    </div>
                </flux:card>

                <flux:card class="p-5 flex flex-col justify-between border-t-4 border-t-purple-500 bg-gradient-to-b from-purple-500/5 to-transparent dark:from-purple-500/10">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 rounded-lg">
                            <flux:icon.square-3-stack-3d variant="mini" class="size-5" />
                        </div>
                        <flux:subheading size="sm" class="font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Diversidad de Stack') }}</flux:subheading>
                    </div>
                    <div class="mt-4">
                        <span class="text-lg font-extrabold text-zinc-900 dark:text-zinc-100 tracking-tight block">
                            {{ $totalLanguagesCount }} <span class="text-sm font-normal text-zinc-500">{{ __($totalLanguagesCount === 1 ? 'Lenguaje' : 'Lenguajes') }}</span>
                        </span>
                        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">
                            {{ __('Tecnologías detectadas activamente a lo largo de tus proyectos.') }}
                        </p>
                    </div>
                </flux:card>

            </div>
        </div>

    </div>
</x-layouts::app>
