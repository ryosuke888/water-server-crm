<?php

namespace App\Services;

use App\Models\Customer;
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

        $lastId = Customer::max('customer_code');
        $now = now();
        $data = [];

        foreach ($csvFile as $i => $row){
            if ($i === 0) {
                $header = $row;
                continue;
            }

            if(empty(array_filter($row, fn ($value) => $value!== null && $value!== ''))) {
                continue;
            }

            $record = array_combine($header, $row);
            $record['created_at'] = $now;
            $record['updated_at'] = $now;
            $record['customer_code'] = 'C' . str_pad(string($lastId + $i), '8', '0', STR_PAD_LEFT);
            $data[] = $record;
        }

        Customer::insert($data);
        return count($data);
    }
}
