<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="my-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <x-flash-message type="success" :message="session('success')" />
            <x-flash-message type="error" :message="session('error')" />

            <!-- 顧客検索 -->

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <form action="{{ route('customers.index') }}" method="GET" class="flex flex-col gap-4 md:flex-row md:items-end">
                    <div class="flex-1">
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">顧客検索</label>
                        <input
                            type="text"
                            name="keyword"
                            id="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="顧客名、電話番号、メールアドレス、顧客IDで検索"
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
                            href="{{ route('customers.index') }}"
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
                    <span class="font-semibold text-gray-900">{{ $customers->total() }}</span>
                    件
                </p>

                <p class="text-sm text-gray-500">
                    {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} 件を表示
                </p>
            </div>

            <!-- 顧客一覧 -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 mb-5">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-[20%] px-4 py-4 text-left font-semibold text-gray-600">顧客名</th>
                            <th class="w-[20%] px-4 py-4 text-left font-semibold text-gray-600">電話番号</th>
                            <th class="w-[20%] px-4 py-4 text-left font-semibold text-gray-600">メールアドレス</th>
                            <th class="w-[20%] px-4 py-4 text-left font-semibold text-gray-600">契約状況</th>
                            <th class="w-[10%] px-4 py-4 text-left font-semibold text-gray-600">登録日</th>
                            <th class="w-[10%] px-4 py-4 text-left font-semibold text-gray-600">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($customers as $customer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">{{ $customer->name }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-left">
                                    <div class="truncate">{{ $customer->phone_number }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-left">
                                    <div class="truncate">{{ $customer->email }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-left">
                                    <div class="truncate">{{ $customer->contract_status?->label() }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-left">
                                    <div class="truncate">{{ $customer->created_at->format('Y/m/d') }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    @can('view', $customer)
                                        <div>
                                            <form action="{{ route('customers.show', $customer) }}" method="get">
                                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">詳細</button>
                                            </form>
                                        </div>
                                    @endcan

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            <!-- ページネーション -->
            @if ($customers->hasPages())
                <div class="bg-white shadow-sm border border-gray-100 px-4 py-4">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                        <div class="text-sm text-gray-500">
                            全 {{ $customers->total() }} 件中
                            {{ $customers->firstItem() ?? 0 }}〜{{ $customers->lastItem() ?? 0 }} 件を表示
                        </div>

                        <div class="flex items-center justify-center md:justify-end">
                            {{ $customers->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            @endif
            </div>

            <!-- アクションボタン -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-3">
                    @can('import-customers')
                        <x-link-button color="emerald" :href="route('customers.import.create')">csv取込</x-link-button>
                    @endcan
                </div>
                <div class="flex flex-wrap gap-3">
                    @can('create', App\Models\Customer::class)
                        <x-link-button color="emerald" :href="route('customers.create')">顧客登録</x-link-button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
