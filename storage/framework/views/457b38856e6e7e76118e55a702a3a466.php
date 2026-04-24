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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Historial de la Oportunidad #<?php echo e($opportunity->id); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Información general</h3>

                <p><strong>ID:</strong> <?php echo e($opportunity->id); ?></p>
                <p><strong>Cliente ID:</strong> <?php echo e($opportunity->customer_id ?? 'Sin cliente'); ?></p>
                <p><strong>Etapa actual:</strong> <?php echo e($opportunity->stage); ?></p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Historial de cambios de etapa</h3>

                <?php if($opportunity->stageHistories->count()): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Usuario</th>
                                    <th class="border px-4 py-2 text-left">Etapa anterior</th>
                                    <th class="border px-4 py-2 text-left">Etapa nueva</th>
                                    <th class="border px-4 py-2 text-left">Fecha y hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $opportunity->stageHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="border px-4 py-2"><?php echo e($history->user->name ?? 'Sin usuario'); ?></td>
                                        <td class="border px-4 py-2"><?php echo e($history->old_stage); ?></td>
                                        <td class="border px-4 py-2"><?php echo e($history->new_stage); ?></td>
                                        <td class="border px-4 py-2">
                                            <?php echo e(\Carbon\Carbon::parse($history->changed_at)->format('d/m/Y h:i A')); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">Aún no hay cambios de etapa registrados.</p>
                <?php endif; ?>
            </div>

            <div class="flex gap-3">
                <a href="<?php echo e(route('opportunities.show', $opportunity)); ?>"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Volver a la oportunidad
                </a>

                <a href="<?php echo e(route('opportunities.index')); ?>"
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Volver al listado
                </a>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\PC\Documents\crm-laravel_ing_software\resources\views/opportunities/history.blade.php ENDPATH**/ ?>