<?php

namespace App\Services;

use App\Models\Opportunity;
use App\Models\OpportunityProposal;
use App\Models\OpportunityProposalHistory;

class OpportunityProposalService
{
    public function store(Opportunity $opportunity, array $data): OpportunityProposal
    {
        $file = $data['file'];
        $path = $file->store('proposals', 'public');

        return OpportunityProposal::create([
            'opportunity_id' => $opportunity->id,
            'title' => $data['title'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'uploaded_at' => now(),
            'uploaded_by' => auth()->id(),
            'status' => 'Borrador',
        ]);
    }

    public function changeStatus(OpportunityProposal $proposal, string $newStatus): OpportunityProposal
    {
        OpportunityProposalHistory::create([
            'proposal_id' => $proposal->id,
            'old_status' => $proposal->status,
            'new_status' => $newStatus,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        $proposal->update([
            'status' => $newStatus,
        ]);

        return $proposal;
    }
}