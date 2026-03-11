<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CRM') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r min-h-screen flex flex-col">
            {{-- Logo / título --}}
            <div class="px-6 py-5">
                <div class="text-lg font-bold text-indigo-600">Helpdesk</div>
                <div class="text-xs text-slate-500">Sistema de Tickets</div>
            </div>

            {{-- Menú --}}
            <nav class="px-3 space-y-1 text-sm">
                <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700">
                    <span>📊</span> <span>Dashboard</span>
                </a>

                <div class="px-3 pt-3 text-xs text-slate-400 uppercase">KPIs</div>

                <a href="{{ route('opportunities.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700">
                    <span>💼</span> <span>Ver Ventas</span>
                </a>

                <a href="{{ route('opportunities.create') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700">
                    <span>➕</span> <span>Crear Oportunidad</span>
                </a>

                <a href="{{ route('tickets.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700">
                    <span>🎫</span> <span>Ver Tickets</span>
                </a>

                <a href="{{ route('tickets.create') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700">
                    <span>📝</span> <span>Crear Ticket</span>
                </a>

                <a href="#"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700">
                    <span>👥</span> <span>Empleados</span>
                </a>

                <div class="my-3 border-t"></div>

                <a href="#"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700">
                    <span>👤</span> <span>Crear Usuario</span>
                </a>
            </nav>

            {{-- Usuario abajo --}}
            <div class="mt-auto border-t p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Usuario' }}</div>
                    <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</div>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b flex items-center justify-between px-6">
                <div class="font-semibold text-gray-800">
                    @yield('title', 'Dashboard')
                </div>

                <div class="text-sm text-gray-600">
                    {{ Auth::user()->email ?? '' }}
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 bg-sky-50 min-h-screen p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
