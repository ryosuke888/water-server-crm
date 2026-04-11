<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\Customer\CustomerRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreCustomerRequest extends CustomerRequest
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
        return array_merge($this->basicRules(), [
            'phone_number' => ['required', 'string', 'regex:/^\d{10,11}$/', 'unique:customers,phone_number'], // 10または11桁の数字のみ
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email'],
        ]);
    }
}
