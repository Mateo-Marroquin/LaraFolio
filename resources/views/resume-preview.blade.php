<x-layouts::app :title="__('Exportar Resumen')">
    <div class="max-w-3xl mx-auto space-y-6">

        <div>
            <flux:heading size="lg" level="2">{{ __('Exportar Currículum Técnico') }}</flux:heading>
            <flux:subheading>{{ __('Genera un documento PDF oficial y optimizado con las estadísticas consolidadas de este perfil.') }}</flux:subheading>
        </div>

        <flux:card
            class="p-6 space-y-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">

            <div
                class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 dark:bg-red-950/30 text-red-500 rounded-xl">
                        <flux:icon name="document-text" class="size-8"/>
                    </div>
                    <div>
                        <flux:heading size="md" level="3">Resumen_GitHub_{{ $username }}.pdf</flux:heading>
                        <p class="text-xs text-zinc-500 mt-0.5">{{ __('Listo para descargar • Formato Dinámico A4') }}</p>
                    </div>
                </div>

                <flux:button
                    href="{{ route('public.resume.download', $username) }}"
                    variant="primary"
                    icon="cloud-arrow-down"

                    class="w-full md:w-auto shadow-sm"
                >
                    {{ __('Descargar PDF Ahora') }}
                </flux:button>
            </div>

            <div class="space-y-4">
                <flux:heading size="sm" level="4" class="text-zinc-400 uppercase tracking-wider text-xs font-semibold">
                    {{ __('El reporte generado incluirá:') }}
                </flux:heading>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div
                        class="p-4 bg-zinc-50 dark:bg-zinc-800/40 rounded-xl border border-zinc-100 dark:border-zinc-800/60">
                        <span class="text-xs text-zinc-500 block">{{ __('Información de Perfil') }}</span>
                        <span class="text-lg font-bold text-zinc-800 dark:text-zinc-200 mt-1 block">
                            {{ $profileInfo->name ? __('Completo') : __('Básico') }}
                        </span>
                    </div>

                    <div
                        class="p-4 bg-zinc-50 dark:bg-zinc-800/40 rounded-xl border border-zinc-100 dark:border-zinc-800/60">
                        <span class="text-xs text-zinc-500 block">{{ __('Catálogo Indexado') }}</span>
                        <span class="text-lg font-bold text-zinc-800 dark:text-zinc-200 mt-1 block">
                            {{ $repositoriesCount }} {{ __('Repositorios') }}
                        </span>
                    </div>

                    <div
                        class="p-4 bg-zinc-50 dark:bg-zinc-800/40 rounded-xl border border-zinc-100 dark:border-zinc-800/60">
                        <span class="text-xs text-zinc-500 block">{{ __('Distribución de Código') }}</span>
                        <span class="text-lg font-bold text-zinc-800 dark:text-zinc-200 mt-1 block">
                            {{ $languagesCount }} {{ __('Lenguajes') }}
                        </span>
                    </div>
                </div>

                <div class="pt-2 text-xs text-zinc-500 flex items-start gap-2">
                    <flux:icon name="information-circle" class="size-4 text-zinc-400 shrink-0 mt-0.5"/>
                    <p>{{ __('Nota: La gráfica analítica circular se compila de forma asíncrona directamente en el documento utilizando el motor de renderizado de QuickChart.') }}</p>
                </div>
            </div>

        </flux:card>

    </div>
</x-layouts::app>
