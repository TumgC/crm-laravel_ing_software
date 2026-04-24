<?php $__env->startSection('title', 'Detalle Ticket'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold"><?php echo e($ticket->ticket_number); ?></h1>
        <p class="text-sm text-slate-500"><?php echo e($ticket->subject); ?></p>
    </div>

    <a href="<?php echo e(route('tickets.index')); ?>" class="text-sm text-slate-600 hover:underline">
        ← Volver a Tickets
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border">
        <h2 class="font-semibold mb-3">Descripción</h2>
        <p class="text-slate-600 text-sm whitespace-pre-line"><?php echo e($ticket->description); ?></p>
    
        
        <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-slate-400">Cliente (Customer ID)</span>
                <div class="font-semibold"><?php echo e($ticket->customer_id); ?></div>
            </div>
            <div>
                <span class="text-slate-400">Creado</span>
                <div class="font-semibold"><?php echo e($ticket->created_at->format('Y-m-d H:i')); ?></div>
            </div>
        </div>
    </div>

    
    <div class="bg-white p-6 rounded-xl shadow-sm border mt-6">
        <h3 class="font-semibold mb-4">Agregar Interacción/Comentario</h3>
        <form method="POST" action="<?php echo e(route('tickets.addInteraction', $ticket)); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label for="comment" class="block text-sm font-medium text-slate-700">Comentario</label>
                <textarea id="comment" name="comment" rows="4" class="mt-1 w-full rounded-lg border-slate-300" required></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                Guardar Interacción
            </button>
        </form>
    </div>

    
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <h2 class="font-semibold mb-4">Actualizar</h2>

        <form method="POST" action="<?php echo e(route('tickets.update', $ticket)); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div>
                <label class="block text-sm font-medium text-slate-700">Estado</label>
                <select name="status" class="mt-1 w-full rounded-lg border-slate-300">
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($st); ?>" <?php echo e($ticket->status === $st ? 'selected' : ''); ?>>
                            <?php echo e($st); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Asignado a</label>
                <select name="assigned_to" class="mt-1 w-full rounded-lg border-slate-300">
                    <option value="">Sin asignar</option>
                    <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($agent->id); ?>" <?php echo e($ticket->assigned_to == $agent->id ? 'selected' : ''); ?>>
                            <?php echo e($agent->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                Guardar cambios
            </button>
        </form>
    </div>

</div>


<div class="bg-white p-6 rounded-xl shadow-sm border mt-6">
    <h3 class="font-semibold mb-4">Historial de Interacciones</h3>
    <ul class="space-y-4">
        <?php $__currentLoopData = $ticket->interactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="p-4 border border-gray-300 rounded">
                <p class="text-sm"><strong>Comentario:</strong> <?php echo e($interaction->comment); ?></p>
                <small class="text-slate-500"><?php echo e($interaction->created_at->format('d/m/Y h:i A')); ?></small>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <?php if($ticket->interactions->isEmpty()): ?>
        <p>No hay interacciones en este ticket.</p>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\crm-laravel_ing_software\resources\views/tickets/show.blade.php ENDPATH**/ ?>