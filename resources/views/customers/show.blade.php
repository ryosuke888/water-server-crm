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
                            </div>
                        </div>
                    </div>
                    <a href="#" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                        編集
                    </a>
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
                <h2 class="text-lg font-semibold text-gray-900 mb-4">注文情報</h2>
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
                            <tr>
                                <td class="py-3 pr-4">10001</td>
                                <td class="py-3 pr-4">天然水 12L</td>
                                <td class="py-3 pr-4">2</td>
                                <td class="py-3 pr-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                        配送準備中
                                    </span>
                                </td>
                                <td class="py-3 pr-4">2026/03/20</td>
                                <td class="py-3">2026/03/27</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- コール履歴 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            </div>
        </div>
    </div>
</x-app-layout>
