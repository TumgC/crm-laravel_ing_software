@extends('layouts.dashboard')

@section('title', 'Tickets')

@section('content')

@php use Illuminate\Support\Str; @endphp

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Tickets</h1>
        <p class="text-sm text-slate-500">Gestiona todos los tickets de soporte</p>
    </div>

    <a href="{{ route('tickets.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
        + Crear Ticket
    </a>
</div>

{{-- Buscador --}}
<div class="bg-white p-4 rounded-xl shadow-sm border mb-6">

    <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">

        {{-- Buscar --}}
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Buscar tickets..."
               class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

        {{-- Prioridad --}}
        <select name="priority" class="px-4 py-2 border rounded-lg text-sm">
            <option value="">Todas las prioridades</option>
            @foreach($priorities as $p)
                <option value="{{ $p }}" @selected(request('priority') == $p)>
                    {{ $p }}
                </option>
            @endforeach
        </select>

        {{-- Estado --}}
        <select name="status" class="px-4 py-2 border rounded-lg text-sm">
            <option value="">Todos los estados</option>
            @foreach($statuses as $st)
                <option value="{{ $st }}" @selected(request('status') == $st)>
                    {{ $st }}
                </option>
            @endforeach
        </select>

        {{-- Agente --}}
        <select name="assigned_to" class="px-4 py-2 border rounded-lg text-sm">
            <option value="">Todos</option>
            <option value="unassigned" @selected(request('assigned_to') == 'unassigned')>
                Sin asignar
            </option>

            @foreach($agents as $agent)
                <option value="{{ $agent->id }}" @selected(request('assigned_to') == $agent->id)>
                    {{ $agent->name }}
                </option>
            @endforeach
        </select>

        {{-- Botones --}}
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800">
                Filtrar
            </button>

            <a href="{{ route('tickets.index') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">
                Limpiar
            </a>
        </div>

    </form>

</div>

{{-- Lista de tickets --}}
<div class="space-y-4">

    @if($tickets->count() > 0)

        @foreach($tickets as $ticket)

            <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">

                <div class="flex justify-between items-start">

                    <div class="flex-1">

                        {{-- Código + Estado + Prioridad --}}
                        <div class="flex items-center gap-3 text-xs mb-2">

                            <span class="text-slate-400 font-semibold">
                                {{ $ticket->ticket_number }}
                            </span>

                            {{-- Badge estado --}}
                            @if($ticket->status == 'Abierto')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                    Abierto
                                </span>
                            @elseif($ticket->status == 'Asignado')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                                    Asignado
                                </span>
                            @elseif($ticket->status == 'Cerrado')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    Cerrado
                                </span>
                            @else
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs">
                                    {{ $ticket->status }}
                                </span>
                            @endif

                            {{-- Badge prioridad --}}
                            @if($ticket->priority == 'Alta' || $ticket->priority == 'Crítica')
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                    {{ $ticket->priority }}
                                </span>
                            @elseif($ticket->priority == 'Media')
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs">
                                    {{ $ticket->priority }}
                                </span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    {{ $ticket->priority }}
                                </span>
                            @endif
                        </div>

                        {{-- Título --}}
                        <h3 class="font-semibold text-lg mb-1">
                            {{ $ticket->subject }}
                        </h3>

                        {{-- Descripción corta --}}
                        <p class="text-sm text-slate-500 mb-3">
                            {{ Str::limit($ticket->description, 120) }}
                        </p>

                        {{-- Footer --}}
                        <div class="flex items-center gap-6 text-xs text-slate-400">
                            <span>
                                Cliente: {{ $ticket->customer_name ?? $ticket->customer_id }}
                            </span>

                            <span>
                                Asignado a: {{ optional($ticket->assignee)->name ?? 'Sin asignar' }}
                            </span>

                            <span>
                                Fecha: {{ $ticket->created_at->format('Y-m-d') }}
                            </span>
                        </div>

                    </div>

                    {{-- Botón detalles --}}
                    <div>
                        <a href="{{ route('tickets.show', $ticket) }}"
                           class="text-indigo-600 text-sm hover:underline">
                            Ver Detalles
                        </a>
                    </div>

                </div>

            </div>

        @endforeach

    @else

        <div class="bg-white p-10 rounded-xl shadow-sm border text-center text-slate-500">
            No hay tickets con estos criterios.
        </div>

    @endif

</div>
<div class="mt-6">
    {{ $tickets->links() }}
</div>

@endsection
