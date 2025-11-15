<div>
    <!-- Matriz de Riesgos -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr>
                        <th class="border-2 border-gray-300 p-2 bg-gray-50 font-semibold text-gray-700">Probabilidad / Impacto</th>
                        <th class="border-2 border-gray-300 p-2 bg-gray-50 font-semibold text-gray-700 text-center">1<br><span class="text-xs font-normal">Muy Bajo</span></th>
                        <th class="border-2 border-gray-300 p-2 bg-gray-50 font-semibold text-gray-700 text-center">2<br><span class="text-xs font-normal">Bajo</span></th>
                        <th class="border-2 border-gray-300 p-2 bg-gray-50 font-semibold text-gray-700 text-center">3<br><span class="text-xs font-normal">Medio</span></th>
                        <th class="border-2 border-gray-300 p-2 bg-gray-50 font-semibold text-gray-700 text-center">4<br><span class="text-xs font-normal">Alto</span></th>
                        <th class="border-2 border-gray-300 p-2 bg-gray-50 font-semibold text-gray-700 text-center">5<br><span class="text-xs font-normal">Muy Alto</span></th>
                    </tr>
                </thead>
                <tbody>
                    @for($prob = 5; $prob >= 1; $prob--)
                        <tr>
                            <td class="border-2 border-gray-300 p-2 bg-gray-50 font-semibold text-gray-700 text-center">
                                {{ $prob }}<br>
                                <span class="text-xs font-normal">
                                    @if($prob == 5) Muy Alta
                                    @elseif($prob == 4) Alta
                                    @elseif($prob == 3) Media
                                    @elseif($prob == 2) Baja
                                    @else Muy Baja
                                    @endif
                                </span>
                            </td>
                            @for($impact = 1; $impact <= 5; $impact++)
                                @php
                                    $cellRisks = $this->getRisksByCell($prob, $impact);
                                    $cellColor = $this->getCellColor($prob, $impact);
                                @endphp
                                <td class="border-2 border-gray-300 p-3 {{ $cellColor }} min-w-[150px] align-top">
                                    @if($cellRisks->count() > 0)
                                        <div class="space-y-2">
                                            @foreach($cellRisks as $risk)
                                                <a 
                                                    href="{{ route('risks.show', $risk) }}"
                                                    class="block p-2 bg-white rounded-lg shadow-sm hover:shadow-md transition-all cursor-pointer"
                                                    wire:click="selectRisk({{ $risk->id }})"
                                                >
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs font-bold text-gray-900 truncate" title="{{ $risk->name }}">
                                                                {{ $risk->name }}
                                                            </p>
                                                            <p class="text-xs text-gray-600 mt-1">
                                                                Nivel: {{ $risk->risk_level }}
                                                            </p>
                                                            @if($risk->plan)
                                                                <p class="text-xs text-gray-500 mt-1 truncate">
                                                                    {{ $risk->plan->name }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <x-ui.badge 
                                                            variant="{{ $risk->category === 'critico' ? 'error' : ($risk->category === 'alto' ? 'warning' : ($risk->category === 'medio' ? 'info' : 'success')) }}"
                                                            class="ml-2 flex-shrink-0"
                                                        >
                                                            {{ ucfirst($risk->category) }}
                                                        </x-ui.badge>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center text-gray-400 text-xs py-4">
                                            -
                                        </div>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        
        <!-- Leyenda -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Leyenda:</h3>
            <div class="flex flex-wrap gap-4 text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-red-600 rounded"></div>
                    <span class="text-gray-600">Crítico (21-25)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-orange-500 rounded"></div>
                    <span class="text-gray-600">Alto (13-20)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-yellow-400 rounded"></div>
                    <span class="text-gray-600">Medio (6-12)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-green-400 rounded"></div>
                    <span class="text-gray-600">Bajo (1-5)</span>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
