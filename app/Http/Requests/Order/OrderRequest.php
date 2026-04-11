<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

abstract class OrderRequest extends FormRequest
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
    protected function basicRules(): array
    {
        return [
            'plan_id' => ['nullable', 'integer', 'exists:plans,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'order_type' => ['required', new Enum(OrderType::class)],
            'scheduled_delivery_date' => ['required', 'date', 'after_or_equal:' . now()->addDays(3)->toDateString()],
        ];
    }

    public function messages(): array
    {
        return [
            'order_type.enum' => '受注種別の値が不正です。',
            'order_status.enum' => '受注ステータスの値が不正です。',
            'scheduled_delivery_date.after_or_equal' => '配送予定日は3日後以降を指定してください。',
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_id' => '顧客ID',
            'plan_id' => 'プラン',
            'product_id' => '商品',
            'quantity' => '数量',
            'order_type' => '受注種別',
            'order_status' => '受注ステータス',
            'scheduled_delivery_date' => '配送日',
        ];
    }
}
