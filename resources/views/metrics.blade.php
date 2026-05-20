<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="grid gap-6 md:grid-cols-3">

            <flux:card class="md:col-span-2 space-y-4">
                <div>
                    <flux:heading size="lg" level="2">{{ __('Distribución de Lenguajes') }}</flux:heading>
                    <flux:subheading>{{ __('Porcentaje de código real en tus repositorios') }}</flux:subheading>
                </div>

                <div class="relative w-full h-[600px] flex justify-center">
                    <canvas id="githubLanguagesChart"></canvas>
                </div>
            </flux:card>

            <flux:card>
                <flux:heading size="md">{{ __('Resumen Técnico') }}</flux:heading>
                <p class="text-xs text-zinc-500 mt-2">
                    {{ __('Los datos se calculan sumando el peso total en bytes de cada archivo fuente analizado por GitHub.') }}
                </p>
            </flux:card>
        </div>

    </div>

    <script>
        const chartData = @json($chartData);

        const ctx = document.getElementById('githubLanguagesChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.data,
                    backgroundColor: [
                        '#0284c7',
                        '#10b981',
                        '#f59e0b',
                        '#6366f1',
                        '#ec4899',
                        '#71717a'
                    ],
                    borderWidth: 2,
                    borderColor: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            color: document.documentElement.classList.contains('dark') ? '#e4e4e7' : '#27272a',
                            font: {family: 'sans-serif', size: 12}
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let value = context.raw;
                                if (value >= 1024 * 1024) {
                                    return ` ${(value / (1024 * 1024)).toFixed(2)} MB`;
                                }
                                return ` ${(value / 1024).toFixed(2)} KB`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-layouts::app>
