<x-layouts::app :title="isset($username) ? __('Portafolio de ') . $username : __('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            <flux:card class="flex flex-col justify-between p-5 border-t-4 border-t-blue-500 bg-gradient-to-b from-blue-500/5 via-transparent to-transparent">
                <div>
                    <flux:heading size="sm" level="3" class="font-medium text-zinc-600 dark:text-zinc-400">{{ __('Total de Repositorios') }}</flux:heading>
                    <div class="text-3xl font-extrabold mt-2 text-zinc-800 dark:text-zinc-100 tracking-tight">
                        {{ $repositories->count() }}
                    </div>
                </div>
            </flux:card>

            <flux:card class="flex flex-col justify-between p-5 border-t-4 border-t-amber-500 bg-gradient-to-b from-amber-500/5 via-transparent to-transparent">
                <div>
                    <flux:heading size="sm" level="3" class="font-medium text-zinc-600 dark:text-zinc-400">{{ __('Estrellas Totales') }}</flux:heading>
                    <div class="text-3xl font-extrabold mt-2 text-amber-500 dark:text-amber-400 flex items-center gap-2 tracking-tight">
                        {{ $repositories->sum('stars_count') }} <span class="text-xl">⭐</span>
                    </div>
                </div>
            </flux:card>

            <flux:card class="flex flex-col justify-between p-5 border-t-4 border-t-orange-500 bg-gradient-to-b from-orange-500/5 via-transparent to-transparent">
                <div>
                    <flux:heading size="sm" level="3" class="font-medium text-zinc-600 dark:text-zinc-400">{{ __('Forks Totales') }}</flux:heading>
                    <div class="text-3xl font-extrabold mt-2 text-orange-500 dark:text-orange-400 tracking-tight">
                        {{ $repositories->sum('forks_count') }}
                    </div>
                </div>
            </flux:card>
        </div>

        <div class="flex-1 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900/50 space-y-6">
            <div>
                <flux:heading size="lg" level="2" class="font-bold tracking-tight">
                    {{ isset($username) ? __('Proyectos de ') . $username : __('Tus Proyectos en GitHub') }}
                </flux:heading>
                <flux:subheading size="sm">
                    {{ isset($username) ? __('Catálogo público indexado en la aplicación') : __('Listado sincronizado desde tu cuenta de desarrollador') }}
                </flux:subheading>
            </div>

            @if($repositories->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center border border-dashed border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <p class="text-zinc-500 dark:text-zinc-400 text-sm mb-2">{{ __('No hay repositorios vinculados aún.') }}</p>
                    @if(!isset($username))
                        <flux:subheading size="sm">{{ __('Ve a Ajustes de GitHub para importar tu catálogo.') }}</flux:subheading>
                    @endif
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">
                    @foreach($repositories->sortByDesc('github_updated_at') as $repo)

                        <flux:card class="flex flex-col justify-between p-5 space-y-4 shadow-sm hover:shadow-md transition-all border-t-2 border-t-zinc-300 dark:border-t-zinc-700 bg-gradient-to-b from-zinc-500/[0.02] to-transparent">

                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <flux:heading size="md" level="3" class="truncate font-semibold text-zinc-900 dark:text-zinc-100">
                                        {{ $repo->name }}
                                    </flux:heading>

                                    @if($repo->is_fork)
                                        <flux:badge color="zinc" variant="outline" size="sm" class="text-[10px] uppercase tracking-wider font-semibold">{{ __('Fork') }}</flux:badge>
                                    @endif
                                </div>

                                <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2 min-h-[2rem] leading-relaxed">
                                    {{ $repo->description ?? __('Sin descripción disponible en el repositorio.') }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-zinc-100 dark:border-zinc-800 text-xs">
                                <div class="flex items-center gap-3 text-zinc-600 dark:text-zinc-400">
                                    @if($repo->primary_language)
                                        <span class="flex items-center gap-1.5 font-medium">
                                            <span class="size-2 rounded-full bg-blue-500 dark:bg-blue-400 shadow-sm"></span>
                                            {{ $repo->primary_language }}
                                        </span>
                                    @endif

                                    @if($repo->stars_count > 0)
                                        <span class="flex items-center gap-1 text-amber-500 font-semibold">
                                            {{ $repo->stars_count }} ⭐
                                        </span>
                                    @endif
                                </div>

                                <flux:button
                                    href="{{ $repo->html_url }}"
                                    target="_blank"
                                    variant="subtle"
                                    size="sm"
                                    icon="arrow-up-right"
                                    icon-trailing
                                    class="text-zinc-700 dark:text-zinc-300 hover:text-blue-500 dark:hover:text-blue-400"
                                >
                                    {{ __('Ver') }}
                                </flux:button>
                            </div>

                        </flux:card>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-layouts::app>
