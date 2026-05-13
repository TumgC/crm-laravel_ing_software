<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2a4 4 0 014-4h2" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 7h8m0 0v8m0-8L13 15" />
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">CRM</h1>
            <p class="text-sm text-slate-500">Panel de control (Ventas + Soporte)</p>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        
        <div class="rounded-2xl p-6 text-white shadow-lg bg-gradient-to-br from-blue-500 to-indigo-700">
            <div class="flex items-start justify-between">
                <div class="text-sm opacity-90">Tickets (Total)</div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">+<?php echo e($ticketsTotal); ?></span>
            </div>
            <div class="mt-3 text-3xl font-extrabold"><?php echo e($ticketsTotal); ?></div>
            <div class="mt-1 text-xs opacity-80">Total registrados</div>
        </div>

        
        <div class="rounded-2xl p-6 text-white shadow-lg bg-gradient-to-br from-purple-500 to-fuchsia-700">
            <div class="flex items-start justify-between">
                <div class="text-sm opacity-90">Tickets Abiertos</div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Hoy</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold"><?php echo e($ticketsAbiertos); ?></div>
            <div class="mt-1 text-xs opacity-80">Pendientes de atención</div>
        </div>

        
        <div class="rounded-2xl p-6 text-white shadow-lg bg-gradient-to-br from-emerald-500 to-green-700">
            <div class="flex items-start justify-between">
                <div class="text-sm opacity-90">Tickets Asignados</div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">En curso</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold"><?php echo e($ticketsAsignados); ?></div>
            <div class="mt-1 text-xs opacity-80">Asignados a agentes</div>
        </div>

        
        <div class="rounded-2xl p-6 text-white shadow-lg bg-gradient-to-br from-orange-500 to-red-600">
            <div class="flex items-start justify-between">
                <div class="text-sm opacity-90">Oportunidades</div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Total</span>
            </div>
            <div class="mt-3 text-3xl font-extrabold"><?php echo e($opTotal); ?></div>
            <div class="mt-1 text-xs opacity-80">En el embudo</div>
        </div>

    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow-sm p-6 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-slate-900">Tendencia (preview)</h3>
                    <p class="text-sm text-slate-500">Luego lo conectamos a datos reales</p>
                </div>
                <span class="text-xs bg-slate-100 px-3 py-1 rounded-full text-slate-700">Tiempo real</span>
            </div>

            
            <div class="mt-6 h-56 rounded-xl bg-slate-50 border border-dashed border-slate-200 flex items-center justify-center text-slate-400">
                Aquí irá la gráfica de Tickets / Oportunidades
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-900">Embudo de Ventas</h3>
            <p class="text-sm text-slate-500 mb-4">Por etapa</p>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Prospecto</span>
                    <span class="font-semibold"><?php echo e($opProspecto); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Negociación</span>
                    <span class="font-semibold"><?php echo e($opNegociacion); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Cerrado Ganado</span>
                    <span class="font-semibold"><?php echo e($opCerradoGanado); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Cerrado Perdido</span>
                    <span class="font-semibold"><?php echo e($opCerradoPerdido); ?></span>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-2">
                <a href="<?php echo e(route('opportunities.index')); ?>"
                   class="w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Ver Oportunidades
                </a>
                <a href="<?php echo e(route('tickets.index')); ?>"
                   class="w-full text-center px-4 py-2 bg-slate-100 text-slate-800 rounded-lg hover:bg-slate-200">
                    Ver Tickets
                </a>
            </div>
        </div>

    </div>

    
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="font-semibold text-slate-900">Actividad reciente</h3>
        <p class="text-sm text-slate-500">Luego mostraremos asignaciones y cambios de etapa.</p>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\crm\resources\views/dashboard.blade.php ENDPATH**/ ?>