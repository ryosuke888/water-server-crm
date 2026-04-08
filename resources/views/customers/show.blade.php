<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-6 mt-5">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- ヘッダー -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                                @if ($customer->contract_status->value === App\Enums\CustomerContractStatus::CANCELED)
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                    {{ $customer->contract_status->label() }}
                                </span>
                                @else
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mt-1">顧客ID: {{ $customer->customer_code }}</p>
                                <p class="text-sm text-gray-500 mt-1">ステータス: {{ $customer->contract_status->label() }}</p>
                                <p class="text-sm text-gray-500">登録日: {{ $customer->created_at->format('Y/m/d'); }}</p>
                                <p class="text-sm text-gray-500">更新日: {{ $customer->updated_at->format('Y/m/d'); }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @can('update', $customer)
                            <a href="{{ route('customers.edit', $customer) }}"
                            class="inline-flex items-center px-5 py-3 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                                編集
                            </a>
                        @endcan
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

            <!-- 基本情報 + 配送先 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">基本情報</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">氏名</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">電話番号</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->phone_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">メールアドレス</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">郵便番号</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->postal_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">契約先住所</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->prefecture . $customer->city . $customer->address_line1 . $customer->address_line2 }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">ステータス</p>
                            <p class="text-sm font-medium text-gray-800">{{ $customer->contract_status->label() }}</p>
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

            <!-- 注文情報 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">受注情報</h2>
                        <p class="text-sm text-gray-500 mt-1">最新の受注内容を確認できます</p>
                    </div>

                    <div class="flex flex-wrap gap-3">

                        @can('create', App\Models\Order::class)
                            <a href="{{ route('customers.orders.create', $customer) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                                受注登録
                            </a>
                        @endcan

                        <a href="{{ route('customers.orders.index', $customer) }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50">
                            一覧を見る
                        </a>
                    </div>
                </div>

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
                                            {{ $order->order_status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4">{{ $order->order_date}}</td>
                                    <td class="py-3 pr-4">{{ $order->scheduled_delivery_date}}</td>
                                    <td class="px-3">
                                        @can('view', $order)
                                              <div>
                                            <form action="{{ route('customers.orders.show', [$customer, $order]) }}" method="get">
                                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">詳細</button>
                                            </form>
                                        </div>
                                        @endcan
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- コール履歴 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">コール履歴</h2>
                        <p class="text-sm text-gray-500 mt-1">最新の対応内容を確認できます</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @can('create', App\Models\CallHistory::class)
                            <a href="{{ route('customers.calls.create', $customer) }}"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                                コール登録
                            </a>
                        @endcan

                        <a href="{{ route('customers.calls.index', $customer) }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50">
                            一覧を見る
                        </a>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($callHistories as $callHistory)
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 hover:bg-gray-100/60 transition">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div class="space-y-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                            {{ $callHistory->call_type->label() }}
                                        </span>

                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">
                                            {{ $callHistory->call_result->label() }}
                                        </span>

                                        <span class="text-xs text-gray-500">
                                            {{ $callHistory->called_at }}
                                        </span>
                                    </div>

                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $callHistory->call_summary }}
                                    </p>

                                    <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-500">
                                        <p>対応者：{{ $callHistory->user->name ?? '担当者名' }}</p>
                                        <p>チャネル：{{ $callHistory->channel->label() ?? '電話' }}</p>
                                        <p>次回対応日：{{ $callHistory->follow_up_date ?? 'なし' }}</p>
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    @can('view', $callHistory)
                                        <a href="{{ route('customers.calls.show', [$customer, $callHistory]) }}"
                                        class="inline-flex items-center px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50">
                                            詳細
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100 text-right">
                    <a href="{{ route('customers.calls.index', $customer) }}"
                    class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                        すべてのコール履歴を見る
                    </a>
                </div>
            </div>

            <!-- アクションボタン -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>

                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customers.index', $customer) }}"
                       class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        一覧へ戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
