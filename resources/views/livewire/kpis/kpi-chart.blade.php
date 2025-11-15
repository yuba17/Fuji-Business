<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Evolución del KPI</h3>
        <select 
            wire:model.live="period" 
            class="px-3 py-1.5 text-sm border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
        >
            <option value="7">Últimos 7 días</option>
            <option value="30">Últimos 30 días</option>
            <option value="90">Últimos 90 días</option>
            <option value="180">Últimos 6 meses</option>
            <option value="365">Último año</option>
        </select>
    </div>
    
    @if(count($chartData['labels']) > 0)
        <div class="relative h-64">
            <canvas id="kpi-chart-{{ $kpi->id }}" wire:ignore></canvas>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                const ctx = document.getElementById('kpi-chart-{{ $kpi->id }}');
                if (!ctx || typeof Chart === 'undefined') return;
                
                let chart = null;
                
                const initChart = () => {
                    if (chart) {
                        chart.destroy();
                    }
                    
                    const data = @js($chartData);
                    
                    chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Valor Actual',
                                    data: data.values,
                                    borderColor: 'rgb(239, 68, 68)',
                                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                },
                                {
                                    label: 'Meta',
                                    data: Array(data.labels.length).fill(data.target),
                                    borderColor: 'rgb(34, 197, 94)',
                                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                    borderDash: [5, 5],
                                    tension: 0,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                }
                            }
                        }
                    });
                };
                
                initChart();
                
                Livewire.on('kpiUpdated', () => {
                    setTimeout(initChart, 100);
                });
                
                Livewire.hook('morph.updated', () => {
                    setTimeout(initChart, 100);
                });
            });
        </script>
    @else
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-sm">No hay datos históricos disponibles</p>
            <p class="text-xs text-gray-400 mt-1">Actualiza el KPI para ver su evolución</p>
        </div>
    @endif
</div>
