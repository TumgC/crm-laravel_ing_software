<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Asignar Ticket</h2>
                    <a href="{{ route('tickets.index') }}" class="text-sm text-gray-600 hover:underline">
                        Volver a Tickets
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-800">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4 text-sm text-gray-700">
                    <div><b>Ticket:</b> {{ $ticket->ticket_number }}</div>
                    <div><b>Cliente:</b> {{ $ticket->customer_id }}</div>
                    <div><b>Estado actual:</b> {{ $ticket->status }}</div>
                </div>

                <form method="POST" action="{{ route('tickets.assignStore', $ticket) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Asignar a (agente)</label>
                        <select name="assigned_to" class="mt-1 w-full rounded-lg border-gray-300" required>
                            <option value="">-- Selecciona un agente --</option>
                            @foreach($agents as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                        Asignar Ticket
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
