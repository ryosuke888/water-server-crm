<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCallRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],

            'call_type' => ['required', 'string'],
            'call_result' => ['required', 'string'],
            'channel' => ['required', 'string'],

            'call_summary' => ['required', 'string', 'max:1000'],

            'needs_follow_up' => ['required', 'boolean'],
            'follow_up_date' => ['nullable', 'date'],

            'called_at' => ['required', 'date'],
        ];
    }
}
