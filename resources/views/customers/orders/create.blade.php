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
                            <select class="w-full rounded-xl border px-4 py-3 text-sm" name="plan_name">
                                <option value="スタンダードプラン" {{ old('plan_name') }}>スタンダードプラン</option>
                                <option value="ファミリープラン" {{ old('plan_name') }}>ファミリープラン</option>
                                <option value="プレミアムプラン" {{ old('plan_name') }}>プレミアムプラン</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">サーバー</label>
                            <select class="w-full rounded-xl border px-4 py-3 text-sm" name="server">
                                <option value="スタンダードサーバー" {{ old('server') }}>スタンダードサーバー</option>
                                <option value="スタイリッシュサーバー" {{ old('server') }}>スタイリッシュサーバー</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">サーバー</label>
                            <select class="w-full rounded-xl border px-4 py-3 text-sm" name="water">
                                <option value="ミネラルウォーター" {{ old('water') }}>ミネラルウォーター</option>
                                <option value="ミネラルナチュラルウォーター" {{ old('water') }}>ミネラルナチュラルウォーター</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">受注種別</label>
                            <select class="w-full rounded-xl border px-4 py-3 text-sm" name="order_type">
                                <option value="初回" {{ old('order_type') }}>初回</option>
                                <option value="定期配送" {{ old('order_type') }}>定期配送</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">数量</label>
                            <input type="text" value="{{ old('quantity') }}" name="quantity"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">小計</label>
                            <input type="text" value="{{ old('unit_price') }}" name="unit_price"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        <div class="">
                            <label class="block text-sm font-medium mb-1">合計</label>
                            <input type="text" value="{{ old('subtotal_amount') }}" name="subtotal_amount"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">運送会社</label>
                            <input type="text" value="{{ old('shipping_company') }}" name="shipping_company"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">お届け日</label>
                            <input type="date" value="{{ old('scheduled_delivery_date') }}" name="scheduled_delivery_date"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
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
                    登録
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
