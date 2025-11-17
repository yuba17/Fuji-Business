@props(['members'])

@if($members->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Persona</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol interno</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Áreas</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($members as $member)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 py-3 text-sm text-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ $member->initials() }}
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-xs text-gray-700">
                            @if($member->internalRole)
                                <div class="flex flex-col">
                                    <span class="font-semibold">{{ $member->internalRole->name }}</span>
                                    <span class="text-[11px] text-gray-500">
                                        {{ $member->internalRole->level ?? 'Sin nivel definido' }}
                                        @if($member->internalRole->track)
                                            · {{ $member->internalRole->track }}
                                        @endif
                                    </span>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">Sin rol interno</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-sm">
                            @php
                                $roleLabel = 'Visualización';
                                if ($member->isDirector()) $roleLabel = 'Director';
                                elseif ($member->isManager()) $roleLabel = 'Manager';
                                elseif ($member->isTecnico()) $roleLabel = 'Técnico';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold
                                @if($member->isDirector())
                                    bg-red-100 text-red-700
                                @elseif($member->isManager())
                                    bg-blue-100 text-blue-700
                                @elseif($member->isTecnico())
                                    bg-emerald-100 text-emerald-700
                                @else
                                    bg-gray-100 text-gray-700
                                @endif
                            ">{{ $roleLabel }}</span>
                        </td>
                        <td class="px-3 py-3 text-xs text-gray-600">
                            @php
                                $primaryArea = $member->area ?? null;
                            @endphp
                            @if($primaryArea)
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full">{{ $primaryArea->name }}</span>
                            @elseif($member->areas && $member->areas->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($member->areas as $memberArea)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full">{{ $memberArea->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">Sin áreas asignadas</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-center text-sm text-gray-500 py-6">No hay miembros en esta vista.</p>
@endif


