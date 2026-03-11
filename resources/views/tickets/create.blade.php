@extends('layouts.dashboard')

@section('title', 'Crear Ticket')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Crear Ticket</h1>
            <p class="text-sm text-slate-500">Registra una solicitud de soporte asociada a un cliente.</p>
        </div>

        <a href="{{ route('tickets.index') }}"
           class="px-4 py-2 bg-slate-200 text-slate-800 rounded-lg text-sm hover:bg-slate-300">
            ← Volver
        </a>
    </div>

    {{-- Card Form --}}
    <div class="bg-white rounded-2xl shadow-sm border p-6">

        {{-- Mensaje éxito --}}
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores --}}
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

        <form method="POST" action="{{ route('tickets.store') }}" class="space-y-5">
            @csrf

            {{-- Customer ID --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Customer ID</label>
                <input type="number" name="customer_id" value="{{ old('customer_id') }}"
                       class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: 5" required>
                <p class="text-xs text-slate-400 mt-1">(Por ahora: válido del 1 al 10 en el servicio simulado)</p>
            </div>

            {{-- Subject --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Asunto</label>
                <input type="text" name="subject" value="{{ old('subject') }}"
                       class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: No puedo iniciar sesión" required>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                <textarea name="description" rows="5"
                          class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                          placeholder="Describe el problema..." required>{{ old('description') }}</textarea>
            </div>

            {{-- Status + Assigned --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                    <select name="status"
                            class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                            required>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ old('status','Abierto') == $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Assigned To</label>

                    <select name="assigned_to"
                            class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Sin asignar</option>

                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}"
                                {{ old('assigned_to') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }} ({{ $agent->email }})
                            </option>
                        @endforeach
                    </select>

                    <p class="text-xs text-slate-400 mt-1">Lista tomada de usuarios registrados en el sistema.</p>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                    Guardar Ticket
                </button>

                <button type="reset"
                        class="px-5 py-2 bg-slate-100 text-slate-800 rounded-lg text-sm hover:bg-slate-200">
                    Limpiar
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
