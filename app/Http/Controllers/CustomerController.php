<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create() {
        return view('customers.create');
    }

    public function show($id) {
        $customer = Customer::find($id);
        return view('customers.show', compact('customer'));
    }

    public function edit($id) {
        $customer = Customer::find($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, $id) {
        $customer = Customer::findOrFail($id);
        $customer->update($request->validated());
        return view('customers.show', compact('customer'));
    }

    public function store(StoreCustomerRequest $request) {
        $validated = $request->validated();
        $latestCustomer = Customer::latest('id')->first();
        $nextId = $latestCustomer ? $latestCustomer->id + 1 : 1;
        $validated['customer_code'] = 'C' . str_pad((string) $nextId, 8, '0', STR_PAD_LEFT);
        $customer = Customer::create($validated);
        return redirect()->route('customers.show', $customer);
    }
}
