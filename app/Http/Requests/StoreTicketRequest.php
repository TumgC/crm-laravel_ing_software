<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Baja,Media,Alta,Crítica',
            'status' => 'required|string|max:50',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'frequent_problem' => 'nullable|string',
        ];
    }
}