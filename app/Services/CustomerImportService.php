<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use SplFileObject;

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
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
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
            'contract_status' => 'required|string|max:50',
            'remarks' => 'nullable|string',
        ];

        $sequence = 1;

        foreach ($csvFile as $i => $row){
            if ($i === 0) {
                $header = $row;
                continue;
            }

            if(empty(array_filter($row, fn ($value) => $value!== null && $value!== ''))) {
                continue;
            }

            if(count($header) !== count($row)) {
                throw new \RuntimeException(($i + 1) . '行目の列数がヘッダーと一致しません。');
            }

            $record = array_combine($header, $row);

            // csvデータのvalidationチェック
            $validator = Validator::make($record, $validationRules)->stopOnFirstFailure();

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
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
}
