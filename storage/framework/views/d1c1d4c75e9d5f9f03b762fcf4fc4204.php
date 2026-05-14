<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Cambiar Etapa</h2>
                    <a href="<?php echo e(route('opportunities.index')); ?>" class="text-sm text-gray-600 hover:underline">
                        Volver a Oportunidades
                    </a>
                </div>

                <?php if($errors->any()): ?>
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-800">
                        <ul class="list-disc pl-5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="mb-4 text-sm text-gray-700">
                    <div><b>Oportunidad:</b> #<?php echo e($opportunity->id); ?></div>
                    <div><b>Cliente:</b> <?php echo e($opportunity->customer_name ?? $opportunity->customer_id); ?></div>
                    <div><b>Etapa actual:</b> <?php echo e($opportunity->stage); ?></div>
                </div>

                   <form id="stageForm" method="POST" action="<?php echo e(route('opportunities.stageUpdate', $opportunity)); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>

    <div>
        <label class="block text-sm font-medium text-gray-700">Nueva etapa</label>
        <select id="stage" name="stage" class="mt-1 w-full rounded-lg border-gray-300" required>
            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s); ?>" <?php if($s === $opportunity->stage): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <button type="submit"
            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white text-sm font-semibold hover:bg-indigo-700">
        Actualizar etapa
    </button>
</form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('stageForm');
            const stageSelect = document.getElementById('stage');

            if (form && stageSelect) {
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\crm-laravel_ing_software\resources\views/opportunities/stage.blade.php ENDPATH**/ ?>