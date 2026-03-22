<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function show($id) {
        $customer = Customer::find($id);
        return view('customers.show', compact('customer'));
    }

    public function edit($id) {
        $customer = Customer::find($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id) {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required',
            'phone_number' => 'sometimes|required',
            'email' => 'sometimes|nullable|email',
            'postal_code' => 'sometimes|nullable|string|max:8',
            'prefecture' => 'sometimes|nullable|string|max:20',
            'city' => 'sometimes|nullable|string|max:100',
            'address_line1' => 'sometimes|nullable|string|max:255',
            'address_line2' => 'sometimes|nullable|string|max:255',
            'shipping_name' => 'sometimes|nullable|string|max:100',
            'shipping_postal_code' => 'sometimes|nullable|string|max:8',
            'shipping_prefecture' => 'sometimes|nullable|string|max:20',
            'shipping_city' => 'sometimes|nullable|string|max:100',
            'shipping_address_line1' => 'sometimes|nullable|string|max:255',
            'shipping_address_line2' => 'sometimes|nullable|string|max:255',
            'contract_status' => 'sometimes|required',
            'remarks' => 'sometimes|nullable',
        ]);
        $customer->update($validated);
        return view('customers.show', compact('customer'));
    }
}
