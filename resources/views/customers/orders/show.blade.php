<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-6 mt-5">

            <!-- ヘッダー -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                                @if ($customer->contract_status === "解約済")
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                    {{ $customer->contract_status }}
                                </span>
                                @else
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mt-1">顧客ID: {{ $customer->customer_code }}</p>
                                <p class="text-sm text-gray-500 mt-1">ステータス: {{ $customer->contract_status }}</p>
                                <p class="text-sm text-gray-500">登録日: {{ $customer->created_at->format('Y/m/d'); }}</p>
                                <p class="text-sm text-gray-500">更新日: {{ $customer->updated_at->format('Y/m/d'); }}</p>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="history.back()"
                    class="px-5 py-3 text-sm border rounded-xl bg-blue hover:bg-blue-50">
                    戻る
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500">電話番号</p>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $customer->phone_number }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500">メールアドレス</p>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $customer->email }}</p>
                    </div>
                </div>
            </div>

            <!-- 基本情報 + 配送先 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">注文情報</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">注文番号</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->order_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">プラン名</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->plan->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">商品名</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->product->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">受注種別</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->order_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">数量</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->quantity }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">小計</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->unit_price }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">合計</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->subtotal_amount }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">ステータス</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->order_status }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">運送会社</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->shipping_company }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">注文日</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->order_date }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">配送日</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->scheduled_shipping_date}}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">お届け日</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->scheduled_delivery_date}}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">備考</p>
                            <p class="text-sm font-medium text-gray-800">{{ $order->remarks}}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">配送先情報</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">氏名</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->shipping_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">郵便番号</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->shipping_postal_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">住所</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->shipping_prefecture . $customer->shipping_city . $customer->shipping_address_line1 . $customer->shipping_address_line2 }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
