<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle de Oportunidad
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Información de la oportunidad</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p><strong>ID Oportunidad:</strong> #{{ $opportunity->id }}</p>
                        <p><strong>Cliente ID:</strong> {{ $opportunity->customer_id ?? 'Sin cliente' }}</p>
                        <p>
                            <strong>Cliente:</strong>
                            @if($customer)
                                {{ $customer['name']
                                    ?? $customer['full_name']
                                    ?? $customer['customer_name']
                                    ?? $customer['nombre']
                                    ?? trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '')) }}
                            @else
                                {{ $opportunity->customer_name ?? 'No asignado' }}
                            @endif
                        </p>
                        <p><strong>Etapa actual:</strong> {{ $opportunity->stage }}</p>
                    </div>

                    <div>
                        <p><strong>Monto:</strong> Q {{ number_format((float) $opportunity->amount, 2) }}</p>
                        <p>
                            <strong>Cierre estimado:</strong>
                            {{ $opportunity->estimated_close_date ? \Carbon\Carbon::parse($opportunity->estimated_close_date)->format('d/m/Y') : 'No definido' }}
                        </p>
                        <p>
                            <strong>Creado:</strong>
                            {{ $opportunity->created_at ? $opportunity->created_at->format('d/m/Y h:i A') : 'Sin fecha' }}
                        </p>
                        <p><strong>Creado por:</strong> {{ $opportunity->created_by ?? 'No disponible' }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <p><strong>Descripción:</strong></p>
                    <p class="mt-1 text-gray-700">
                        {{ $opportunity->description ?: 'Sin descripción registrada.' }}
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Historial de Etapas</h3>
                    <a href="{{ route('opportunities.history', $opportunity) }}"
                       class="text-sm text-indigo-600 hover:underline">
                        Ver historial completo
                    </a>
                </div>

                <ul class="space-y-3">
                    @forelse($opportunity->stageHistories as $history)
                        <li class="border rounded-lg p-3">
                            <p>
                                <strong>De:</strong> {{ $history->old_stage }}
                                <strong class="ml-2">A:</strong> {{ $history->new_stage }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $history->changed_at ? $history->changed_at->format('d/m/Y h:i A') : 'Sin fecha' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Cambiado por: {{ $history->user->name ?? 'Usuario no disponible' }}
                            </p>
                        </li>
                    @empty
                        <li class="text-gray-500">No hay historial de etapas para esta oportunidad.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Propuestas</h3>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="font-semibold text-red-700 mb-1">Revisa lo siguiente:</p>
                        <ul class="text-sm text-red-600 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('proposals.store', $opportunity) }}"
                      enctype="multipart/form-data"
                      class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                    @csrf

                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Título de la propuesta"
                           class="border rounded-lg px-3 py-2"
                           required>

                    <input type="file"
                           name="file"
                           class="border rounded-lg px-3 py-2"
                           accept=".pdf,.doc,.docx"
                           required>

                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Subir
                    </button>
                </form>

                @forelse($opportunity->proposals as $proposal)
                    <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <p class="font-semibold text-base">{{ $proposal->title }}</p>

                                <p class="text-sm text-gray-600 mt-1">
                                    Archivo: {{ $proposal->file_name }}
                                </p>

                                <p class="text-sm text-gray-600">
                                    Estado:
                                    <span class="font-medium">{{ $proposal->status }}</span>
                                </p>

                                <p class="text-sm text-gray-600">
                                    Fecha de carga:
                                    {{ $proposal->uploaded_at ? $proposal->uploaded_at->format('d/m/Y h:i A') : 'Sin fecha' }}
                                </p>

                                <a href="{{ asset('storage/' . $proposal->file_path) }}"
                                   target="_blank"
                                   class="inline-block mt-2 text-blue-600 text-sm underline">
                                    Ver archivo
                                </a>
                            </div>

                            <div class="w-full md:w-72">
                                <form method="POST" action="{{ route('proposals.status', $proposal) }}" class="flex gap-2">
                                    @csrf

                                    <select name="status" class="border rounded-lg px-2 py-1 w-full">
                                        <option value="Borrador" @selected($proposal->status === 'Borrador')>Borrador</option>
                                        <option value="Enviada" @selected($proposal->status === 'Enviada')>Enviada</option>
                                        <option value="En Revisión" @selected($proposal->status === 'En Revisión')>En Revisión</option>
                                        <option value="Aceptada" @selected($proposal->status === 'Aceptada')>Aceptada</option>
                                        <option value="Rechazada" @selected($proposal->status === 'Rechazada')>Rechazada</option>
                                    </select>

                                    <button type="submit"
                                            class="bg-gray-700 text-white px-3 py-1 rounded">
                                        Actualizar
                                    </button>
                                </form>

                                @if($proposal->status === 'Aceptada' && $opportunity->stage !== 'Cerrado Ganado')
                                    <form method="POST"
                                          action="{{ route('opportunities.stageUpdate', $opportunity) }}"
                                          class="mt-3"
                                          onsubmit="return confirm('¿Desea marcar esta oportunidad como Cerrado Ganado?');">
                                        @csrf
                                        <input type="hidden" name="stage" value="Cerrado Ganado">

                                        <button type="submit"
                                                class="w-full bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">
                                            Marcar como Cerrado Ganado
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Historial de estados</h4>

                            @if($proposal->histories->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($proposal->histories as $history)
                                        <li class="border rounded-lg px-3 py-2 bg-white text-sm text-gray-600">
                                            De <strong>{{ $history->old_status }}</strong>
                                            a <strong>{{ $history->new_status }}</strong>
                                            <br>
                                            <span class="text-xs text-gray-500">
                                                {{ $history->changed_at ? $history->changed_at->format('d/m/Y h:i A') : 'Sin fecha' }}
                                                | Usuario ID: {{ $history->changed_by }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500">No hay historial de cambios para esta propuesta.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-500">
                        No hay propuestas registradas para esta oportunidad.
                    </div>
                @endforelse
            </div>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('opportunities.stageForm', $opportunity) }}"
                   class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Cambiar Etapa
                </a>

                <a href="{{ route('opportunities.index') }}"
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Volver a Oportunidades
                </a>
            </div>

        </div>
    </div>
</x-app-layout>