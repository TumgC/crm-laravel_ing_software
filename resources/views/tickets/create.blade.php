@extends('layouts.dashboard')

@section('title', 'Crear Ticket')

@section('content')
<div class="max-w-4xl mx-auto">

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

    <div class="bg-white rounded-2xl shadow-sm border p-6">

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

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

            <div class="mb-4">
                <label for="customer_search" class="block font-medium text-sm text-gray-700">Buscar cliente</label>
                <input
                    type="text"
                    id="customer_search"
                    class="w-full border rounded-lg px-3 py-2"
                    placeholder="Buscar por nombre, apellido o DPI"
                    autocomplete="off"
                >
                <div id="customer_results" class="border rounded-lg bg-white mt-1 hidden"></div>
            </div>

            <div class="mb-4">
                <label for="customer_selected" class="block font-medium text-sm text-gray-700">Cliente seleccionado</label>
                <input
                    type="text"
                    id="customer_selected"
                    class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                    placeholder="Aún no se ha seleccionado un cliente"
                    readonly
                >
            </div>

            <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Asunto</label>
                <input type="text"
                       name="subject"
                       id="subject"
                       value="{{ old('subject') }}"
                       class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: No puedo iniciar sesión"
                       required>
            </div>

            <div class="mb-4">
                <label for="common_issue" class="block font-medium text-sm text-gray-700">Problema frecuente</label>
                <select id="common_issue" name="frequent_problem" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Selecciona una opción</option>
                    <option value="No puedo iniciar sesión" {{ old('frequent_problem') == 'No puedo iniciar sesión' ? 'selected' : '' }}>
                        No puedo iniciar sesión
                    </option>
                    <option value="Olvidé mi contraseña" {{ old('frequent_problem') == 'Olvidé mi contraseña' ? 'selected' : '' }}>
                        Olvidé mi contraseña
                    </option>
                    <option value="Error al generar factura" {{ old('frequent_problem') == 'Error al generar factura' ? 'selected' : '' }}>
                        Error al generar factura
                    </option>
                    <option value="Problema con acceso al sistema" {{ old('frequent_problem') == 'Problema con acceso al sistema' ? 'selected' : '' }}>
                        Problema con acceso al sistema
                    </option>
                    <option value="Consulta sobre pedido" {{ old('frequent_problem') == 'Consulta sobre pedido' ? 'selected' : '' }}>
                        Consulta sobre pedido
                    </option>
                    <option value="Fallo en actualización de datos" {{ old('frequent_problem') == 'Fallo en actualización de datos' ? 'selected' : '' }}>
                        Fallo en actualización de datos
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                <textarea name="description"
                          id="description"
                          rows="5"
                          class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                          placeholder="Describe el problema..."
                          required>{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Prioridad</label>
                <select name="priority"
                        class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                        required>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', 'Media') === $priority)>
                            {{ $priority }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                    <select name="status"
                            class="w-full rounded-lg border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500"
                            required>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ old('status', 'Abierto') == $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Asignado a</label>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('customer_search');
        const resultsBox = document.getElementById('customer_results');
        const customerIdInput = document.getElementById('customer_id');
        const customerSelectedInput = document.getElementById('customer_selected');
        const commonIssueSelect = document.getElementById('common_issue');
        const descriptionInput = document.getElementById('description');

        if (searchInput) {
            searchInput.addEventListener('input', async function () {
                const term = this.value.trim();

                if (term.length < 2) {
                    resultsBox.innerHTML = '';
                    resultsBox.classList.add('hidden');
                    return;
                }

                try {
                    const response = await fetch(`/tickets/search-customers?term=${encodeURIComponent(term)}`);
                    const customers = await response.json();

                    if (!customers.length) {
                        resultsBox.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">No se encontraron clientes</div>';
                        resultsBox.classList.remove('hidden');
                        return;
                    }

                    resultsBox.innerHTML = customers.map(customer => `
                        <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer customer-option"
                             data-id="${customer.id}"
                             data-name="${customer.first_name} ${customer.last_name}"
                             data-dpi="${customer.dpi}">
                            <strong>${customer.first_name} ${customer.last_name}</strong><br>
                            <small>DPI: ${customer.dpi} | ID: ${customer.id}</small>
                        </div>
                    `).join('');

                    resultsBox.classList.remove('hidden');

                    document.querySelectorAll('.customer-option').forEach(option => {
                        option.addEventListener('click', function () {
                            const id = this.dataset.id;
                            const name = this.dataset.name;
                            const dpi = this.dataset.dpi;

                            customerIdInput.value = id;
                            customerSelectedInput.value = `${name} - DPI: ${dpi} - ID: ${id}`;
                            searchInput.value = name;
                            resultsBox.innerHTML = '';
                            resultsBox.classList.add('hidden');
                        });
                    });
                } catch (error) {
                    resultsBox.innerHTML = '<div class="px-3 py-2 text-sm text-red-500">Error al buscar clientes</div>';
                    resultsBox.classList.remove('hidden');
                }
            });
        }

        if (commonIssueSelect) {
            commonIssueSelect.addEventListener('change', function () {
                const value = this.value;

                if (descriptionInput) {
                    if (value) {
                        descriptionInput.value = `El cliente reporta el siguiente problema: ${value}.`;
                    } else {
                        descriptionInput.value = '';
                    }
                }
            });
        }
    });
</script>
@endsection