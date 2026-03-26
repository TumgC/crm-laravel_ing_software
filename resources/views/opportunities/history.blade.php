<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Historial de la Oportunidad #{{ $opportunity->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Información general</h3>

                <p><strong>ID:</strong> {{ $opportunity->id }}</p>
                <p><strong>Cliente ID:</strong> {{ $opportunity->customer_id ?? 'Sin cliente' }}</p>
                <p><strong>Etapa actual:</strong> {{ $opportunity->stage }}</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Historial de cambios de etapa</h3>

                @if($opportunity->stageHistories->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Usuario</th>
                                    <th class="border px-4 py-2 text-left">Etapa anterior</th>
                                    <th class="border px-4 py-2 text-left">Etapa nueva</th>
                                    <th class="border px-4 py-2 text-left">Fecha y hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opportunity->stageHistories as $history)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $history->user->name ?? 'Sin usuario' }}</td>
                                        <td class="border px-4 py-2">{{ $history->old_stage }}</td>
                                        <td class="border px-4 py-2">{{ $history->new_stage }}</td>
                                        <td class="border px-4 py-2">
                                            {{ \Carbon\Carbon::parse($history->changed_at)->format('d/m/Y h:i A') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Aún no hay cambios de etapa registrados.</p>
                @endif
            </div>

            <div class="flex gap-3">
                <a href="{{ route('opportunities.show', $opportunity) }}"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Volver a la oportunidad
                </a>

                <a href="{{ route('opportunities.index') }}"
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>

        </div>
    </div>
</x-app-layout>