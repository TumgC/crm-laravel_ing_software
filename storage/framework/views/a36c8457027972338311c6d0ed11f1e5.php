<?php $__env->startSection('title', 'Tickets'); ?>

<?php $__env->startSection('content'); ?>

<?php use Illuminate\Support\Str; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Tickets</h1>
        <p class="text-sm text-slate-500">Gestiona todos los tickets de soporte</p>
    </div>

    <a href="<?php echo e(route('tickets.create')); ?>"
       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
        + Crear Ticket
    </a>
</div>


<div class="bg-white p-4 rounded-xl shadow-sm border mb-6">

    <form method="GET" action="<?php echo e(route('tickets.index')); ?>" class="grid grid-cols-1 md:grid-cols-5 gap-3">

        
        <input type="text"
               name="q"
               value="<?php echo e(request('q')); ?>"
               placeholder="Buscar tickets..."
               class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

        
        <select name="priority" class="px-4 py-2 border rounded-lg text-sm">
            <option value="">Todas las prioridades</option>
            <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p); ?>" <?php if(request('priority') == $p): echo 'selected'; endif; ?>>
                    <?php echo e($p); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="status" class="px-4 py-2 border rounded-lg text-sm">
            <option value="">Todos los estados</option>
            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($st); ?>" <?php if(request('status') == $st): echo 'selected'; endif; ?>>
                    <?php echo e($st); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select name="assigned_to" class="px-4 py-2 border rounded-lg text-sm">
            <option value="">Todos</option>
            <option value="unassigned" <?php if(request('assigned_to') == 'unassigned'): echo 'selected'; endif; ?>>
                Sin asignar
            </option>

            <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($agent->id); ?>" <?php if(request('assigned_to') == $agent->id): echo 'selected'; endif; ?>>
                    <?php echo e($agent->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800">
                Filtrar
            </button>

            <a href="<?php echo e(route('tickets.index')); ?>"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">
                Limpiar
            </a>
        </div>

    </form>

</div>


<div class="space-y-4">

    <?php if($tickets->count() > 0): ?>

        <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">

                <div class="flex justify-between items-start">

                    <div class="flex-1">

                        
                        <div class="flex items-center gap-3 text-xs mb-2">

                            <span class="text-slate-400 font-semibold">
                                <?php echo e($ticket->ticket_number); ?>

                            </span>

                            
                            <?php if($ticket->status == 'Abierto'): ?>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                    Abierto
                                </span>
                            <?php elseif($ticket->status == 'Asignado'): ?>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                                    Asignado
                                </span>
                            <?php elseif($ticket->status == 'Cerrado'): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    Cerrado
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs">
                                    <?php echo e($ticket->status); ?>

                                </span>
                            <?php endif; ?>

                            
                            <?php if($ticket->priority == 'Alta' || $ticket->priority == 'Crítica'): ?>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                    <?php echo e($ticket->priority); ?>

                                </span>
                            <?php elseif($ticket->priority == 'Media'): ?>
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs">
                                    <?php echo e($ticket->priority); ?>

                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                    <?php echo e($ticket->priority); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                        
                        <h3 class="font-semibold text-lg mb-1">
                            <?php echo e($ticket->subject); ?>

                        </h3>

                        
                        <p class="text-sm text-slate-500 mb-3">
                            <?php echo e(Str::limit($ticket->description, 120)); ?>

                        </p>

                        
                        <div class="flex items-center gap-6 text-xs text-slate-400">
                            <span>
                                Cliente: <?php echo e($ticket->customer_name ?? $ticket->customer_id); ?>

                            </span>

                            <span>
                                Asignado a: <?php echo e(optional($ticket->assignee)->name ?? 'Sin asignar'); ?>

                            </span>

                            <span>
                                Fecha: <?php echo e($ticket->created_at->format('Y-m-d')); ?>

                            </span>
                        </div>

                    </div>

                    
                    <div>
                        <a href="<?php echo e(route('tickets.show', $ticket)); ?>"
                           class="text-indigo-600 text-sm hover:underline">
                            Ver Detalles
                        </a>
                    </div>

                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php else: ?>

        <div class="bg-white p-10 rounded-xl shadow-sm border text-center text-slate-500">
            No hay tickets con estos criterios.
        </div>

    <?php endif; ?>

</div>
<div class="mt-6">
    <?php echo e($tickets->links()); ?>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\PC\Documents\crm-laravel_ing_software\resources\views/tickets/index.blade.php ENDPATH**/ ?>