<?php

namespace App\Services;

use App\Enums\CustomerContractStatus;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use SplFileObject;

use function Symfony\Component\String\s;

class CustomerImportService {
    public function import($file): int
    {
        $filePath = $file->getRealPath();
        $csvFile = new SplFileObject($filePath);

        $csvFile->setFlags(
                SplFileObject::READ_CSV |         // CSVとして読み込む
                SplFileObject::SKIP_EMPTY |       // 空行をスキップ
                SplFileObject::DROP_NEW_LINE |    // 行末の改行を削除
                SplFileObject::READ_AHEAD);       // 先読み

        $lastCustomer = Customer::orderByDesc('id')->first();
        $lastId = $lastCustomer ? $lastCustomer->id : 0;
        $now = now();
        $data = [];

        $validationRules = [
            'name' => 'required|string|max:100',
            'phone_number' => ['required', 'string', 'max:20', 'unique:customers,phone_number'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email'],
            'postal_code' => 'nullable|string|max:8',
            'prefecture' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'shipping_name' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:8',
            'shipping_prefecture' => 'nullable|string|max:20',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_address_line1' => 'nullable|string|max:255',
            'shipping_address_line2' => 'nullable|string|max:255',
            'contract_status' => ['required', new Enum(CustomerContractStatus::class)],
            'remarks' => 'nullable|string',
        ];

        $sequence = 1;

        $usedPhoneNumbers = [];
        $usedEmails = [];

        foreach ($csvFile as $i => $row){
            if ($i === 0) {
                $header = array_map(function ($value) {
                    return $this->normalizeCsvValue($value);
                }, $row);
                // BOM消去
                $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
                continue;
            }

             $row = array_map(function ($value) {
                return $this->normalizeCsvValue($value);
            }, $row);

            if(empty(array_filter($row, fn ($value) => $value!== null))) {
                continue;
            }

            if(count($header) !== count($row)) {
                throw new \RuntimeException(($i + 1) . '行目の列数がヘッダーと一致しません。');
            }

            $record = array_combine($header, $row);

            // 電話番号の正規化
            $record['phone_number'] = preg_replace('/[^0-9]/', '', (string) $record['phone_number']);

            // csvデータのvalidationチェック
            $validator = Validator::make($record, $validationRules, [
                'phone_number.unique' => '電話番号が既に登録されています。',
                'email.unique' => 'メールアドレスが既に登録されています。',
            ])->stopOnFirstFailure();

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // csv電話番号重複チェック
            if (in_array($record['phone_number'], $usedPhoneNumbers, true)) {
                throw ValidationException::withMessages([
                    'phone_number' => ($i + 1) . ['行目の電話番号がCSV内で重複しています。'],
                ]);
            }

            // csvメールアドレス重複チェック
            if (!empty($record['email']) && in_array($record['email'], $usedEmails, true)) {
                throw ValidationException::withMessages([
                    'email' => ($i + 1) . ['行目のメールアドレスがCSV内で重複しています。'],
                ]);
            }

            $usedPhoneNumbers[] = $record['phone_number'];
            if (!empty($record['email'])) {
                $usedEmails[] = $record['email'];
            }

            $record['created_at'] = $now;
            $record['updated_at'] = $now;
            $record['customer_code'] = 'C' . str_pad((string)($lastId + $sequence), 8, '0', STR_PAD_LEFT);
            $sequence++;

            $data[] = $record;
        }

        DB::transaction(function () use ($data) {
            Customer::insert($data);
        });

        return count($data);
    }

    private function normalizeCsvValue($value): ?string
    {
        $value = (string)$value;
        $value = str_replace('\xE3\x80\x80', ' ', $value);  // 全角スペースを半角へ変換
        $value = trim($value); // 前後の空白除去

        return $value === '' ? null : $value;
    }
}
