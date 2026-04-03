<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-5xl px-4">

            <!-- タイトル -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">顧客登録</h1>
                <p class="text-sm text-gray-500 mt-1">顧客情報を登録します</p>
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

            @can('create', App\Models\Customer::class)
                <form action="{{ route('customers.store') }}" method="POST" class="space-y-8">
                @csrf

                    <!-- 基本情報 -->
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold">契約者情報</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 px-6 py-6">

                            <div>
                                <label class="block text-sm font-medium mb-1">顧客名</label>
                                <input type="text" value="{{ old('name') }}" name="name" required
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-200">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">電話番号</label>
                                <input type="text" value="{{ old('phone_number') }}" name="phone_number" required
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">メール</label>
                                <input type="email" value="{{ old('email') }}" name="email"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                            </div>

                        </div>
                    </div>

                    <!-- 契約者住所 -->
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold">契約者住所情報</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 px-6 py-6">

                        <div>
                            <label class="block text-sm font-medium mb-1">郵便番号</label>
                            <input type="text" value="{{ old('postal_code') }}" name="postal_code"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">都道府県</label>
                            <input type="text" value="{{ old('prefecture') }}" name="prefecture"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">市区町村</label>
                            <input type="text" value="{{ old('city') }}" name="city"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">番地</label>
                            <input type="text" value="{{ old('address_line1') }}" name="address_line1"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">建物名・部屋番号</label>
                            <input type="text" value="{{ old('address_line2') }}" name="address_line2"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                        </div>

                        </div>
                    </div>

                    <!-- 契約情報 -->
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                        <h2 class="text-lg font-semibold">契約情報</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 px-6 py-6">

                        <div>
                            <label class="block text-sm font-medium mb-1">ステータス</label>
                            <select class="w-full rounded-xl border px-4 py-3 text-sm" name="contract_status">
                                <option value="未契約" {{ old('contract_status') === '未契約' ? 'selected' : '' }}>未契約</option>
                                <option value="契約中" {{ old('contract_status') === '契約中' ? 'selected' : '' }}>契約中</option>
                                <option value="解約済" {{ old('contract_status') === '解約済' ? 'selected' : '' }}>解約済</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">備考</label>
                            <textarea rows="4"
                            class="w-full rounded-xl border px-4 py-3 text-sm" name="remarks">{{ old('remarks') }}</textarea>
                        </div>

                        </div>
                    </div>

                    <!-- 注文情報 -->
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold">注文情報</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 px-6 py-6">

                            <div>
                                <label class="block text-sm font-medium mb-1">配送先氏名</label>
                                <input type="text" value="{{ old('shipping_name') }}" name="shipping_name"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">郵便番号</label>
                                <input type="text" value="{{ old('shipping_postal_code') }}" name="shipping_postal_code"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">都道府県</label>
                                <input type="text" value="{{ old('shipping_prefecture') }}" name="shipping_prefecture" name="shipping_prefecture"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">市区町村</label>
                                <input type="text" value="{{ old('shipping_city') }}" name="shipping_city" name="shipping_city"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">番地</label>
                                <input type="text" value="{{ old('shipping_address_line1') }}" name="shipping_address_line1" name="shipping_address_line1"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium mb-1">建物名・部屋番号</label>
                                <input type="text" value="{{ old('shipping_address_line2') }}" name="shipping_address_line2" name="shipping_address_line2"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                            </div>

                        </div>
                    </div>

                    <!-- コール情報 -->

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
</x-app-layout>
