@extends('layouts.dashboard')

@section('title', 'Crear Oportunidad')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Crear Nueva Oportunidad</h1>
        <p class="text-sm text-slate-500">Completa el formulario para registrar una oportunidad de venta</p>
    </div>

    <a href="{{ route('opportunities.index') }}"
       class="text-sm text-slate-600 hover:underline">
        ← Volver a Ventas
    </a>
</div>

{{-- Alertas --}}
@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-800">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white p-6 rounded-xl shadow-sm border max-w-3xl">
    <form method="POST" action="{{ route('opportunities.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700">Cliente (Customer ID)</label>
            <input type="number" name="customer_id" value="{{ old('customer_id') }}"
                   class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                   placeholder="Ej: 1" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Descripción de la Oportunidad</label>
            <textarea name="description" rows="4"
                      class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Describe los detalles de la oportunidad...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Monto Estimado (Q)</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount') }}"
                       class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Ej: 45000" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Fecha Estimada de Cierre</label>
                <input type="date" name="estimated_close_date" value="{{ old('estimated_close_date') }}"
                       class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <div class="rounded-xl border bg-indigo-50 p-4 text-sm text-indigo-900">
            <p class="font-semibold mb-2">💡 Consejos para registrar oportunidades</p>
            <ul class="list-disc pl-5 space-y-1 text-indigo-800">
                <li>Describe claramente el producto/servicio.</li>
                <li>Usa un monto estimado realista.</li>
                <li>Luego podrás cambiar la etapa (Prospecto → Negociación → Cerrado).</li>
            </ul>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                Crear Oportunidad
            </button>

            <a href="{{ route('opportunities.index') }}"
               class="inline-flex items-center rounded-lg border px-5 py-2.5 text-sm font-semibold hover:bg-slate-50 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
