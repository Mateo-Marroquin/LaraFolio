<x-layouts::app :title="isset($username) ? __('Portafolio de ') . $username : __('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-8 rounded-xl">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <flux:card class="w-full space-y-4 p-6 border-t-4 border-t-indigo-500 bg-gradient-to-b from-indigo-500/[0.04] via-transparent to-transparent">
            <div>
                <flux:heading size="lg" level="2" class="font-bold tracking-tight">{{ __('Distribución de Lenguajes') }}</flux:heading>
                <flux:subheading size="sm">
                    {{ __('Porcentaje de código real en tus repositorios') }}
                    @if(isset($isOwner) && $isOwner)
                        <span class="text-indigo-600 dark:text-indigo-400 font-semibold ml-1">{{ __('(Incluye Proyectos Privados)') }}</span>
                    @else
                        <span class="text-zinc-500 ml-1">{{ __('(Solo Públicos)') }}</span>
                    @endif
                </flux:subheading>
            </div>

            <div class="relative w-full h-[450px] flex justify-center pt-2">
                <canvas id="githubLanguagesChart"></canvas>
            </div>
        </flux:card>

        <flux:card class="w-full space-y-4 p-6 border-t-4 border-t-sky-500 bg-gradient-to-b from-sky-500/[0.04] via-transparent to-transparent">
            <div>
                <flux:heading size="lg" level="2" class="font-bold tracking-tight">
                    {{ __('Índice de Actividad Diaria') }} — <span class="capitalize text-sky-600 dark:text-sky-400">{{ $timelineChartData['monthName'] }}</span>
                </flux:heading>
                <flux:subheading size="sm">{{ __('Cantidad de acciones realizadas en GitHub (Commits, Push, Pull Requests) ordenadas por día.') }}</flux:subheading>
            </div>

            <div class="relative w-full h-[300px] pt-2">
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
                        '#6366f1', // Indigo principal coordinado
                        '#0284c7',
                        '#10b981',
                        '#f59e0b',
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
                            padding: 25,
                            color: document.documentElement.classList.contains('dark') ? '#e4e4e7' : '#27272a',
                            font: {family: 'sans-serif', size: 12, weight: '500'}
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
                    backgroundColor: 'rgba(14, 165, 233, 0.08)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 3,
                    pointBackgroundColor: '#0ea5e9',
                    pointBorderColor: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                    pointBorderWidth: 1.5,
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
                        grid: {color: document.documentElement.classList.contains('dark') ? '#27272a' : '#e4e4e7'}
                    },
                    x: {
                        ticks: {color: document.documentElement.classList.contains('dark') ? '#a1a1aa' : '#71717a'},
                        grid: {display: false}
                    }
                },
                plugins: {
                    legend: {display: false}
                }
            }
        });
    </script>
</x-layouts::app>
