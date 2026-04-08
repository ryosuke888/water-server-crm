<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderHistory;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index(Customer $customer)
    {
        $this->authorize('viewAny', OrderHistory::class);

        $orderHistories = $customer->orderHistories()
        ->with('user')
        ->latest('acted_at')
        ->paginate(10)
        ->withQueryString();

        return view('customers.order-histories.index', compact('customer', 'orderHistories'));
    }

    public function show(Customer $customer, OrderHistory $orderHistory)
    {
        $this->authorize('view', $orderHistory);

        $orderHistory = $customer->orderHistories()
        ->with('user')
        ->findOrFail($orderHistory->id);

        return view('customers.order-histories.show', compact('customer', 'orderHistory'));
    }
}
