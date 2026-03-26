<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function customerIndex(Customer $customer) {
        $customer->load('orders.product');
        return view('orders.customer_index', compact('customer'));
    }

    public function show(Order $order) {

    }
}
