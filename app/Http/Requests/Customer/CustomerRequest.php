<?php

namespace App\Http\Requests\Customer;

use App\Enums\CustomerContractStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

abstract class CustomerRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'phone_number' => ['required', 'string', 'regex:/^\d{10,11}$/'], // 10または11桁の数字のみ
            'email' => ['nullable', 'email', 'max:255'],
            'postal_code' => 'nullable|string|regex:/^\d{7}$/', // 7桁の数字のみ
            'prefecture' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'shipping_name' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|regex:/^\d{7}$/',  // 7桁の数字のみ
            'shipping_prefecture' => 'nullable|string|max:20',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_address_line1' => 'nullable|string|max:255',
            'shipping_address_line2' => 'nullable|string|max:255',
            'contract_status' => ['required', new Enum(CustomerContractStatus::class)],
            'remarks' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.regex' => '電話番号は10〜11桁の数字で入力してください。',
            'phone_number.unique' => 'この電話番号はすでに登録されています。',
            'email.unique' => 'このメールアドレスはすでに登録されています。',
            'postal_code.regex' => '郵便番号は7桁の数字で入力してください。',
            'shipping_postal_code.regex' => '配送先郵便番号は7桁の数字で入力してください。',
            'contract_status.enum' => '契約ステータスの値が不正です。',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '顧客名',
            'phone_number' => '電話番号',
            'email' => 'メールアドレス',
            'postal_code' => '郵便番号',
            'prefecture' => '都道府県',
            'city' => '市区町村',
            'address_line1' => '住所（番地）',
            'address_line2' => '建物名・部屋番号',
            'shipping_name' => '配送先氏名',
            'shipping_postal_code' => '配送先郵便番号',
            'shipping_prefecture' => '配送先都道府県',
            'shipping_city' => '配送先市区町村',
            'shipping_address_line1' => '配送先住所（番地）',
            'shipping_address_line2' => '配送先建物名・部屋番号',
            'contract_status' => '契約ステータス',
            'remarks' => '備考',
        ];
    }
}
