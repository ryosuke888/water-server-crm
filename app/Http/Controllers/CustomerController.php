<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Queries\CallHistoryQuery;
use App\Queries\CustomerQuery;
use App\Services\CustomerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;




class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Customer::class);

        $keyword = trim((string) $request->query('keyword'));
        $customers = CustomerQuery::search($keyword)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $this->authorize('create', Customer::class);
        return view('customers.create');
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        $orders = CustomerQuery::recentByCustomer($customer)
            ->get();

        $callHistories = CallHistoryQuery::listByCustomer($customer)
            ->get();

        return view('customers.show', compact('customer', 'orders', 'callHistories'));
    }

    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            $customer->update($request->validated());
            return redirect()->route('customers.show', compact('customer'))->with('success', '顧客情報更新に成功しました。');
        } catch(Exception $e) {
            // ログ出力
            Log::error('顧客情報更新失敗', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'data' => $request->only(['contract_status',]),
                'url' => $request->url(),
                'method' => $request->method(),
            ]);
            return back()->withInput()->with('error', '顧客情報更新に失敗しました。');
        }

    }

    public function store(StoreCustomerRequest $request, CustomerService $customerService)
    {
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
                'data' => $request->only(['contract_status',]),
                'url' => $request->url(),
                'method' => $request->method(),
            ]);
            return back()->withInput()->with('error', '顧客登録に失敗しました。');
        }
    }
}
