<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
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

    public function store(StoreCustomerRequest $request, CustomerService $customerService) {
        $validated = $request->validated();
        $customer = $customerService->store($validated);
        return redirect()->route('customers.show', $customer);
    }
}
