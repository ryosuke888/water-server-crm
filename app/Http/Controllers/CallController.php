<?php

namespace App\Http\Controllers;

use App\Http\Requests\Call\StoreCallRequest;
use App\Http\Requests\Call\UpdateCallRequest;
use App\Models\CallHistory;
use App\Models\Customer;
use App\Queries\CallHistoryQuery;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function index(Customer $customer)
    {
        $this->authorize('viewAny', CallHistory::class);

        $callHistories = CallHistoryQuery::listByCustomer($customer)
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('customers.calls.index', compact('customer', 'callHistories'));
    }

    public function show(Customer $customer, CallHistory $callHistory)
    {
        $callHistory = $customer->callHistories()
            ->with('user')
            ->findOrFail($callHistory->id);

        $this->authorize('view', $callHistory);

        return view('customers.calls.show', compact('customer', 'callHistory'));
    }

    public function edit(Customer $customer, CallHistory $callHistory)
    {
        $this->authorize('update', $callHistory);
        $customer->load('orders.product');
        return view('customers.calls.edit', compact('customer', 'callHistory'));
    }

    public function create(Customer $customer)
    {
        $this->authorize('create', CallHistory::class);

        $customer->load('orders.product');
        return view('customers.calls.create', compact('customer'));
    }

    public function update(UpdateCallRequest $request, Customer $customer, CallHistory $callHistory)
    {
        $this->authorize('update', $callHistory);

        try {
            $validated = $request->validated();
            $callHistory->update($validated);
            return redirect()->route('customers.calls.show', compact('customer', 'callHistory'))->with('success', 'コール履歴更新に成功しました');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'コール履歴更新に失敗しました');
        }
    }

    public function store(StoreCallRequest $request, Customer $customer)
    {
        $this->authorize('create', CallHistory::class);
        try {
            $validated = $request->validated();
            $validated['user_id'] = auth()->id();
            $validated['customer_id'] = $customer->id;

            CallHistory::create($validated);
            return redirect()->route('customers.calls.index', $customer)->with('success', 'コール履歴登録に成功しました');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'コール履歴登録に失敗しました');
        }
    }
}
