<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCustomerCsvRequest;
use App\Models\Customer;
use Symfony\Component\HttpFoundation\Request;

use SplFileObject;

class CustomerImportController extends Controller
{
    public function create() {
        return view('customers.import.create');
    }

    public function store(Request $request)
    {
        $file = $request->file('csv_file');

        $fileName = $file->getRealPath();
        $csvFile = new SplFileObject($fileName);

        $csvFile->setFlags(
                SplFileObject::READ_CSV |         // CSVとして読み込む
                SplFileObject::SKIP_EMPTY |       // 空行をスキップ
                SplFileObject::DROP_NEW_LINE |    // 行末の改行を削除
                SplFileObject::READ_AHEAD);       // 先読み

        $now = now();
        $data = [];

        foreach ($csvFile as $i => $row){
            if ($i === 0) {
                $header = $row;
                continue;
            }
            $record = array_combine($header, $row);
            $record['created_at'] = $now;
            $record['updated_at'] = $now;
            $data[] = $record;
        }

        Customer::insert($data);
        return redirect()->route('customers.index')->with('success', 'CSVデータの取り込みに成功しまいた');
    }
}
