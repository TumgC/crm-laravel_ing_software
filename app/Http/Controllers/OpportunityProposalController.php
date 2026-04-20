<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpportunityProposalRequest;
use App\Models\Opportunity;
use App\Models\OpportunityProposal;
use App\Services\OpportunityProposalService;
use Illuminate\Http\Request;

class OpportunityProposalController extends Controller
{
    protected OpportunityProposalService $service;

    public function __construct(OpportunityProposalService $service)
    {
        $this->service = $service;
    }

    public function store(StoreOpportunityProposalRequest $request, Opportunity $opportunity)
    {
        $this->service->store($opportunity, $request->validated());

        return back()->with('success', 'Propuesta subida correctamente.');
    }

    public function updateStatus(Request $request, OpportunityProposal $proposal)
    {
        $request->validate([
            'status' => 'required|in:Borrador,Enviada,En Revisión,Aceptada,Rechazada',
        ]);

        $this->service->changeStatus($proposal, $request->status);

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}