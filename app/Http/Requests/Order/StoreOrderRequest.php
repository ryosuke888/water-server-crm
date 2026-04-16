<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\Order\OrderRequest;
use App\Models\Order;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Gate;

class StoreOrderRequest extends OrderRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Order::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge($this->basicRules(), [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
        ]);
    }
}
