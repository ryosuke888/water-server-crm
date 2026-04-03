<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-6 mt-5">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


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

            <!-- 注文情報 + 配送先 -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-5">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">受注情報</h2>
                            <p class="text-sm text-gray-500 mt-1">受注内容を確認できます</p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <div>
                                @can('update', $order)
                                    <a href="{{ route('customers.orders.edit', [$customer, $order]) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                                        編集
                                    </a>
                                @endcan
                            </div>
                            @can('delete', $order)
                                <div x-data="{ openCancelModal: false }">
                                    <div class="flex items-center gap-3">

                                        @if ($order->order_status !== 'キャンセル')
                                            <button
                                                type="button"
                                                @click="openCancelModal = true"
                                                class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                                            >
                                                キャンセル
                                            </button>
                                        @endif
                                    </div>

                                    <div
                                        x-show="openCancelModal"
                                        x-cloak
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
                                    >
                                        <div
                                            @click.away="openCancelModal = false"
                                            x-show="openCancelModal"
                                            x-transition
                                            class="w-full max-w-lg rounded-2xl bg-white shadow-xl"
                                        >
                                            <div class="border-b px-6 py-4">
                                                <h2 class="text-lg font-semibold text-gray-900">受注キャンセル</h2>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    本当にこの受注をキャンセルしますか？理由も記録されます。
                                                </p>
                                            </div>

                                            <form
                                                action="{{ route('customers.orders.cancel', [$customer, $order]) }}"
                                                method="POST"
                                                class="space-y-5 px-6 py-5"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">
                                                        受注番号
                                                    </label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $order->order_code }}</p>
                                                </div>

                                                <div>
                                                    <label for="cancel_reason" class="block text-sm font-medium text-gray-700">
                                                        キャンセル理由 <span class="text-red-500">*</span>
                                                    </label>
                                                    <textarea
                                                        name="cancel_reason"
                                                        id="cancel_reason"
                                                        rows="4"
                                                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500"
                                                        placeholder="例：顧客都合のためキャンセル"
                                                    >{{ old('cancel_reason') }}</textarea>

                                                    @error('cancel_reason')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                                                    キャンセル後は受注ステータスが「キャンセル」に変更され、対応履歴に記録されます。
                                                </div>

                                                <div class="flex justify-end gap-3 pt-2">
                                                    <button
                                                        type="button"
                                                        @click="openCancelModal = false"
                                                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                                    >
                                                        閉じる
                                                    </button>

                                                    <button
                                                        type="submit"
                                                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                                                    >
                                                        キャンセルを確定
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                </div>
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

            <!-- アクションボタン -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>

                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customers.orders.index', $customer) }}"
                       class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        一覧へ戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
