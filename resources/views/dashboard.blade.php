<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <flux:card class="flex flex-col justify-between">
                <div>
                    <flux:heading size="sm" level="3">{{ __('Total de Repositorios') }}</flux:heading>
                    <div class="text-3xl font-bold mt-2 text-zinc-800 dark:text-zinc-100">
                        {{ auth()->user()->githubRepositories->count() }}
                    </div>
                </div>
            </flux:card>

            <flux:card class="flex flex-col justify-between">
                <div>
                    <flux:heading size="sm" level="3">{{ __('Estrellas Totales') }}</flux:heading>
                    <div class="text-3xl font-bold mt-2 text-yellow-500 flex items-center gap-2">
                        {{ auth()->user()->githubRepositories->sum('stars_count') }}
                    </div>
                </div>
            </flux:card>

            <flux:card class="flex flex-col justify-between">
                <div>
                    <flux:heading size="sm" level="3">{{ __('Forks Totales') }}</flux:heading>
                    <div class="text-3xl font-bold mt-2 text-orange-400">
                        {{ auth()->user()->githubRepositories->sum('forks_count') }}
                    </div>
                </div>
            </flux:card>
        </div>

        <div class="flex-1 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900/50 space-y-4">
            <div>
                <flux:heading size="lg" level="2">{{ __('Tus Proyectos en GitHub') }}</flux:heading>
                <flux:subheading>{{ __('Listado sincronizado desde tu cuenta de desarrollador') }}</flux:subheading>
            </div>

            @if(auth()->user()->githubRepositories->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center border border-dashed border-zinc-200 dark:border-zinc-700 rounded-xl">
                    <p class="text-zinc-500 dark:text-zinc-400 text-sm mb-2">{{ __('No hay repositorios vinculados aún.') }}</p>
                    <flux:subheading size="sm">{{ __('Ve a Ajustes de GitHub para importar tu catálogo.') }}</flux:subheading>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-4">
                    @foreach(auth()->user()->githubRepositories()->orderBy('github_updated_at', 'desc')->get() as $repo)
                        <flux:card class="flex flex-col justify-between p-5 space-y-4 shadow-sm hover:shadow-md transition-shadow">

                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <flux:heading size="md" level="3" class="truncate font-semibold text-zinc-900 dark:text-red-500">
                                        {{ $repo->name }}
                                    </flux:heading>

                                    @if($repo->is_fork)
                                        <flux:badge color="zinc" variant="outline" size="sm">{{ __('Fork') }}</flux:badge>
                                    @endif
                                </div>

                                <p class="text-xs text-zinc-500 dark:text-zinc-400 min-h-[2rem]">
                                    {{ $repo->description ?? __('Sin descripción disponible en el repositorio.') }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800 text-xs">
                                <div class="flex items-center gap-3 text-zinc-600 dark:text-blue-400">
                                    @if($repo->primary_language)
                                        <span class="flex items-center gap-1.5 font-medium">
                                            <span class="size-2 rounded-full bg-zinc-400 dark:bg-green-500"></span>
                                            {{ $repo->primary_language }}
                                        </span>
                                    @endif

                                    @if($repo->stars_count > 0)
                                        <span class="flex items-center gap-0.5 text-yellow-500 font-medium">
                                            {{ $repo->stars_count }}  ⭐
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
