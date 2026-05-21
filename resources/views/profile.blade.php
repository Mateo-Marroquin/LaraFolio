<x-layouts::app :title="__('Perfil de GitHub')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <flux:card class="max-w-2xl w-full mx-auto">
            <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start text-center sm:text-left">

                <flux:avatar
                    circle
                    class="w-40 h-40"
                    src="{{ $profileInfo->avatar_url }}"
                    initials="{{ Str::of($profileInfo->name ?? $profileInfo->username)->substr(0, 2)->upper() }}"
                />

                <div class="flex-1 space-y-2 w-full">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <flux:heading size="xl" level="2">
                                {{ $profileInfo->name ?? $profileInfo->username }}
                            </flux:heading>
                            <flux:subheading class="font-mono">
                                <a href="https://github.com/{{ $profileInfo->username }}" target="_blank"
                                   class="hover:underline flex items-center justify-center sm:justify-start gap-1">
                                    @github {{ $profileInfo->username }}
                                    <flux:icon.arrow-top-right-on-square variant="micro" class="text-zinc-400"/>
                                </a>
                            </flux:subheading>
                        </div>

                        <flux:button
                            href="https://github.com/{{ $profileInfo->username }}"
                            target="_blank"
                            variant="filled"
                            size="sm"
                        >
                            Ver en GitHub
                        </flux:button>
                    </div>

                    @if($profileInfo->location)
                        <div
                            class="flex items-center justify-center sm:justify-start gap-1.5 text-sm text-zinc-500 dark:text-zinc-400">
                            <flux:icon.map-pin variant="mini"/>
                            <span>{{ $profileInfo->location }}</span>
                        </div>
                    @endif

                    @if($profileInfo->bio)
                        <p class="text-sm text-zinc-600 dark:text-zinc-300 pt-2 leading-relaxed">
                            {{ $profileInfo->bio }}
                        </p>
                    @endif

                    <flux:separator class="my-4"/>

                    <div
                        class="grid grid-cols-1 {{ isset($isOwner) && $isOwner ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }} gap-4 pt-2">
                        <div
                            class="bg-zinc-50 dark:bg-zinc-900 p-3 rounded-lg border border-zinc-200/60 dark:border-zinc-800/50 flex items-center gap-3">
                            <div class="p-2 bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 rounded-md">
                                <flux:icon.folder variant="mini"/>
                            </div>
                            <div>
                                <flux:subheading size="sm"
                                                 class="block">{{ __('Repositorios públicos') }}</flux:subheading>
                                <span
                                    class="text-lg font-bold text-zinc-800 dark:text-zinc-100">{{ number_format($profileInfo->public_repos) }}</span>
                            </div>
                        </div>

                        {{-- 🌟 NUEVA TARJETA EXCLUSIVA PARA EL DUEÑO LOGUEADO --}}
                        @if(isset($isOwner) && $isOwner)
                            <div
                                class="bg-zinc-50 dark:bg-zinc-900 p-3 rounded-lg border border-zinc-200/60 dark:border-zinc-800/50 flex items-center gap-3">
                                <div class="p-2 bg-red-50 dark:bg-red-950 text-red-600 dark:text-red-400 rounded-md">
                                    <flux:icon.lock-closed variant="mini"/>
                                </div>
                                <div>
                                    <flux:subheading size="sm"
                                                     class="block">{{ __('Repos privados') }}</flux:subheading>
                                    <span
                                        class="text-lg font-bold text-zinc-800 dark:text-zinc-100">{{ number_format($privateReposCount) }}</span>
                                </div>
                            </div>
                        @endif

                        <div
                            class="bg-zinc-50 dark:bg-zinc-900 p-3 rounded-lg border border-zinc-200/60 dark:border-zinc-800/50 flex items-center gap-3">
                            <div
                                class="p-2 bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 rounded-md">
                                <flux:icon.users variant="mini"/>
                            </div>
                            <div>
                                <flux:subheading size="sm" class="block">{{ __('Seguidores') }}</flux:subheading>
                                <span
                                    class="text-lg font-bold text-zinc-800 dark:text-zinc-100">{{ number_format($profileInfo->followers) }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </flux:card>

    </div>
</x-layouts::app>
