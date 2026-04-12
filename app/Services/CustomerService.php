<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerService {
    public function store(array $data): Customer
    {
        $customer = DB::transaction(function () use ($data) {
            $customer = Customer::create($data);
            $customer->customer_code = 'C' . str_pad((string) $customer->id, 8, '0', STR_PAD_LEFT);
            $customer->save();
            return $customer;
        });

        return $customer;
    }
}
