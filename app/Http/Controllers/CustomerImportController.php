<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCustomerCsvRequest;
use App\Services\CustomerImportService;
use Illuminate\Validation\ValidationException;

class CustomerImportController extends Controller
{
    public function create()
    {
        $this->authorize('import-customers');
        return view('customers.import.create');
    }

    public function store(ImportCustomerCsvRequest $request, CustomerImportService $customerImportService)
    {
        $this->authorize('import-customers');

        try {
            $file = $request->file('csv_file');
            $count = $customerImportService->import($file);
            return redirect()->route('customers.index')->with('success', $count . '件のCSVデータを取り込みました');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'csvの取り込みに失敗しました。');
        }
    }
}
