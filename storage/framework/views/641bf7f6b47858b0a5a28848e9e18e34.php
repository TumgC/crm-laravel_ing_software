<?php $__env->startSection('title', 'Ventas'); ?>

<?php $__env->startSection('content'); ?>
<?php use Illuminate\Support\Str; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Ventas</h1>
        <p class="text-sm text-slate-500">Gestiona todas las oportunidades de venta</p>
    </div>

    <a href="<?php echo e(route('opportunities.create')); ?>"
       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
        + Crear Oportunidad
    </a>
</div>


<div class="bg-white p-4 rounded-xl shadow-sm border mb-6">
    <form method="GET" action="<?php echo e(route('opportunities.index')); ?>" class="flex gap-3 items-center">
        <input type="text"
               name="q"
               value="<?php echo e(request('q')); ?>"
               placeholder="Buscar oportunidades..."
               class="flex-1 px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

        <?php if(request('stage')): ?>
            <input type="hidden" name="stage" value="<?php echo e(request('stage')); ?>">
        <?php endif; ?>

        <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm hover:bg-slate-800">
            Buscar
        </button>
    </form>

    <div class="flex gap-2 mt-4 text-sm flex-wrap">
            <?php
                $current = request('stage', 'Todos');
                $q = request('q');
            ?>

            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $params = ['q' => $q];

                    if ($st !== 'Todos') {
                        $params['stage'] = $st;
                    }

                    $params = array_filter($params, fn($v) => $v !== null && $v !== '');

                    $count = $st === 'Todos'
                        ? ($totalOpportunities ?? 0)
                        : ($stageCounts[$st] ?? 0);
                ?>

                <a href="<?php echo e(route('opportunities.index', $params)); ?>"
                class="px-3 py-1 rounded-lg
                <?php echo e($current === $st ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">
                    <?php echo e($st); ?> (<?php echo e($count); ?>)
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>


</div>


<div class="space-y-4">
<?php $__currentLoopData = $opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">
        <div class="flex justify-between items-start gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 text-xs mb-2">
                    <span class="text-slate-400 font-semibold">OPP-<?php echo e(str_pad($op->id, 4, '0', STR_PAD_LEFT)); ?></span>

                    <?php $stage = $op->stage; ?>
                    <?php if($stage === 'Prospecto'): ?>
                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-full">Prospecto</span>
                    <?php elseif($stage === 'Negociación'): ?>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">Negociación</span>
                    <?php elseif($stage === 'Cerrado Ganado'): ?>
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full">Cerrado Ganado</span>
                    <?php elseif($stage === 'Cerrado Perdido'): ?>
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full">Cerrado Perdido</span>
                    <?php else: ?>
                        <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full"><?php echo e($stage); ?></span>
                    <?php endif; ?>
                </div>

                <h3 class="font-semibold text-lg mb-1">
                  <?php echo e($op->customer_name ?? 'Cliente sin nombre'); ?>

                </h3>

                <p class="text-sm text-slate-500 mb-3">
                    ID Cliente: <?php echo e($op->customer_id); ?>

                </p>

                <p class="text-sm text-slate-500 mb-3">
                    <?php echo e(Str::limit($op->description ?? '', 120)); ?>

                </p>

                <div class="flex flex-wrap items-center gap-6 text-xs text-slate-400">
                    <span>Monto: Q <?php echo e(number_format($op->amount, 2)); ?></span>
                    <span>Cierre estimado:<?php echo e($op->estimated_close_date ? \Carbon\Carbon::parse($op->estimated_close_date)->format('Y-m-d') : '—'); ?></span>
                    <span>Creado: <?php echo e($op->created_at->format('Y-m-d')); ?></span>
                </div>
            </div>

            <div class="shrink-0">
                <a href="<?php echo e(route('opportunities.stageForm', $op)); ?>"
                   class="text-indigo-600 text-sm hover:underline">
                    Cambiar etapa
                </a>
            </div>
        </div>
    </div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="mt-6">
        <?php echo e($opportunities->links()); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\crm\resources\views/opportunities/index.blade.php ENDPATH**/ ?>