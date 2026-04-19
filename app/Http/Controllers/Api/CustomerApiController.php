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
        $keyword = trim((string)$request->query('keyword'));
        $customers = Customer::query()->when($keyword, function ($query) use ($keyword) {
            $query->whereAny([
                'name',
                'email',
                'phone_number',
                'customer_code'
                ], 'like', '%'. $keyword . '%');
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return response()->json([
            'data' => $customers->items(),
            'meta' => [
                'total' => $customers->total(),
                'eachSide' => $customers->onEachSide(1)->links(),
                'firstItem' => $customers->firstItem(),
                'lastItem' => $customers->lastItem(),
            ]
        ]);
    }
}
