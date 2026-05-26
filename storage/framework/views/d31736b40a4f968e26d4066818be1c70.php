<?php $__env->startSection('title', 'Crear Oportunidad'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Crear Nueva Oportunidad</h1>
        <p class="text-sm text-slate-500">Completa el formulario para registrar una oportunidad de venta</p>
    </div>

    <a href="<?php echo e(route('opportunities.index')); ?>"
       class="text-sm text-slate-600 hover:underline">
        ← Volver a Ventas
    </a>
</div>


<?php if(session('success')): ?>
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php if(session('commercial_alert')): ?>
    <?php $alert = session('commercial_alert'); ?>

    <div class="mb-4 rounded-lg border p-3
        <?php if($alert['type'] === 'ok'): ?> bg-blue-50 border-blue-200 text-blue-800
        <?php elseif($alert['type'] === 'empty'): ?> bg-slate-50 border-slate-200 text-slate-700
        <?php else: ?> bg-yellow-50 border-yellow-200 text-yellow-800
        <?php endif; ?>">

        <p class="font-semibold"><?php echo e($alert['message']); ?></p>

        <?php if(!empty($alert['stage'])): ?>
            <p class="text-sm mt-1">
                Etapa comercial actual: <strong><?php echo e($alert['stage']); ?></strong>
            </p>
        <?php endif; ?>

        <?php if(!empty($alert['reference'])): ?>
            <p class="text-sm">
                Referencia: <?php echo e($alert['reference']); ?>

            </p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-800">
        <ul class="list-disc pl-5">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="bg-white p-6 rounded-xl shadow-sm border max-w-3xl">
    <form method="POST" action="<?php echo e(route('opportunities.store')); ?>" class="space-y-5">
        <?php echo csrf_field(); ?>
        
        <div class="mb-4">
           
        <label for="customer_search" class="block font-medium text-sm text-gray-700">
            Buscar cliente por ID, nombre o DPI <span class="text-red-600">*</span>
            </label>

            <input
                type="text"
                id="customer_search"
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Ingrese ID, nombre o DPI del cliente"
                autocomplete="off"
                required
            >

            <p class="mt-1 text-xs text-slate-500">
                Busque y seleccione un cliente por ID, nombre o DPI para validar si existe y está activo.
            </p>

            <div id="customer_results" class="border rounded-lg bg-white mt-1 hidden"></div>

        </div>

        <div class="mb-4">
            <label for="customer_selected" class="block font-medium text-sm text-gray-700">
                Cliente seleccionado
            </label>

            <input
                type="text"
                id="customer_selected"
                class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                placeholder="Aún no se ha seleccionado un cliente"
                readonly
            >
        </div>

        <input type="hidden" name="customer_id" id="customer_id" value="<?php echo e(old('customer_id')); ?>">

        <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <div>
            <label class="block text-sm font-medium text-slate-700">Descripción de la Oportunidad</label>
            <textarea name="description" rows="4"
                      class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Describe los detalles de la oportunidad..."><?php echo e(old('description')); ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Monto Estimado (Q)</label>
                <input type="number" step="0.01" name="amount" value="<?php echo e(old('amount')); ?>"
                       class="mt-1 w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Ej: 45000" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Fecha Estimada de Cierre</label>
                <input type="date" name="estimated_close_date" value="<?php echo e(old('estimated_close_date')); ?>"
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

            <a href="<?php echo e(route('opportunities.index')); ?>"
               class="inline-flex items-center rounded-lg border px-5 py-2.5 text-sm font-semibold hover:bg-slate-50 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('customer_search');
            const resultsBox = document.getElementById('customer_results');
            const customerIdInput = document.getElementById('customer_id');
            const customerSelectedInput = document.getElementById('customer_selected');
            const form = document.querySelector('form');

            if (searchInput) {
                searchInput.addEventListener('input', async function () {
                    const term = this.value.trim();

                    if (!term) {
                        resultsBox.innerHTML = '';
                        resultsBox.classList.add('hidden');
                        return;
                    }

                    try {
                        const response = await fetch(`/opportunities/search-customers?term=${encodeURIComponent(term)}`);
                        const customers = await response.json();

                        if (!customers.length) {
                            customerIdInput.value = '';
                            customerSelectedInput.value = '';
                            resultsBox.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">No se encontraron clientes con ese criterio de búsqueda</div>';
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
                                searchInput.value = `${name} - ID: ${id}`;
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

            if (form) {
                form.addEventListener('submit', function (e) {
                    if (!customerIdInput.value) {
                        e.preventDefault();
                        alert('Debe seleccionar un cliente válido antes de crear la oportunidad.');
                        searchInput.focus();
                    }
                });
            }
        });
    </script>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\crm\resources\views/opportunities/create.blade.php ENDPATH**/ ?>