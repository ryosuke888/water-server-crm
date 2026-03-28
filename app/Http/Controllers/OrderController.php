<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanProductPrice;
use App\Models\Product;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function create(Customer $customer) {
        $plans = Plan::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $planProductPrices = PlanProductPrice::with('plans', 'products')->get();
        return view('customers.orders.create', compact('customer', 'plans', 'products', 'planProductPrices'));
    }

    public function store(StoreOrderRequest $request, OrderService $orderService, Customer $customer) {
        try {
            $valideted = $request->validated();
            $orderService->store($valideted);
            return redirect()->route('customers.orders.index', compact('customer'))->with('success', '顧客登録に成功しました。');
        } catch (Exception $e) {
            // ログ出力
            Log::error('受注登録失敗', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'data' => $request->only(['order_status',]),
                'url' => $request->url(),
                'method' => $request->method(),
            ]);
            return back()->withInput()->with('error', '受注登録に失敗しました。');
        }

    }
}
