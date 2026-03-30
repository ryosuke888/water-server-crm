<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;




class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create() {
        return view('customers.create');
    }

    public function show(Customer $customer) {
        $customer = $customer::with('orders', 'callHistories')->findOrFail($customer->id);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer) {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer) {
        try {
            $customer->update($request->validated());
            return view('customers.show', compact('customer'))->with('success', '顧客情報更新に成功しました。');
        } catch(Exception $e) {
            // ログ出力
            Log::error('顧客情報更新失敗', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'customer_id' => $customer['id'],
                'data' => $request->only(['contact_status',]),
                'url' => $request->url(),
                'method' => $request->method(),
            ]);
            return back()->withInput()->with('error', '顧客情報更新に失敗しました。');
        }

    }

    public function store(StoreCustomerRequest $request, CustomerService $customerService) {
        try {
            $validated = $request->validated();
            $customer = $customerService->store($validated);
            return redirect()->route('customers.show', $customer)->with('success', '顧客登録に成功しました。');
        } catch(Exception $e) {
            // ログ出力
            Log::error("顧客登録失敗", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'data' => $request->only(['contact_status',]),
                'url' => $request->url(),
                'method' => $request->method(),
            ]);
            return back()->withInput()->with('error', '顧客登録に失敗しました。');
        }
    }
}
