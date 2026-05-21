<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="grid gap-6 md:grid-cols-3">

            <flux:card class="md:col-span-2 space-y-4">
                <div>
                    <flux:heading size="lg" level="2">{{ __('Distribución de Lenguajes') }}</flux:heading>
                    <flux:subheading>{{ __('Porcentaje de código real en tus repositorios') }}</flux:subheading>
                </div>

                <div class="relative w-full h-[450px] flex justify-center">
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

        <flux:card class="w-full space-y-4">
            <div>
                <flux:heading size="lg" level="2">
                    {{ __('Índice de Actividad Diaria') }} — <span class="capitalize text-blue-500">{{ $timelineChartData['monthName'] }}</span>
                </flux:heading>
                <flux:subheading>{{ __('Cantidad de acciones realizadas en GitHub (Commits, Push, Pull Requests) ordenadas por día.') }}</flux:subheading>
            </div>
            <div class="relative w-full h-[300px]">
                <canvas id="githubTimelineChart"></canvas>
            </div>
        </flux:card>

    </div>

    <script>
        const chartData = @json($chartData);
        const timelineData = @json($timelineChartData);

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

        const ctxTimeline = document.getElementById('githubTimelineChart').getContext('2d');
        new Chart(ctxTimeline, {
            type: 'line',
            data: {
                labels: timelineData.labels,
                datasets: [{
                    label: 'Acciones de Desarrollo',
                    data: timelineData.data,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3,
                    pointBackgroundColor: '#0ea5e9',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: document.documentElement.classList.contains('dark') ? '#a1a1aa' : '#71717a'
                        },
                        grid: { color: document.documentElement.classList.contains('dark') ? '#27272a' : '#e4e4e7' }
                    },
                    x: {
                        ticks: { color: document.documentElement.classList.contains('dark') ? '#a1a1aa' : '#71717a' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</x-layouts::app>
