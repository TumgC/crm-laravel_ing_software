@extends('layouts.dashboard')

@section('title', 'Detalle Ticket')

@section('content')

@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
        {{ session('success') }}
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
        <p class="text-slate-600 text-sm whitespace-pre-line">{{ $ticket->description }}</p>

        <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-slate-400">Cliente (Customer ID)</span>
                <div class="font-semibold">{{ $ticket->customer_id }}</div>
            </div>
            <div>
                <span class="text-slate-400">Creado</span>
                <div class="font-semibold">{{ $ticket->created_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Panel de actualización --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <h2 class="font-semibold mb-4">Actualizar</h2>

        <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="space-y-4">
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

@endsection
