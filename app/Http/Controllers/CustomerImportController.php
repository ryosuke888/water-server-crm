<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCustomerCsvRequest;
use App\Services\CustomerImportService;

class CustomerImportController extends Controller
{
    public function create() {
        return view('customers.import.create');
    }

    public function store(ImportCustomerCsvRequest $request, CustomerImportService $customerImportService)
    {
        $file = $request->file('csv_file');
        $count = $customerImportService->import($file);

        return redirect()->route('customers.index')->with('success', $count . '件のCSVデータを取り込みました');
    }
}
