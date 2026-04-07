<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'plan_id' => ['nullable', 'integer', 'exists:plans,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],

            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'integer', 'min:0'],
            'subtotal_amount' => ['nullable', 'integer', 'min:0'],

            'order_status' => ['required', new Enum(OrderStatus::class)],
            'order_type' => ['required', new Enum(OrderType::class)],
            'scheduled_delivery_date' => ['required', 'date', 'after_or_equal:' . now()->addDays(3)->toDateString()],
        ];
    }
}
