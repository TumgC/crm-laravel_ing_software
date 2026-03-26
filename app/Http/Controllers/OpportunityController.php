<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\OpportunityStageHistory;
use App\Services\CustomerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityController extends Controller
{

    public function index(\Illuminate\Http\Request $request)
    {
        $stage = $request->query('stage');
        $q     = $request->query('q');

        $query = \App\Models\Opportunity::query();

        if ($stage && $stage !== 'Todos') {
            $query->where('stage', $stage);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('customer_id', 'like', "%{$q}%")
                    ->orWhere('amount', 'like', "%{$q}%");
            });
        }

            $opportunities = $query->orderByDesc('created_at')
                ->paginate(10)
                ->withQueryString();

            $stages = [
                'Todos',
                'Prospecto',
                'Negociación',
                'Cerrado Ganado',
                'Cerrado Perdido',
            ];

            $realStages = [
                'Prospecto',
                'Negociación',
                'Cerrado Ganado',
                'Cerrado Perdido',
            ];

            $stageCounts = array_fill_keys($realStages, 0);

            $realCounts = \App\Models\Opportunity::selectRaw('stage, COUNT(*) as total')
                ->groupBy('stage')
                ->pluck('total', 'stage')
                ->toArray();

        foreach ($realCounts as $stage => $total) {
        if (array_key_exists($stage, $stageCounts)) {
                $stageCounts[$stage] = $total;
            }
        }

        $totalOpportunities = \App\Models\Opportunity::count();

        return view('opportunities.index', compact('opportunities','stages','stage','stageCounts','totalOpportunities', 'q'));
    }

    public function show($id)
        
        {
            // Obtener la oportunidad con las relaciones 'assignedTo' y 'stageHistories'
            $opportunity = Opportunity::with(['assignedTo', 'stageHistories'])->findOrFail($id);

            // Pasar la oportunidad a la vista
            return view('opportunities.show', compact('opportunity'));
        }


    public function create()
        {
        return view('opportunities.create');
        }

   public function store(Request $request, CustomerService $customerService)
        {
        $request->validate([
            'customer_id'          => 'required|integer|min:1',
            'amount'               => 'required|numeric|min:0',
            'estimated_close_date' => 'nullable|date',
            'description'          => 'nullable|string|max:1000',
        ]);

        $customer = $this->findCustomerById((int) $request->customer_id, $customerService);

            if (!$customer) {
                return back()
                    ->withErrors(['customer_id' => 'El cliente no existe.'])
                    ->withInput();
            }

            $status = $customer['status'] ?? $customer['estado'] ?? null;

            $isActive = false;

            if (is_bool($status)) {
                $isActive = $status;
            } elseif (is_numeric($status)) {
                $isActive = (int) $status === 1;
            } elseif (is_string($status)) {
                $normalizedStatus = mb_strtolower(trim($status));
                $isActive = in_array($normalizedStatus, ['activo', 'activa', 'active', '1', 'true', 'habilitado']);
            }

            if (!$isActive) {
                return back()
                    ->withErrors(['customer_id' => 'El cliente no está activo.'])
                    ->withInput();
            }

            $customerName =
                $customer['name']
                ?? $customer['full_name']
                ?? $customer['customer_name']
                ?? $customer['nombre']
                ?? trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''));

            Opportunity::create([
                'customer_id'          => $request->customer_id,
                'customer_name'        => $customerName,
                'amount'               => $request->amount,
                'estimated_close_date' => $request->estimated_close_date,
                'description'          => $request->description,
                'stage'                => 'Prospecto',
                'created_by'           => auth()->id(),
            ]);

            return redirect()->route('opportunities.index')
                ->with('success', 'Oportunidad creada correctamente.');
        }
    
    public function stageForm(Opportunity $opportunity)
    {
        $stages = ['Prospecto', 'Negociacion', 'Cerrado Ganado', 'Cerrado Perdido'];
        return view('opportunities.stage', compact('opportunity', 'stages'));
    }

    public function stageUpdate(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'stage' => 'required|in:Prospecto,Negociacion,Cerrado Ganado,Cerrado Perdido',
        ]);

        $old = $opportunity->stage;
        $new = $request->stage;

        // Solo guardar si realmente hubo cambio
        if ($old !== $new) {

            OpportunityStageHistory::create([
                'opportunity_id' => $opportunity->id,
                'old_stage'      => $old,
                'new_stage'      => $new,
                'changed_by'     => Auth::id(),
                'changed_at'     => Carbon::now(),
            ]);

            $opportunity->update([
                'stage' => $new,
            ]);
        }

        return redirect()
            ->route('opportunities.show', $opportunity)
            ->with('success', 'Etapa actualizada correctamente.');
    }
       
    public function searchCustomers(Request $request, CustomerService $customerService)
    {
        $term = $request->get('term', '');

        $customers = $customerService->searchCustomers($term);

        return response()->json($customers);
    }
    
    public function history(Opportunity $opportunity)
    {
        $opportunity->load(['stageHistories.user']);

        return view('opportunities.history', compact('opportunity'));
    }

    private function findCustomerById(int $customerId, CustomerService $customerService): ?array
    {
        $customers = $customerService->searchCustomers((string) $customerId);

        if (!is_array($customers)) {
            return null;
        }

        foreach ($customers as $customer) {
            $id = $customer['id'] ?? $customer['customer_id'] ?? null;

            if ((int) $id === (int) $customerId) {
                return $customer;
            }
        }

        return null;
        }
        
    public function changeStage(Request $request, Opportunity $opportunity)
    {
        $this->validate($request, [
            'new_stage' => 'required|string', // Asegúrate de validar el nuevo valor de la etapa
        ]);

        // Registrar el cambio de etapa en el historial
        OpportunityStageHistory::create([
            'opportunity_id' => $opportunity->id,
            'old_stage' => $opportunity->stage,
            'new_stage' => $request->new_stage,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        // Actualizar la etapa de la oportunidad
        $opportunity->update(['stage' => $request->new_stage]);

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('opportunities.show', $opportunity)->with('success', 'Etapa cambiada con éxito.');
    }


}
