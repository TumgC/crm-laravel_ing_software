@extends('layouts.dashboard')

@section('title', 'Ventas')

@section('content')
@php use Illuminate\Support\Str; @endphp

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Ventas</h1>
        <p class="text-sm text-slate-500">Gestiona todas las oportunidades de venta</p>
    </div>

    <a href="{{ route('opportunities.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
        + Crear Oportunidad
    </a>
</div>

{{-- Buscador + filtros --}}
<div class="bg-white p-4 rounded-xl shadow-sm border mb-6">
    <form method="GET" action="{{ route('opportunities.index') }}" class="flex gap-3 items-center">
        <input type="text"
               name="q"
               value="{{ request('q') }}"
               placeholder="Buscar oportunidades..."
               class="flex-1 px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

        @if(request('stage'))
            <input type="hidden" name="stage" value="{{ request('stage') }}">
        @endif

        <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800">
            Buscar
        </button>
    </form>

    <div class="flex flex-wrap gap-2 mt-4 text-sm">
        @php
            $stages = $stages ?? ['Todos','Prospecto','Negociación','Cerrado Ganado','Cerrado Perdido'];
            $current = request('stage', 'Todos');
        @endphp

        @foreach($stages as $st)
            <a href="{{ route('opportunities.index', array_filter(['stage'=>$st, 'q'=>request('q')])) }}"
               class="px-3 py-1 rounded-lg
               {{ $current === $st ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                {{ $st }}
            </a>
        @endforeach
    </div>
</div>

{{-- Lista (cards) --}}
<div class="space-y-4">
@foreach($opportunities as $op)

    <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
        <div class="flex justify-between items-start gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 text-xs mb-2">
                    <span class="text-slate-400 font-semibold">OPP-{{ str_pad($op->id, 4, '0', STR_PAD_LEFT) }}</span>

                    @php $stage = $op->stage; @endphp
                    @if($stage === 'Prospecto')
                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-full">Prospecto</span>
                    @elseif($stage === 'Negociación')
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">Negociación</span>
                    @elseif($stage === 'Cerrado Ganado')
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full">Cerrado Ganado</span>
                    @elseif($stage === 'Cerrado Perdido')
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full">Cerrado Perdido</span>
                    @else
                        <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full">{{ $stage }}</span>
                    @endif
                </div>

                <h3 class="font-semibold text-lg mb-1">
                    Cliente ID: {{ $op->customer_id }}
                </h3>

                <p class="text-sm text-slate-500 mb-3">
                    {{ Str::limit($op->description ?? '', 120) }}
                </p>

                <div class="flex flex-wrap items-center gap-6 text-xs text-slate-400">
                    <span>Monto: Q {{ number_format($op->amount, 2) }}</span>
                    <span>Cierre estimado: {{ optional($op->close_date)->format('Y-m-d') ?? '—' }}</span>
                    <span>Creado: {{ $op->created_at->format('Y-m-d') }}</span>
                </div>
            </div>

            <div class="shrink-0">
                <a href="{{ route('opportunities.stageForm', $op) }}"
                   class="text-indigo-600 text-sm hover:underline">
                    Cambiar etapa
                </a>
            </div>
        </div>
    </div>

@endforeach

    <div class="mt-6">
        {{ $opportunities->links() }}
    </div>
</div>

@endsection
