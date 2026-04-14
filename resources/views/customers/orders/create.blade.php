<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-5xl px-4">

            <!-- タイトル -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">受注登録</h1>
                <p class="text-sm text-gray-500 mt-1">受注情報を登録します</p>
            </div>

            <x-flash-message type="success" :message="session('success')" />
            <x-flash-message type="error" :message="session('error')" />

            @can('create', App\Models\Order::class)
                <form action="{{ route('customers.orders.store', $customer) }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- 注文情報 -->
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold">受注</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 px-6 py-6">

                            <div>
                                <label class="block text-sm font-medium mb-1">プラン名</label>
                                <select class="w-full rounded-xl border px-4 py-3 text-sm" name="plan_id" id="plan">
                                    <option value="" selected>選択してください</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" >{{ $plan->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('plan_id')" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">製品</label>
                                <select class="w-full rounded-xl border px-4 py-3 text-sm" name="product_id" id="product">
                                    <option value="" selected>選択してください</option>
                                    @foreach ($products as $product)
                                            <option value="{{ $product->id }}" >{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('product_id')" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">受注種別</label>
                                <select class="w-full rounded-xl border px-4 py-3 text-sm" name="order_type">
                                    <option value="初回" {{ old('order_type') }}>初回</option>
                                    <option value="定期配送" {{ old('order_type') }}>定期配送</option>
                                </select>
                                <x-input-error :messages="$errors->get('order_type')" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">数量</label>
                                <input type="number" value="{{ old('quantity') }}" name="quantity"
                                class="w-full rounded-xl border px-4 py-3 text-sm" id="quantity" min="1">
                                <x-input-error :messages="$errors->get('quantity')" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">小計</label>
                                <input type="text" value="{{ old('unit_price') }}" name="unit_price"
                                class="w-full rounded-xl border px-4 py-3 text-sm" id="unit_price" readonly>
                                <x-input-error :messages="$errors->get('unit_price')" />
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium mb-1">合計</label>
                                <input type="text" value="{{ old('subtotal_amount') }}" name="subtotal_amount"
                                class="w-full rounded-xl border px-4 py-3 text-sm" id="subtotal_amount" readonly>
                                <x-input-error :messages="$errors->get('subtotal_amount')" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">お届け日</label>
                                <input type="date" value="{{ old('scheduled_delivery_date') }}" name="scheduled_delivery_date"
                                class="w-full rounded-xl border px-4 py-3 text-sm" min="{{ now()->addDays(3)->toDateString() }}">
                                <x-input-error :messages="$errors->get('scheduled_delivery_date')" />
                            </div>
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">

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
                        登録
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
