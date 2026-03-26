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

                <p><strong>Descripción:</strong> {{ $opportunity->description }}</p>
                <p><strong>Cliente ID:</strong> {{ $opportunity->customer_id ?? 'Sin cliente' }}</p>

                <!-- Mostrar cliente ficticio -->
                <p><strong>Cliente:</strong> 
                    @php
                        $customer = app(\App\Services\CustomerService::class)->getMockCustomers()[$opportunity->customer_id - 1] ?? null;
                    @endphp
                    {{ $customer ? $customer['first_name'] . ' ' . $customer['last_name'] : 'No asignado' }}
                </p>

                <p><strong>Creado:</strong> {{ $opportunity->created_at ? $opportunity->created_at->format('d/m/Y h:i A') : 'Sin fecha' }}</p>
                <p><strong>Agente ID:</strong> {{ $opportunity->assignedTo ? $opportunity->assignedTo->id : 'Sin asignar' }}</p>
                <p><strong>Agente Asignado:</strong> 
                    {{ $opportunity->assignedTo ? $opportunity->assignedTo->name : 'No asignado' }}
                </p>
            </div>

           <!-- Historial de Etapas -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
            <h3 class="text-lg font-bold mb-4">Historial de Etapas</h3>
            <ul>
                @forelse($opportunity->stageHistories as $history)
                    <li class="mb-3">
                        <strong>De:</strong> {{ $history->old_stage }} <strong>A:</strong> {{ $history->new_stage }}
                        <br>
                        <small>
                            @if($history->changed_at instanceof \Carbon\Carbon)
                                {{ $history->changed_at->format('d/m/Y h:i A') }}
                            @else
                                {{ $history->changed_at }}
                            @endif
                        </small>
                        <br>
                        <small>Cambiado por: {{ $history->user->name }}</small>
                    </li>
                @empty
                    <p>No hay historial de etapas para esta oportunidad.</p>
                @endforelse
            </ul>
        </div>

            <!-- Botón para Cambiar etapa -->
            <div class="flex gap-3 mt-6">
                <form method="POST" action="{{ route('opportunities.changeStage', $opportunity) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="new_stage" value="Cerrado Ganado">
                    <button type="submit" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Confirmar Cambio de Etapa
                    </button>
                </form>
            </div>

            <!-- Botón para volver a la lista de oportunidades -->
            <div class="mt-6">
                <a href="{{ route('opportunities.index') }}" 
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Volver a Oportunidades
                </a>
            </div>

        </div>
    </div>
</x-app-layout>