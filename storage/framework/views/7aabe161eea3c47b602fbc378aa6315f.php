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
            Detalle de Oportunidad
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('support_alert')): ?>
                <?php $alert = session('support_alert'); ?>

                <div class="px-4 py-3 rounded border
                    <?php if($alert['type'] === 'critical'): ?> bg-red-100 border-red-400 text-red-700
                    <?php elseif($alert['type'] === 'error'): ?> bg-yellow-100 border-yellow-400 text-yellow-700
                    <?php else: ?> bg-blue-100 border-blue-400 text-blue-700
                    <?php endif; ?>">
                    <p class="font-semibold"><?php echo e($alert['message']); ?></p>

                    <?php if(!empty($alert['summary'])): ?>
                        <p class="text-sm mt-1">
                            Categoría: <?php echo e($alert['summary']['category'] ?? 'No disponible'); ?>

                        </p>
                        <p class="text-sm">
                            Fecha del ticket más reciente: <?php echo e($alert['summary']['date'] ?? 'No disponible'); ?>

                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Información de la oportunidad</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p><strong>ID Oportunidad:</strong> #<?php echo e($opportunity->id); ?></p>
                        <p><strong>Cliente ID:</strong> <?php echo e($opportunity->customer_id ?? 'Sin cliente'); ?></p>
                        <p>
                            <strong>Cliente:</strong>
                            <?php if($customer): ?>
                                <?php echo e($customer['name']
                                    ?? $customer['full_name']
                                    ?? $customer['customer_name']
                                    ?? $customer['nombre']
                                    ?? trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''))); ?>

                            <?php else: ?>
                                <?php echo e($opportunity->customer_name ?? 'No asignado'); ?>

                            <?php endif; ?>
                        </p>
                        <p><strong>Etapa actual:</strong> <?php echo e($opportunity->stage); ?></p>
                    </div>

                    <div>
                        <p><strong>Monto:</strong> Q <?php echo e(number_format((float) $opportunity->amount, 2)); ?></p>
                        <p>
                            <strong>Cierre estimado:</strong>
                            <?php echo e($opportunity->estimated_close_date ? \Carbon\Carbon::parse($opportunity->estimated_close_date)->format('d/m/Y') : 'No definido'); ?>

                        </p>
                        <p>
                            <strong>Creado:</strong>
                            <?php echo e($opportunity->created_at ? $opportunity->created_at->format('d/m/Y h:i A') : 'Sin fecha'); ?>

                        </p>
                        <p><strong>Creado por:</strong> <?php echo e($opportunity->created_by ?? 'No disponible'); ?></p>
                    </div>
                </div>

                <div class="mt-4">
                    <p><strong>Descripción:</strong></p>
                    <p class="mt-1 text-gray-700">
                        <?php echo e($opportunity->description ?: 'Sin descripción registrada.'); ?>

                    </p>
                </div>

                 <?php if($opportunity->stage === 'Cerrado Ganado'): ?>
                <div class="mt-4 p-4 bg-green-50 border border-green-300 rounded-lg">
                        <h4 class="font-bold text-green-800 mb-2">
                            Cierre registrado
                        </h4>

                        <p>
                            <strong>Monto final:</strong>
                            Q <?php echo e(number_format((float) ($opportunity->final_amount ?? $opportunity->amount), 2)); ?>

                        </p>

                        <p>
                            <strong>Fecha y hora de cierre:</strong>
                            <?php echo e($opportunity->closed_at ? $opportunity->closed_at->format('d/m/Y h:i A') : 'No registrada'); ?>

                        </p>

                        <p>
                            <strong>Cerrado por:</strong>
                            <?php echo e($opportunity->closedBy->name ?? 'Usuario no disponible'); ?>

                        </p>

                        <p class="mt-2 text-green-700 font-semibold">
                            Notificación interna generada correctamente.
                        </p>
                </div>
              <?php endif; ?>

                <form method="POST" action="<?php echo e(route('opportunities.checkSupport', $opportunity)); ?>" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="inline-block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                        Consultar estado en Soporte
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Historial de Etapas</h3>
                    <a href="<?php echo e(route('opportunities.history', $opportunity)); ?>"
                       class="text-sm text-indigo-600 hover:underline">
                        Ver historial completo
                    </a>
                </div>

                <ul class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $opportunity->stageHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="border rounded-lg p-3">
                            <p>
                                <strong>De:</strong> <?php echo e($history->old_stage); ?>

                                <strong class="ml-2">A:</strong> <?php echo e($history->new_stage); ?>

                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                <?php echo e($history->changed_at ? $history->changed_at->format('d/m/Y h:i A') : 'Sin fecha'); ?>

                            </p>
                            <p class="text-sm text-gray-600">
                                Cambiado por: <?php echo e($history->user->name ?? 'Usuario no disponible'); ?>

                            </p>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="text-gray-500">No hay historial de etapas para esta oportunidad.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Propuestas</h3>

                <?php if($errors->any()): ?>
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4">
                        <p class="font-semibold text-red-700 mb-1">Revisa lo siguiente:</p>
                        <ul class="text-sm text-red-600 list-disc pl-5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST"
                      action="<?php echo e(route('proposals.store', $opportunity)); ?>"
                      enctype="multipart/form-data"
                      class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                    <?php echo csrf_field(); ?>

                    <input type="text"
                           name="title"
                           value="<?php echo e(old('title')); ?>"
                           placeholder="Título de la propuesta"
                           class="border rounded-lg px-3 py-2"
                           required>

                    <input type="file"
                           name="file"
                           class="border rounded-lg px-3 py-2"
                           accept=".pdf,.doc,.docx"
                           required>

                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Subir
                    </button>
                </form>

                <?php $__empty_1 = true; $__currentLoopData = $opportunity->proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border rounded-lg p-4 mb-4 bg-gray-50">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex-1">
                                <p class="font-semibold text-base"><?php echo e($proposal->title); ?></p>

                                <p class="text-sm text-gray-600 mt-1">
                                    Archivo: <?php echo e($proposal->file_name); ?>

                                </p>

                                <p class="text-sm text-gray-600">
                                    Estado:
                                    <span class="font-medium"><?php echo e($proposal->status); ?></span>
                                </p>

                                <p class="text-sm text-gray-600">
                                    Fecha de carga:
                                    <?php echo e($proposal->uploaded_at ? $proposal->uploaded_at->format('d/m/Y h:i A') : 'Sin fecha'); ?>

                                </p>

                                <a href="<?php echo e(asset('storage/' . $proposal->file_path)); ?>"
                                   target="_blank"
                                   class="inline-block mt-2 text-blue-600 text-sm underline">
                                    Ver archivo
                                </a>
                            </div>

                            <div class="w-full md:w-72">
                                <form method="POST" action="<?php echo e(route('proposals.status', $proposal)); ?>" class="flex gap-2">
                                    <?php echo csrf_field(); ?>

                                    <select name="status" class="border rounded-lg px-2 py-1 w-full">
                                        <option value="Borrador" <?php if($proposal->status === 'Borrador'): echo 'selected'; endif; ?>>Borrador</option>
                                        <option value="Enviada" <?php if($proposal->status === 'Enviada'): echo 'selected'; endif; ?>>Enviada</option>
                                        <option value="En Revisión" <?php if($proposal->status === 'En Revisión'): echo 'selected'; endif; ?>>En Revisión</option>
                                        <option value="Aceptada" <?php if($proposal->status === 'Aceptada'): echo 'selected'; endif; ?>>Aceptada</option>
                                        <option value="Rechazada" <?php if($proposal->status === 'Rechazada'): echo 'selected'; endif; ?>>Rechazada</option>
                                    </select>

                                    <button type="submit"
                                            class="bg-gray-700 text-white px-3 py-1 rounded">
                                        Actualizar
                                    </button>
                                </form>

                                <?php if($proposal->status === 'Aceptada' && $opportunity->stage !== 'Cerrado Ganado'): ?>
                                    <form method="POST"
                                          action="<?php echo e(route('opportunities.stageUpdate', $opportunity)); ?>"
                                          class="mt-3"
                                          onsubmit="return confirm('¿Desea marcar esta oportunidad como Cerrado Ganado?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="stage" value="Cerrado Ganado">

                                        <button type="submit"
                                                class="w-full bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">
                                            Marcar como Cerrado Ganado
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Historial de estados</h4>

                            <?php if($proposal->histories->count() > 0): ?>
                                <ul class="space-y-2">
                                    <?php $__currentLoopData = $proposal->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="border rounded-lg px-3 py-2 bg-white text-sm text-gray-600">
                                            De <strong><?php echo e($history->old_status); ?></strong>
                                            a <strong><?php echo e($history->new_status); ?></strong>
                                            <br>
                                            <span class="text-xs text-gray-500">
                                                <?php echo e($history->changed_at ? $history->changed_at->format('d/m/Y h:i A') : 'Sin fecha'); ?>

                                                | Usuario ID: <?php echo e($history->changed_by); ?>

                                            </span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">No hay historial de cambios para esta propuesta.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-8 text-center text-gray-500">
                        No hay propuestas registradas para esta oportunidad.
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="<?php echo e(route('opportunities.stageForm', $opportunity)); ?>"
                   class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Cambiar Etapa
                </a>

                <a href="<?php echo e(route('opportunities.index')); ?>"
                   class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Volver a Oportunidades
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
<?php endif; ?><?php /**PATH C:\Users\PC\Documents\crm-laravel_ing_software\resources\views/opportunities/show.blade.php ENDPATH**/ ?>