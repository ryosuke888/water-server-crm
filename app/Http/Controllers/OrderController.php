<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function customerIndex(Customer $customer) {

        $orders = Order::where('customer_id', $customer->id)->get();
        foreach ($orders as $order) {
            $product = Product::where('id', $order->product_id)->firstOrFail();
            $order->product_name = $product->name;
        }

        return view('orders.customer_index', compact('customer'))->with(compact('orders'));
    }

    public function show(Order $order) {

    }
}
