@php
    $isLicense = $infra->infrastructure_class === 'license';
    $cardColor = $isLicense ? 'from-purple-50 to-purple-100 border-purple-200' : 'from-gray-50 to-gray-100 border-gray-200';
@endphp

<div class="bg-gradient-to-br {{ $cardColor }} rounded-lg p-4 border border-gray-200 hover:shadow-md transition-all">
    <div class="flex items-start justify-between mb-2">
        <div class="flex-1">
            <div class="flex items-start gap-2 mb-1">
                <h4 class="text-sm font-bold text-gray-900 flex-shrink-0">{{ $infra->name }}</h4>
                <div class="flex flex-wrap items-center gap-1.5 flex-1">
                    @if($infra->tracking_mode === 'group')
                        <span class="px-2 py-0.5 bg-orange-100 text-orange-800 text-[10px] font-bold rounded-full whitespace-nowrap">🧰 Grupo</span>
                    @else
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-800 text-[10px] font-medium rounded-full whitespace-nowrap">👤 Individual</span>
                    @endif
                    @if($infra->is_critical)
                        <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full whitespace-nowrap">🔴 Crítica</span>
                    @endif
                    <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-medium rounded-full whitespace-nowrap">
                        {{ match($infra->status) {
                            'active' => '✅ Activo',
                            'maintenance' => '🔧 Mantenimiento',
                            'deprecated' => '⚠️ Deprecado',
                            'planned' => '📅 Planificado',
                            default => $infra->status
                        } }}
                    </span>
                    {{-- Badge de estado de adquisición --}}
                    @if($infra->acquisition_status)
                        <span class="px-2 py-0.5 {{ match($infra->acquisition_status) {
                            'purchased' => 'bg-green-100 text-green-800',
                            'to_purchase' => 'bg-orange-100 text-orange-800',
                            'planned' => 'bg-blue-100 text-blue-800',
                            default => 'bg-gray-100 text-gray-800'
                        } }} text-[10px] font-medium rounded-full whitespace-nowrap">
                            {{ match($infra->acquisition_status) {
                                'purchased' => '✅ Comprado',
                                'to_purchase' => '🛒 Por comprar',
                                'planned' => '📋 Planificado',
                                default => $infra->acquisition_status
                            } }}
                        </span>
                    @endif
                    {{-- Badge de caducidad para licencias --}}
                    @if($isLicense && $infra->expires_at)
                        @php
                            $expiryStatus = $infra->expiry_status;
                        @endphp
                        @if($expiryStatus === 'expired')
                            <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full whitespace-nowrap">⏰ Caducada</span>
                        @elseif($expiryStatus === 'expiring_soon')
                            <span class="px-2 py-0.5 bg-orange-100 text-orange-800 text-[10px] font-bold rounded-full whitespace-nowrap">
                                ⏰ Caduca en {{ $infra->daysUntilExpiry() }} días
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 text-[10px] font-medium rounded-full whitespace-nowrap">
                                ✅ Válida
                            </span>
                        @endif
                    @endif
                </div>
            </div>
            @if($infra->description)
                <p class="text-xs text-gray-600 mb-2">{{ Str::limit($infra->description, 80) }}</p>
            @endif
        </div>
    </div>
    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
        @if($infra->type)
            <div>
                <span class="text-gray-500">Tipo:</span>
                <span class="font-semibold text-gray-900">{{ $infra->type }}</span>
            </div>
        @endif
        @if($infra->provider)
            <div>
                <span class="text-gray-500">Proveedor:</span>
                <span class="font-semibold text-gray-900">{{ $infra->provider }}</span>
            </div>
        @endif
        @if($infra->capacity)
            <div>
                <span class="text-gray-500">Capacidad:</span>
                <span class="font-semibold text-gray-900">{{ $infra->capacity }}</span>
            </div>
        @endif
        @if($infra->utilization_percent !== null)
            <div>
                <span class="text-gray-500">Utilización:</span>
                <span class="font-semibold {{ $infra->utilization_percent > 80 ? 'text-red-600' : ($infra->utilization_percent > 60 ? 'text-orange-600' : 'text-green-600') }}">
                    {{ $infra->utilization_percent }}%
                </span>
            </div>
        @endif
        @if($isLicense && $infra->expires_at)
            <div class="col-span-2">
                <span class="text-gray-500">Caduca:</span>
                <span class="font-semibold {{ $infra->isExpired() ? 'text-red-600' : ($infra->isExpiringSoon() ? 'text-orange-600' : 'text-gray-900') }}">
                    {{ $infra->expires_at->format('d/m/Y') }}
                    @if($infra->daysUntilExpiry() !== null)
                        ({{ $infra->daysUntilExpiry() > 0 ? 'en ' . $infra->daysUntilExpiry() . ' días' : 'hace ' . abs($infra->daysUntilExpiry()) . ' días' }})
                    @endif
                </span>
            </div>
        @endif
        @if($infra->owner)
            <div class="col-span-2">
                <span class="text-gray-500">Propietario:</span>
                <span class="font-semibold text-gray-900">{{ $infra->owner->name }}</span>
            </div>
        @elseif($infra->is_critical)
            <div class="col-span-2">
                <span class="px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full">⚠️ Sin propietario</span>
            </div>
        @endif
    </div>
    @if($infra->tracking_mode === 'group')
        <div class="mb-3 pt-2 border-t border-gray-200">
            <div class="grid grid-cols-3 gap-2 text-[11px]">
                <div class="bg-white rounded-lg px-2 py-1 text-center border border-gray-100">
                    <p class="text-gray-500 font-medium uppercase tracking-wide text-[9px]">Totales</p>
                    <p class="text-gray-900 font-bold">{{ $infra->quantity_total }}</p>
                </div>
                <div class="bg-white rounded-lg px-2 py-1 text-center border border-gray-100">
                    <p class="text-gray-500 font-medium uppercase tracking-wide text-[9px]">En uso</p>
                    <p class="text-gray-900 font-bold">{{ $infra->quantity_in_use }}</p>
                </div>
                <div class="bg-white rounded-lg px-2 py-1 text-center border border-gray-100">
                    <p class="text-gray-500 font-medium uppercase tracking-wide text-[9px]">Disponibles</p>
                    <p class="text-green-600 font-bold">{{ $infra->quantity_available }}</p>
                </div>
            </div>
        </div>
    @endif
    @if($infra->cost_monthly || $infra->cost_yearly)
        <div class="mb-3 pt-2 border-t border-gray-200">
            <p class="text-xs text-gray-500">Coste:</p>
            <p class="text-sm font-bold text-gray-900">
                @if($infra->cost_monthly)
                    €{{ number_format($infra->cost_monthly, 2, ',', '.') }}/mes
                @endif
                @if($infra->cost_yearly)
                    <span class="text-gray-500">o</span> €{{ number_format($infra->cost_yearly, 2, ',', '.') }}/año
                @endif
            </p>
        </div>
    @endif
    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-200">
        <button wire:click="openInfrastructureModal({{ $infra->id }})" 
                class="text-[10px] text-blue-600 hover:text-blue-800 font-medium">
            Editar
        </button>
        <span class="text-gray-300">|</span>
        <button wire:click="deleteInfrastructure({{ $infra->id }})" 
                onclick="return confirm('¿Estás seguro de eliminar esta infraestructura?')"
                class="text-[10px] text-red-600 hover:text-red-800 font-medium">
            Eliminar
        </button>
    </div>
</div>

