<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Cambiar Etapa</h2>
                    <a href="{{ route('opportunities.index') }}" class="text-sm text-gray-600 hover:underline">
                        Volver a Oportunidades
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
                    <div><b>Oportunidad:</b> #{{ $opportunity->id }}</div>
                    <div><b>Cliente:</b> {{ $opportunity->customer_id }}</div>
                    <div><b>Etapa actual:</b> {{ $opportunity->stage }}</div>
                </div>

               <form id="stageForm" method="POST" action="{{ route('opportunities.stageUpdate', $opportunity) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nueva etapa</label>
                        <select id="stage" name="stage" class="mt-1 w-full rounded-lg border-gray-300" required>
                            @foreach($stages as $s)
                                <option value="{{ $s }}" @selected($s === $opportunity->stage)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white text-sm font-semibold hover:bg-indigo-700">
                        Actualizar etapa
                    </button>
                </form>

            </div>
        </div>
    </div>
    
    <script>
             document.addEventListener('DOMContentLoaded', function () {
             const form = document.getElementById('stageForm');
             const stageSelect = document.getElementById('stage');

           if (form && stageSelect) 
        {
              form.addEventListener('submit', function (e) {
                  const selectedStage = stageSelect.value;

                if (selectedStage === 'Cerrado Ganado' || selectedStage === 'Cerrado Perdido') {
                    const mensaje = '¿Está seguro de cambiar la etapa a "' + selectedStage + '"?';

                    if (!confirm(mensaje)) {
                        e.preventDefault();
                    }
                }
            });
        }
    });
    </script>
</x-app-layout>
