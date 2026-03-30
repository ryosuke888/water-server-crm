<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCallRequest;
use App\Models\CallHistory;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function index(Customer $customer)
    {
        $customer->load('callHistories');
        return view('customers.calls.index', compact('customer'));
    }

    public function create(Customer $customer)
    {
        $customer->load('orders.product');
        return view('customers.calls.create', compact('customer'));
    }

    public function store(StoreCallRequest $request, Customer $customer)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = auth()->id();
            CallHistory::create($validated);
            return redirect()->route('customers.calls.index', $customer)->with('success', 'コール履歴登録に成功しました');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'コール履歴登録に失敗しました');
        }
    }
}
