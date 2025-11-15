@extends('layouts.dashboard')

@section('title', $task->title)

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('tasks.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">Tareas</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $task->title }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('header-actions')
@if(auth()->user()->isDirector() || auth()->user()->isManager() || (auth()->user()->isTecnico() && $task->assigned_to === auth()->id()))
    <a href="{{ route('tasks.edit', $task) }}" class="px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
        Editar
    </a>
@endif
@endsection

@section('content')
<div x-data="taskDetail()" x-cloak>
<!-- Header de la Tarea - Diseño Limpio -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-green-600 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold mb-3">{{ $task->title }}</h1>
                    @if($task->description)
                        <p class="text-green-50 text-sm">{{ $task->description }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <x-ui.badge 
                        variant="{{ $task->priority === 'critical' ? 'error' : ($task->priority === 'high' ? 'warning' : ($task->priority === 'medium' ? 'info' : 'success')) }}">
                        {{ $task->priority_label }}
                    </x-ui.badge>
                    <x-ui.badge 
                        variant="{{ $task->status === 'done' ? 'success' : ($task->status === 'in_progress' ? 'info' : ($task->status === 'blocked' ? 'error' : 'warning')) }}">
                        {{ $task->status_label }}
                    </x-ui.badge>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-4 text-sm text-green-50">
                @if($task->plan)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>{{ $task->plan->name }}</span>
                    </div>
                @endif
                @if($task->assignedUser)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Asignado a: {{ $task->assignedUser->name }}</span>
                    </div>
                @endif
                @if($task->due_date)
                    <div class="flex items-center gap-2 {{ $task->isOverdue() ? 'text-red-200 font-semibold' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Vence: {{ $task->due_date->format('d/m/Y') }}</span>
                        @if($task->isOverdue())
                            <span class="text-red-200">(Vencida)</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Información Detallada -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Información General</h2>
        <dl class="space-y-3">
            @if($task->area)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Área</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $task->area->name }}</dd>
                </div>
            @endif
            @if($task->milestone)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Hito</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $task->milestone->name }}</dd>
                </div>
            @endif
            @if($task->creator)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Creada por</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $task->creator->name }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-sm font-medium text-gray-500">Creada el</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $task->created_at->format('d/m/Y H:i') }}</dd>
            </div>
            @if($task->estimated_hours)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Horas Estimadas</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $task->estimated_hours }}h</dd>
                </div>
            @endif
            @if($task->actual_hours)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Horas Reales</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $task->actual_hours }}h</dd>
                </div>
            @endif
        </dl>
    </div>
    
    @if($task->subtasks->count() > 0)
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Subtareas</h2>
            <div class="space-y-3">
                @foreach($task->subtasks as $subtask)
                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $subtask->title }}</h3>
                        <div class="flex items-center gap-2">
                            <x-ui.badge variant="{{ $subtask->status === 'done' ? 'success' : 'warning' }}">
                                {{ $subtask->status_label }}
                            </x-ui.badge>
                            <a href="{{ route('tasks.show', $subtask) }}" class="text-xs text-green-600 hover:text-green-700 font-semibold">
                                Ver →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Adjuntos -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Adjuntos</h2>
        </div>
        @can('update', $task)
            <button @click="showUploadModal = true" class="px-4 py-2 text-sm font-semibold bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-sm">
                Subir Archivo
            </button>
        @endcan
    </div>
    
    @if($task->attachments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($task->attachments as $attachment)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-green-300 hover:shadow-md transition-all group">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="text-2xl flex-shrink-0">{{ $attachment->file_icon }}</div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm truncate" title="{{ $attachment->original_name }}">
                                    {{ $attachment->original_name }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $attachment->formatted_size }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($attachment->description)
                        <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $attachment->description }}</p>
                    @endif
                    
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <div class="text-xs text-gray-500">
                            <p>Por: {{ $attachment->uploader->name }}</p>
                            <p>{{ $attachment->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('tasks.attachments.download', [$task, $attachment]) }}" 
                               class="px-3 py-1.5 text-xs font-semibold bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all">
                                Descargar
                            </a>
                            @can('update', $task)
                                <form action="{{ route('tasks.attachments.destroy', [$task, $attachment]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Estás seguro de eliminar este archivo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                                        Eliminar
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
            </div>
            <p class="text-gray-600 text-sm mb-4 font-medium">No hay archivos adjuntos</p>
            @can('update', $task)
                <button @click="showUploadModal = true" class="px-6 py-3 text-sm font-semibold bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-sm">
                    Subir Primer Archivo
                </button>
            @endcan
        </div>
    @endif
</div>

