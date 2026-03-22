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

    public function update($id) {
        var_dump($_REQUEST);
        $name = $_POST['name'] ?? null;
        $phone_number = $_POST['phone_number'] ?? null;
        $email = $_POST['email'] ?? '';
        $postal_code = $_POST['postal_code'] ?? '';
        $prefecture = $_POST['prefecture'] ?? '';
        $city = $_POST['city'] ?? '';
        $address_line1 = $_POST['address_line1'] ?? '';
        $address_line2 = $_POST['address_line2'] ?? '';
        $contract_status = $_POST['contract_status'] ?? '';

        // 注文情報
        $shipping_name = $_POST['shipping_name'] ?? '';
        $shipping_postal_code = $_POST['shipping_postal_code'] ?? '';
        $shipping_prefecture = $_POST['shipping_prefecture'] ?? '';
        $shipping_city = $_POST['shipping_city'] ?? '';
        $shipping_address_line1 = $_POST['shipping_address_line1'] ?? '';
        $shipping_address_line2 = $_POST['shipping_address_line2'] ?? '';



    }
}
