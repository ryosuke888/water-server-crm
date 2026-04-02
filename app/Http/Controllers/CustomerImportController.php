<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCustomerCsvRequest;
use App\Services\CustomerImportService;
use Exception;
use Illuminate\Validation\ValidationException;

class CustomerImportController extends Controller
{
    public function create() {
        return view('customers.import.create');
    }

    public function store(ImportCustomerCsvRequest $request, CustomerImportService $customerImportService)
    {
        try {
            $file = $request->file('csv_file');
            $count = $customerImportService->import($file);
            return redirect()->route('customers.index')->with('success', $count . '件のCSVデータを取り込みました');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'csvの取り込みに失敗しました。');
        }
    }
}
