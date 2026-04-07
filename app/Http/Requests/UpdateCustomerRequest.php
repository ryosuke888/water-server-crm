<?php

namespace App\Http\Requests;

use App\Enums\CustomerContractStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateCustomerRequest extends FormRequest
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
            'name' => 'sometimes|required',
            'phone_number' => ['required', 'string', 'max:20', Rule::unique('customers', 'phone_number')->ignore($customer->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
            'postal_code' => 'sometimes|nullable|string|max:8',
            'prefecture' => 'sometimes|nullable|string|max:20',
            'city' => 'sometimes|nullable|string|max:100',
            'address_line1' => 'sometimes|nullable|string|max:255',
            'address_line2' => 'sometimes|nullable|string|max:255',
            'shipping_name' => 'sometimes|nullable|string|max:100',
            'shipping_postal_code' => 'sometimes|nullable|string|max:8',
            'shipping_prefecture' => 'sometimes|nullable|string|max:20',
            'shipping_city' => 'sometimes|nullable|string|max:100',
            'shipping_address_line1' => 'sometimes|nullable|string|max:255',
            'shipping_address_line2' => 'sometimes|nullable|string|max:255',
            'contract_status' => ['required', new Enum(CustomerContractStatus::class)],
            'remarks' => 'sometimes|nullable',
        ];
    }
}
