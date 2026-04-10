<x-app-layout>
    <x-slot name="header">

    </x-slot>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-5xl px-4">

            <!-- タイトル -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">顧客編集</h1>
                <p class="text-sm text-gray-500 mt-1">顧客情報を更新します</p>
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

            @can('update', $customer)
                <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-8">
                @csrf
                @method('PATCH')

                    <!-- 基本情報 -->
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold">契約者情報</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 px-6 py-6">

                        <div>
                            <label class="block text-sm font-medium mb-1">顧客ID</label>
                            <input type="text" value="{{ $customer->customer_code }}" readonly
                            class="w-full rounded-xl border bg-gray-100 px-4 py-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">顧客名</label>
                            <input type="text" value="{{ old('name', $customer->name) }}"  name="name" required
                            class="w-full rounded-xl border px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-200">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">電話番号</label>
                            <input type="text" value="{{ old('phone_number', $customer->phone_number) }}"  name="phone_number" required
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">メール</label>
                            <input type="email" value="{{ old('email', $customer->email) }}" name="email"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                            <input type="text" value="{{ old('postal_code', $customer->postal_code) }}" name="postal_code"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">都道府県</label>
                            <input type="text" value="{{ old('prefecture', $customer->prefecture) }}" name="prefecture"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                            @error('prefecture')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">市区町村</label>
                            <input type="text" value="{{ old('city', $customer->city) }}" name="city"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">番地</label>
                            <input type="text" value="{{ old('address_line1', $customer->address_line1) }}" name="address_line1"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                            @error('address_line1')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">建物名・部屋番号</label>
                            <input type="text" value="{{ old('address_line2', $customer->address_line2) }}" name="address_line2"
                            class="w-full rounded-xl border px-4 py-3 text-sm">
                            @error('address_line2')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                <option value="{{ App\Enums\CustomerContractStatus::PROSPECT }}" {{ old('contract_status', $customer->contract_status) === App\Enums\CustomerContractStatus::PROSPECT ? 'selected' : '' }}>未契約</option>
                                <option value="{{ App\Enums\CustomerContractStatus::ACTIVE }}" {{ old('contract_status', $customer->contract_status) === App\Enums\CustomerContractStatus::ACTIVE ? 'selected' : '' }}>契約中</option>
                                <option value="{{ App\Enums\CustomerContractStatus::CANCELED }}" {{ old('contract_status', $customer->contract_status) === App\Enums\CustomerContractStatus::CANCELED ? 'selected' : '' }}>解約済</option>
                            </select>
                            @error('contract_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">備考</label>
                            <textarea rows="4"
                            class="w-full rounded-xl border px-4 py-3 text-sm" name="remarks">{{ old('remarks', $customer->remarks) }}</textarea>
                            @error('remarks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        </div>
                    </div>

                    <!-- 配送先情報 -->
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold">配送先情報</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 px-6 py-6">

                            <div>
                                <label class="block text-sm font-medium mb-1">配送先氏名</label>
                                <input type="text" value="{{ old('shipping_name', $customer->shipping_name) }}" name="shipping_name"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                                @error('shipping_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">郵便番号</label>
                                <input type="text" value="{{ old('shipping_postal_code', $customer->shipping_postal_code) }}" name="shipping_postal_code"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                                @error('shipping_postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">都道府県</label>
                                <input type="text" value="{{ old('shipping_prefecture', $customer->shipping_prefecture) }}" name="shipping_prefecture"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                                @error('shipping_prefecture')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">市区町村</label>
                                <input type="text" value="{{ old('shipping_city', $customer->shipping_city) }}" name="shipping_city"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                                @error('shipping_city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">番地</label>
                                <input type="text" value="{{ old('shipping_address_line1', $customer->shipping_address_line1) }}" name="shipping_address_line1"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                                @error('shipping_address_line1')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium mb-1">建物名・部屋番号</label>
                                <input type="text" value="{{ old('shipping_address_line2', $customer->shipping_address_line2) }}" name="shipping_address_line2"
                                class="w-full rounded-xl border px-4 py-3 text-sm">
                                @error('shipping_address_line2')
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
</x-app-layout>
