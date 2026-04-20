<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOpportunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'estimated_close_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ];
    }
}