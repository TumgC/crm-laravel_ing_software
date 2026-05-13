@extends('layouts.dashboard')

@section('title', 'Detalle Ticket')

@section('content')

@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if(session('commercial_alert'))
    @php $alert = session('commercial_alert'); @endphp

    <div class="mb-4 rounded-lg border p-3
        @if($alert['type'] === 'ok') bg-blue-50 border-blue-200 text-blue-800
        @elseif($alert['type'] === 'empty') bg-slate-50 border-slate-200 text-slate-700
        @else bg-yellow-50 border-yellow-200 text-yellow-800
        @endif">

        <p class="font-semibold">{{ $alert['message'] }}</p>

        @if(!empty($alert['stage']))
            <p class="text-sm mt-1">
                Etapa comercial actual: <strong>{{ $alert['stage'] }}</strong>
            </p>
        @endif

        @if(!empty($alert['reference']))
            <p class="text-sm">
                Referencia: {{ $alert['reference'] }}
            </p>
        @endif
    </div>
@endif

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">{{ $ticket->ticket_number }}</h1>
        <p class="text-sm text-slate-500">{{ $ticket->subject }}</p>
    </div>

    <a href="{{ route('tickets.index') }}" class="text-sm text-slate-600 hover:underline">
        ← Volver a Tickets
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Info del ticket --}}
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border">
        <h2 class="font-semibold mb-3">Descripción</h2>

        <p class="text-slate-600 text-sm whitespace-pre-line">
            {{ $ticket->description }}
        </p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-slate-400">Cliente</span>
                <div class="font-semibold">
                    {{ $ticket->customer_name ?? 'Cliente ID: ' . $ticket->customer_id }}
                </div>
                <div class="text-xs text-slate-400">
                    Customer ID: {{ $ticket->customer_id }}
                </div>
            </div>

            <div>
                <span class="text-slate-400">Creado</span>
                <div class="font-semibold">
                    {{ $ticket->created_at->format('Y-m-d H:i') }}
                </div>
            </div>

            <div>
                <span class="text-slate-400">Prioridad</span>
                <div class="font-semibold">
                    {{ $ticket->priority ?? 'No definida' }}
                </div>
            </div>

            <div>
                <span class="text-slate-400">Estado actual</span>
                <div class="font-semibold">
                    {{ $ticket->status }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('tickets.checkCommercialStatus', $ticket) }}" class="mt-6">
            @csrf
            <button type="submit"
                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm">
                Consultar estado comercial
            </button>
        </form>
    </div>

    {{-- Panel de actualización --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <h2 class="font-semibold mb-4">Actualizar</h2>

        <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="space-y-4" onsubmit="return confirmarCierreTicket();">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-slate-700">Estado</label>
                <select name="status" class="mt-1 w-full rounded-lg border-slate-300">
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ $ticket->status === $st ? 'selected' : '' }}>
                            {{ $st }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Asignado a</label>
                <select name="assigned_to" class="mt-1 w-full rounded-lg border-slate-300">
                    <option value="">Sin asignar</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                Guardar cambios
            </button>
        </form>
    </div>

</div>

{{-- Interacción obligatoria --}}
<div class="bg-white p-6 rounded-xl shadow-sm border mt-6">
    <h3 class="font-semibold mb-4">Agregar Interacción/Comentario</h3>

    <form method="POST" action="{{ route('tickets.addInteraction', $ticket) }}">
        @csrf

        <div class="mb-4">
            <label for="comment" class="block text-sm font-medium text-slate-700">Comentario</label>
            <textarea id="comment"
                      name="comment"
                      rows="4"
                      class="mt-1 w-full rounded-lg border-slate-300"
                      required></textarea>
        </div>

        <button type="submit"
                class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
            Guardar Interacción
        </button>
    </form>
</div>

{{-- Historial de Interacciones --}}
<div class="bg-white p-6 rounded-xl shadow-sm border mt-6">
    <h3 class="font-semibold mb-4">Historial de Interacciones</h3>

    <ul class="space-y-4">
        @foreach($ticket->interactions as $interaction)
            <li class="p-4 border border-gray-300 rounded">
                <p class="text-sm">
                    <strong>Comentario:</strong> {{ $interaction->comment }}
                </p>
                <small class="text-slate-500">
                    {{ $interaction->created_at->format('d/m/Y h:i A') }}
                </small>
            </li>
        @endforeach
    </ul>

    @if($ticket->interactions->isEmpty())
        <p>No hay interacciones en este ticket.</p>
    @endif
</div>

{{-- Historial de Cambios de Estado --}}
<div class="bg-white p-6 rounded-xl shadow-sm border mt-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold">Historial de Cambios de Estado</h3>
    </div>

    @if($ticket->statusHistories->isNotEmpty())

        <ul class="space-y-4">

            @foreach($ticket->statusHistories as $history)

                <li class="p-4 border border-gray-300 rounded">

                    <p class="text-sm font-semibold">
                        De: {{ $history->old_status ?? 'Sin estado' }}
                        → A: {{ $history->new_status }}
                    </p>

                    <small class="text-slate-500 block mt-1">
                        {{ $history->changed_at?->format('d/m/Y h:i A') }}
                    </small>

                    <small class="text-slate-500">
                        Cambiado por:
                        {{ $history->changedBy->name ?? 'Sistema' }}
                    </small>

                </li>

            @endforeach

        </ul>

    @else

        <p class="text-sm text-slate-500">
            No hay cambios de estado registrados.
        </p>

    @endif
</div>

<script>
    function confirmarCierreTicket() {
        const estadoActual = @json($ticket->status);
        const nuevoEstado = document.querySelector('select[name="status"]').value;

        if (nuevoEstado === 'Cerrado' && estadoActual !== 'Resuelto') {
            return confirm('Este ticket no ha pasado por el estado Resuelto. ¿Deseas cerrarlo de todos modos?');
        }

        return true;
    }
</script>

@endsection
