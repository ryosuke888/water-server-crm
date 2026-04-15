<?php

namespace App\Http\Requests\Call;

use App\Enums\CallChannel;
use App\Enums\CallResult;
use App\Enums\CallType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],

            'call_type' => ['required', new Enum(CallType::class)],
            'call_result' => ['required', new Enum(CallResult::class)],
            'channel' => ['required', new Enum(CallChannel::class)],

            'call_summary' => ['required', 'string', 'max:1000'],

            'needs_follow_up' => ['required', 'boolean'],
            'follow_up_date' => ['required_if:needs_follow_up,true', 'date'],

            'called_at' => ['required', 'date'],
        ];
    }
}
