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

        $stages = ['Todos','Prospecto','Negociación','Cerrado Ganado','Cerrado Perdido'];

        return view('opportunities.index', compact('opportunities','stages','stage','q'));
    }

    public function create()
    {
        return view('opportunities.create');
    }


        public function store(Request $request)
        {
            $request->validate([
                'customer_id'          => 'required|integer',
                'amount'               => 'required|numeric|min:0',
                'estimated_close_date' => 'nullable|date',
                'description'          => 'nullable|string|max:1000',
            ]);

            Opportunity::create([
                'customer_id' => $request->customer_id,
                'amount' => $request->amount,
                'estimated_close_date' => $request->estimated_close_date,
                'description' => $request->description,
                'stage' => 'Prospecto',          // H1 inicia en Prospecto
                'created_by' => auth()->id(),     // usuario logueado
            ]);

            return redirect()->route('opportunities.index')
                ->with('success', 'Oportunidad creada correctamente.');
        }

    public function stageForm(Opportunity $opportunity)
    {
        $stages = ['Prospecto', 'Negociación', 'Cerrado Ganado', 'Cerrado Perdido'];
        return view('opportunities.stage', compact('opportunity', 'stages'));
    }

    public function stageUpdate(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'stage' => 'required|in:Prospecto,Negociación,Cerrado Ganado,Cerrado Perdido',
        ]);

        $old = $opportunity->stage;
        $new = $request->stage;

        // H2: Guardar historial
        OpportunityStageHistory::create([
            'opportunity_id' => $opportunity->id,
            'old_stage' => $old,
            'new_stage' => $new,
            'changed_by' => Auth::id(),
            'changed_at' => Carbon::now(),
        ]);

        $opportunity->update(['stage' => $new]);

        return redirect()->route('opportunities.index')->with('success', 'Etapa actualizada correctamente.');
    }
       
    public function searchCustomers(Request $request, CustomerService $customerService)
    {
        $term = $request->get('term', '');

        $customers = $customerService->searchCustomers($term);

        return response()->json($customers);
    }
}
