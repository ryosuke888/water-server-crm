<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\Customer\CustomerRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends CustomerRequest
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
        $customer = $this->route('customer');

        return array_merge($this->basicRules(), [
            'phone_number' => ['required', 'string', 'regex:/^\d{10,11}$/', Rule::unique('customers', 'phone_number')->ignore($customer->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
        ]);
    }
}
