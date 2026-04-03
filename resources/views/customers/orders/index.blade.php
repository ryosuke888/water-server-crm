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

            <!-- 受注検索 -->

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <form action="{{ route('customers.orders.index' ,$customer) }}" method="GET" class="flex flex-col gap-4 md:flex-row md:items-end">
                    <div class="flex-1">
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">受注検索</label>
                        <input
                            type="text"
                            name="keyword"
                            id="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="受注IDで検索"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
                        >
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="submit"
                            class="px-5 py-3 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700"
                        >
                            検索
                        </button>

                        <a
                            href="{{ route('customers.orders.index' ,$customer) }}"
                            class="px-5 py-3 rounded-xl border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            リセット
                        </a>
                    </div>
                </form>
            </div>

            <!-- 件数表示 -->
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-gray-500">
                    検索結果：
                    <span class="font-semibold text-gray-900">{{ $orders->total() }}</span>
                    件
                </p>

                <p class="text-sm text-gray-500">
                    {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} 件を表示
                </p>
            </div>

            <!-- 注文情報 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">受注情報</h2>
                        <p class="text-sm text-gray-500 mt-1">受注内容を確認できます</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @can('create', App\Models\Order::class)
                            <a href="{{ route('customers.orders.create', $customer) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                                受注登録
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- 受注一覧 -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-3 pr-4">注文番号</th>
                                <th class="py-3 pr-4">商品名</th>
                                <th class="py-3 pr-4">数量</th>
                                <th class="py-3 pr-4">ステータス</th>
                                <th class="py-3 pr-4">注文日</th>
                                <th class="py-3">次回配送日</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 text-gray-800">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="py-3 pr-4">{{ $order->order_code}}</td>
                                    <td class="py-3 pr-4">{{ $order->product->name}}</td>
                                    <td class="py-3 pr-4">{{ $order->quantity}}</td>
                                    <td class="py-3 pr-4">
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                            {{ $order->order_status}}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4">{{ $order->order_date}}</td>
                                    <td class="py-3 pr-4">{{ $order->scheduled_shipping_date}}</td>
                                    <td class="px-3">
                                        <div>
                                            @can('view', $order)
                                                <form action="{{ route('customers.orders.show', [$customer, $order]) }}" method="get">
                                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">詳細</button>
                                                </form>
                                            @endcan
                                        </div>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ページネーション -->
                @if ($orders->hasPages())
                    <div class="bg-white shadow-sm border border-gray-100 px-4 py-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                            <div class="text-sm text-gray-500">
                                全 {{ $orders->total() }} 件中
                                {{ $orders->firstItem() ?? 0 }}〜{{ $orders->lastItem() ?? 0 }} 件を表示
                            </div>

                            <div class="flex items-center justify-center md:justify-end">
                                {{ $orders->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- アクションボタン -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>

                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customers.show', $customer) }}"
                       class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
