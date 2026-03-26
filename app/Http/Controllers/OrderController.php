<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Customer $customer) {
        $customer->load([
            'orders.product',
            'orders.plan',
            'orders.planProductPrice',
        ]);
        return view('customers.orders.index', compact('customer'));
    }

    public function show(Customer $customer, Order $order) {
        return view('customers.orders.show', compact('customer', 'order'));
    }
}
