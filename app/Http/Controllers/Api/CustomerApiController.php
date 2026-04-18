<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $keywords = trim((string)$request->query('keywords'));
        $customers = Customer::query()->when($keywords, function ($query) use ($keywords) {
            $query->whereAny([
                'name',
                'email',
                'phone_number',
                'customer_code'
                ], 'like', '%'. $keywords . '%');
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return response()->json([
            'data' => $customers->items(),

        ]);
    }
}
