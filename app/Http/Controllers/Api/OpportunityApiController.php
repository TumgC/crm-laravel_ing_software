<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use Illuminate\Http\Request;

class OpportunityApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Opportunity::query();

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $opportunities = $query->orderByDesc('created_at')
            ->get()
            ->map(function ($opportunity) {
                return [
                    'id' => $opportunity->id,
                    'customer_id' => $opportunity->customer_id,
                    'customer_name' => $opportunity->customer_name,
                    'amount' => $opportunity->amount,
                    'stage' => $opportunity->stage,
                    'estimated_close_date' => $opportunity->estimated_close_date,
                    'created_at' => $opportunity->created_at,
                    'updated_at' => $opportunity->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'module' => 'crm_sales',
            'data' => $opportunities,
        ]);
    }
}
