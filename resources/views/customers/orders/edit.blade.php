<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-5xl px-4">

            <!-- タイトル -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">受注更新</h1>
                <p class="text-sm text-gray-500 mt-1">受注情報を更新します</p>
            </div>

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

            @can('update', $order)
                <form action="{{ route('customers.orders.update', [$customer,$order]) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PATCh')

                    <!-- 注文情報 -->
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold">受注</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 px-6 py-6">

                            <div>
                                <label class="block text-sm font-medium mb-1">プラン名</label>
                                <select class="w-full rounded-xl border px-4 py-3 text-sm" name="plan_id" id="plan">
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id', $order->plan->plan_id) === $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">製品</label>
                                <select class="w-full rounded-xl border px-4 py-3 text-sm" name="product_id" id="product">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $order->product->product_id) === $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">受注種別</label>
                                <select class="w-full rounded-xl border px-4 py-3 text-sm" name="order_type">
                                    <option value="{{ App\Enums\OrderType::INITIAL }}" {{ old('order_type', $order->order_type->value) === App\Enums\OrderType::INITIAL ? 'selected' : '' }}>初回</option>
                                    <option value="{{ App\Enums\OrderType::CHANGE }}" {{ old('order_type', $order->order_type->value) === App\Enums\OrderType::CHANGE ? 'selected' : '' }}>変更</option>
                                    <option value="{{ App\Enums\OrderType::REGULAR }}" {{ old('order_type', $order->order_type->value) === App\Enums\OrderType::REGULAR ? 'selected' : '' }}>定期配送</option>
                                </select>
                                @error('order_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">受注ステータス</label>
                                <select class="w-full rounded-xl border px-4 py-3 text-sm" name="order_status">
                                    <option value="{{ App\Enums\OrderStatus::RECEIVED }}" {{ old('order_status', $order->order_status->value) === App\Enums\OrderStatus::RECEIVED ? 'selected' : '' }}>受付済</option>
                                    <option value="{{ App\Enums\OrderStatus::PREPARING }}" {{ old('order_status', $order->order_status->value) === App\Enums\OrderStatus::PREPARING ? 'selected' : '' }}>出荷準備中</option>
                                    <option value="{{ App\Enums\OrderStatus::COMPLETED }}" {{ old('order_status', $order->order_status->value) === App\Enums\OrderStatus::COMPLETED ? 'selected' : '' }}>出荷済</option>
                                    <option value="{{ App\Enums\OrderStatus::CANCELED }}" {{ old('order_status', $order->order_status->value) === App\Enums\OrderStatus::CANCELED ? 'selected' : '' }}>キャンセル</option>
                                </select>
                                @error('order_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">数量</label>
                                <input type="number" value="{{ old('quantity', $order->quantity) }}" name="quantity"
                                class="w-full rounded-xl border px-4 py-3 text-sm" id="quantity" min="1">
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">小計</label>
                                <input type="text" value="{{ old('unit_price', $order->unit_price) }}" name="unit_price"
                                class="w-full rounded-xl border px-4 py-3 text-sm" id="unit_price" readonly>
                                @error('unit_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium mb-1">合計</label>
                                <input type="text" value="{{ old('subtotal_amount', $order->subtotal_amount) }}" name="subtotal_amount"
                                class="w-full rounded-xl border px-4 py-3 text-sm" id="subtotal_amount" readonly>
                                @error('subtotal_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">お届け日</label>
                                <input type="date" value="{{ old('scheduled_delivery_date', $order->scheduled_delivery_date) }}" name="scheduled_delivery_date"
                                class="w-full rounded-xl border px-4 py-3 text-sm" min="{{ now()->addDays(3)->toDateString() }}">
                                @error('scheduled_delivery_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ボタン -->
                    <div class="flex justify-between items-center">
                        <button type="button" onclick="history.back()"
                        class="px-5 py-3 text-sm border rounded-xl bg-white hover:bg-gray-50">
                        戻る
                        </button>

                        <button type="submit"
                        class="px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">
                        更新
                        </button>
                    </div>

                </form>
            @endcan
        </div>
    </div>
    <script>
        const plan = document.getElementById('plan');
        const product = document.getElementById('product');
        const quantity = document.getElementById('quantity');
        let unitPrice = document.getElementById('unit_price');
        let subtotalAmount = document.getElementById('subtotal_amount');

        const prices = @json($planProductPrices->mapWithKeys(function($item) {
            return [$item->plan_id . '_' . $item->product_id => $item->price];
        }));

        function updatePrice() {
            const planId = plan.value;
            const productId = product.value;
            const qty = Number(quantity.value || 0);

            const key = `${planId}_${productId}`;

            if (!planId || !productId) {
                unitPrice.value = '';
                return
            }

            const price = prices[key] ?? 0;
            unitPrice.value = price;

            if (!planId || !productId || !qty) {
                subtotalAmount.value = '';
                return
            }

            subtotalAmount.value = price * qty;
        }

        plan.addEventListener('change', updatePrice);
        product.addEventListener('change', updatePrice);
        quantity.addEventListener('change', updatePrice);

    </script>
</x-app-layout>
