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
    <form method="GET" action="{{ route('tickets.index') }}" class="flex gap-3 items-center">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Buscar tickets..."
               class="flex-1 px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800">
            Buscar
        </button>
    </form>

    <div class="flex gap-2 mt-4 text-sm flex-wrap">
        @php
            $current = request('status', 'Todos');
            $q = request('q');
        @endphp

        @foreach($statuses as $st)
            @php
                // Si es "Todos", NO mandamos status
                $params = ['q' => $q];

                if ($st !== 'Todos') {
                    $params['status'] = $st;
                }

                $params = array_filter($params, fn($v) => $v !== null && $v !== '');
            @endphp

            <a href="{{ route('tickets.index', $params) }}"
            class="px-3 py-1 rounded-lg
            {{ $current === $st ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                {{ $st }}
            </a>
        @endforeach
    </div>
</div>

{{-- Lista de tickets --}}
<div class="space-y-4">

@foreach($tickets as $ticket)

    <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">

        <div class="flex justify-between items-start">

            <div class="flex-1">

                {{-- Código + Estado --}}
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

</div>
<div class="mt-6">
    {{ $tickets->links() }}
</div>

@endsection
