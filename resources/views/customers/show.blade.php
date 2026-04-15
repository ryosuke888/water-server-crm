<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-6 mt-5">

            <x-flash-message type="success" :message="session('success')" />
            <x-flash-message type="error" :message="session('error')" />

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
                            <x-link-button color="blue" :href="route('customers.edit', $customer)">編集</x-link-button>
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
                            <x-link-button color="emerald" :href="route('customers.orders.create', $customer)">受注登録</x-link-button>
                        @endcan
                        <x-link-button color="white" :href="route('customers.orders.index', $customer)">一覧を見る</x-link-button>
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
                                    <td class="px-3 py-2">
                                        @can('view', $order)
                                            <x-link-button color="blue" :href="route('customers.orders.show', [$customer, $order])">詳細</x-link-button>
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
                            <x-link-button color="emerald" :href="route('customers.calls.create', $customer)">コール登録</x-link-button>
                        @endcan

                        <x-link-button color="white" :href="route('customers.calls.index', $customer)">一覧を見る</x-link-button>
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
                                        <x-link-button color="white" :href="route('customers.calls.show', [$customer, $callHistory])">詳細</x-link-button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100 text-right">
                    <x-link-button color="blue" :href="route('customers.calls.index', $customer)">すべてのコール履歴を見る</x-link-button>
                </div>
            </div>

            <!-- アクションボタン -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>

                </div>
                <div class="flex flex-wrap gap-3">
                    <x-link-button color="white" :href="route('customers.order-histories.index', $customer)">受注履歴</x-link-button>
                    <x-link-button color="white" :href="route('customers.index', $customer)">一覧へ戻る</x-link-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
