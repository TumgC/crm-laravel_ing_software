<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpportunityRequest;
use App\Http\Requests\UpdateOpportunityStageRequest;
use App\Models\Opportunity;
use App\Models\SupportCheckLog;
use App\Services\CustomerService;
use App\Services\OpportunityService;
use App\Services\SupportStatusService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class OpportunityController extends Controller
{
    protected OpportunityService $opportunityService;
    protected CustomerService $customerService;
    protected SupportStatusService $supportStatusService;

    public function __construct(
        OpportunityService $opportunityService,
        CustomerService $customerService,
        SupportStatusService $supportStatusService
    ) {
        $this->opportunityService = $opportunityService;
        $this->customerService = $customerService;
        $this->supportStatusService = $supportStatusService;
    }

    public function index(Request $request)
    {
        $stage = $request->query('stage');
        $q = $request->query('q');

        $opportunities = $this->opportunityService->getFilteredOpportunities($stage, $q);
        $stages = $this->opportunityService->getStages(true);
        $stageCounts = $this->opportunityService->getStageCounts();
        $totalOpportunities = $this->opportunityService->getTotalOpportunities();

        return view('opportunities.index', compact(
            'opportunities',
            'stages',
            'stage',
            'stageCounts',
            'totalOpportunities',
            'q'
        ));
    }

    public function show(Opportunity $opportunity)
    {
        $opportunity->load([
            'stageHistories.user',
            'proposals.histories',
        ]);

        $customer = null;
        $customers = $this->customerService->searchCustomers((string) $opportunity->customer_id);

        if (is_array($customers)) {
            foreach ($customers as $item) {
                $id = $item['id'] ?? $item['customer_id'] ?? null;

                if ((int) $id === (int) $opportunity->customer_id) {
                    $customer = $item;
                    break;
                }
            }
        }

        return view('opportunities.show', compact('opportunity', 'customer'));
    }

    public function create()
    {
        return view('opportunities.create');
    }

    public function store(StoreOpportunityRequest $request)
    {
        try {
            $this->opportunityService->createOpportunity(
                $request->validated(),
                auth()->id()
            );
        } catch (InvalidArgumentException $e) {
            return back()->withErrors([
                'customer_id' => $e->getMessage(),
            ])->withInput();
        }

        return redirect()
            ->route('opportunities.index')
            ->with('success', 'Oportunidad creada correctamente.');
    }

    public function stageForm(Opportunity $opportunity)
    {
        $stages = $this->opportunityService->getStages();

        return view('opportunities.stage', compact('opportunity', 'stages'));
    }

    public function stageUpdate(UpdateOpportunityStageRequest $request, Opportunity $opportunity)
    {
        $this->opportunityService->updateStage(
            $opportunity,
            $request->validated()['stage'],
            auth()->id()
        );

        return redirect()
            ->route('opportunities.show', $opportunity)
            ->with('success', 'Etapa actualizada correctamente.');
    }

    public function searchCustomers(Request $request)
    {
        $term = $request->get('term', '');
        $customers = $this->customerService->searchCustomers($term);

        return response()->json($customers);
    }

    public function history(Opportunity $opportunity)
    {
        $opportunity->load(['stageHistories.user']);

        return view('opportunities.history', compact('opportunity'));
    }

    public function checkSupportStatus(Opportunity $opportunity)
    {
        try {
            $result = $this->supportStatusService->checkCustomerStatus((int) $opportunity->customer_id);

            SupportCheckLog::create([
                'customer_id' => $opportunity->customer_id,
                'opportunity_id' => $opportunity->id,
                'checked_by' => auth()->id(),
                'checked_at' => now(),
                'has_critical_ticket' => $result['has_critical_ticket'],
                'summary' => $result['has_critical_ticket']
                    ? json_encode($result['summary'], JSON_UNESCAPED_UNICODE)
                    : null,
                'status' => 'success',
                'error_message' => null,
            ]);

            if ($result['has_critical_ticket']) {
                return redirect()
                    ->route('opportunities.show', $opportunity)
                    ->with('support_alert', [
                        'type' => 'critical',
                        'message' => 'El cliente tiene un ticket crítico abierto.',
                        'summary' => $result['summary'],
                    ]);
            }

            return redirect()
                ->route('opportunities.show', $opportunity)
                ->with('support_alert', [
                    'type' => 'ok',
                    'message' => 'El cliente no tiene tickets críticos abiertos.',
                    'summary' => null,
                ]);
        } catch (\Throwable $e) {
            SupportCheckLog::create([
                'customer_id' => $opportunity->customer_id,
                'opportunity_id' => $opportunity->id,
                'checked_by' => auth()->id(),
                'checked_at' => now(),
                'has_critical_ticket' => null,
                'summary' => null,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('opportunities.show', $opportunity)
                ->with('support_alert', [
                    'type' => 'error',
                    'message' => 'No fue posible consultar el estado en Soporte. Intenta nuevamente.',
                    'summary' => null,
                ]);
        }
    }
}