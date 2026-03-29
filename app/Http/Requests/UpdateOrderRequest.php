<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'plan_id' => ['nullable', 'integer', 'exists:plans,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],

            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'integer', 'min:0'],
            'subtotal_amount' => ['nullable', 'integer', 'min:0'],

            'order_status' => ['required', 'string', 'in:受付済,出荷準備中,出荷済,キャンセル'],
            'order_type' => ['required', 'string', 'in:初回,変更,定期配送'],
            'scheduled_delivery_date' => ['required', 'date', 'after_or_equal:' . now()->addDays(3)->toDateString()],
        ];
    }
}