<!-- Modal para subir archivo -->
<div x-show="showUploadModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 bg-gray-900/40 flex items-center justify-center p-4"
     @click.self="showUploadModal = false"
     style="display: none;">
    <div x-show="showUploadModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Subir Archivo</h3>
            <button @click="showUploadModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('tasks.attachments.store', $task) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Archivo</label>
                    <input type="file" 
                           name="file" 
                           required
                           accept="*/*"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    <p class="text-xs text-gray-500 mt-1">Tamaño máximo: 10MB</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción (opcional)</label>
                    <textarea name="description" 
                              rows="3"
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none"
                              placeholder="Describe el archivo..."></textarea>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" 
                        @click="showUploadModal = false"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 text-sm font-semibold bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-sm">
                    Subir Archivo
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Comentarios -->
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900">Comentarios</h2>
        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg">{{ $task->comments->count() }}</span>
    </div>
    
    <!-- Formulario para agregar comentario -->
    <form action="{{ route('tasks.comments.store', $task) }}" method="POST" class="mb-6">
        @csrf
        <div class="relative">
            <textarea 
                name="comment" 
                x-model="newComment"
                @input="handleMentionInput($event)"
                @keydown.enter.prevent="if($event.shiftKey) {} else { $event.target.form.submit(); }"
                rows="4"
                placeholder="Escribe un comentario... Usa @ para mencionar usuarios"
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none"
                required></textarea>
            
            <!-- Autocompletado de menciones -->
            <div x-show="showMentionSuggestions && mentionSuggestions.length > 0"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-200 max-h-48 overflow-y-auto z-10"
                 style="display: none;">
                <template x-for="user in mentionSuggestions" :key="user.id">
                    <button type="button"
                            @click="selectMention(user)"
                            class="w-full px-4 py-2 text-left hover:bg-gray-100 transition-colors flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            <span x-text="user.name.charAt(0).toUpperCase()"></span>
                        </div>
                        <span class="text-sm font-medium text-gray-900" x-text="user.name"></span>
                    </button>
                </template>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-3 mt-3">
            <p class="text-xs text-gray-500">Presiona Enter para enviar, Shift+Enter para nueva línea</p>
            <button type="submit" 
                    class="px-5 py-2.5 text-sm font-semibold bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-sm">
                Comentar
            </button>
        </div>
    </form>
    
    <!-- Lista de comentarios -->
    @if($task->comments->count() > 0)
        <div class="space-y-4">
            @foreach($task->comments as $comment)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $comment->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($comment->user_id === auth()->id() || auth()->user()->isDirector())
                            <form action="{{ route('tasks.comments.destroy', [$task, $comment]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este comentario?')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <div class="text-sm text-gray-700 mb-3">
                        {!! $comment->formatted_comment !!}
                    </div>
                    
                    @if($comment->mentionedUsers()->count() > 0)
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs text-gray-500">Mencionado:</span>
                            @foreach($comment->mentionedUsers() as $mentionedUser)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg">
                                    @{{ $mentionedUser->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Respuestas -->
                    @if($comment->replies->count() > 0)
                        <div class="ml-12 mt-4 space-y-3 border-l-2 border-gray-300 pl-4">
                            @foreach($comment->replies as $reply)
                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($reply->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 text-sm">{{ $reply->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        @if($reply->user_id === auth()->id() || auth()->user()->isDirector())
                                            <form action="{{ route('tasks.comments.destroy', [$task, $reply]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este comentario?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-700">
                                        {!! $reply->formatted_comment !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Botón para responder -->
                    <button @click="showReplyForm({{ $comment->id }})" 
                            class="mt-3 text-xs text-green-600 hover:text-green-700 font-semibold">
                        Responder
                    </button>
                    
                    <!-- Formulario de respuesta (oculto por defecto) -->
                    <form x-show="replyToCommentId === {{ $comment->id }}"
                          x-transition:enter="transition ease-out duration-200"
                          x-transition:enter-start="opacity-0 transform scale-95"
                          x-transition:enter-end="opacity-100 transform scale-100"
                          action="{{ route('tasks.comments.store', $task) }}" 
                          method="POST" 
                          class="mt-3"
                          style="display: none;">
                        @csrf
                        <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                        <textarea 
                            name="comment" 
                            rows="2"
                            placeholder="Escribe una respuesta..."
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none"
                            required></textarea>
                        <div class="flex items-center justify-end gap-2 mt-2">
                            <button type="button" 
                                    @click="replyToCommentId = null"
                                    class="px-3 py-1.5 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="px-4 py-1.5 text-xs font-semibold bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all">
                                Responder
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <p class="text-gray-600 text-sm font-medium">No hay comentarios aún</p>
        </div>
    @endif
</div>
</div>

<script>
function taskDetail() {
    return {
        showUploadModal: false,
        newComment: '',
        showMentionSuggestions: false,
        mentionSuggestions: [],
        mentionQuery: '',
        mentionStartIndex: -1,
        replyToCommentId: null,
        users: @json($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name])),
        
        handleMentionInput(event) {
            const textarea = event.target;
            const cursorPos = textarea.selectionStart;
            const text = textarea.value;
            
            // Buscar @ antes del cursor
            const textBeforeCursor = text.substring(0, cursorPos);
            const lastAtIndex = textBeforeCursor.lastIndexOf('@');
            
            if (lastAtIndex !== -1) {
                const textAfterAt = textBeforeCursor.substring(lastAtIndex + 1);
                // Si no hay espacio después del @, mostrar sugerencias
                if (!textAfterAt.includes(' ') && !textAfterAt.includes('\n')) {
                    this.mentionQuery = textAfterAt.toLowerCase();
                    this.mentionStartIndex = lastAtIndex;
                    this.filterMentionSuggestions();
                    this.showMentionSuggestions = true;
                    return;
                }
            }
            
            this.showMentionSuggestions = false;
        },
        
        filterMentionSuggestions() {
            if (!this.mentionQuery) {
                this.mentionSuggestions = this.users.slice(0, 5);
                return;
            }
            
            this.mentionSuggestions = this.users
                .filter(user => user.name.toLowerCase().includes(this.mentionQuery))
                .slice(0, 5);
        },
        
        selectMention(user) {
            const textarea = document.querySelector('textarea[name="comment"]');
            const text = textarea.value;
            const beforeMention = text.substring(0, this.mentionStartIndex);
            const afterMention = text.substring(textarea.selectionStart);
            
            textarea.value = beforeMention + '@' + user.name + ' ' + afterMention;
            this.newComment = textarea.value;
            this.showMentionSuggestions = false;
            textarea.focus();
            textarea.setSelectionRange(beforeMention.length + user.name.length + 2, beforeMention.length + user.name.length + 2);
        },
        
        showReplyForm(commentId) {
            this.replyToCommentId = this.replyToCommentId === commentId ? null : commentId;
        }
    }
}
</script>
@endsection

