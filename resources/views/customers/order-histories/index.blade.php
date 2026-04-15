<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            受注対応履歴一覧
        </h2>
    </x-slot>

    <div class="my-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- メッセージ --}}
            <x-flash-message type="success" :message="session('success')" />
            <x-flash-message type="error" :message="session('error')" />

            {{-- 件数 --}}
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-4">
                <p class="text-sm text-gray-500">
                    履歴件数：
                    <span class="font-semibold text-gray-900">{{ $orderHistories->total() }}</span>
                    件
                </p>

                <p class="text-sm text-gray-500">
                    {{ $orderHistories->firstItem() ?? 0 }} - {{ $orderHistories->lastItem() ?? 0 }} 件を表示
                </p>
            </div>

            {{-- 一覧 --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 mb-5">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-[15%] px-4 py-4 text-left font-semibold text-gray-600">日時</th>
                            <th class="w-[15%] px-4 py-4 text-left font-semibold text-gray-600">注文番号</th>
                            <th class="w-[15%] px-4 py-4 text-left font-semibold text-gray-600">操作種別</th>
                            <th class="w-[30%] px-4 py-4 text-left font-semibold text-gray-600">概要</th>
                            <th class="w-[15%] px-4 py-4 text-left font-semibold text-gray-600">対応者</th>
                            <th class="w-[10%] px-4 py-4 text-left font-semibold text-gray-600">操作</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($orderHistories as $history)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 text-gray-700">
                                    {{ $history->acted_at?->format('Y/m/d H:i') }}
                                </td>

                                <td class="px-4 py-4 text-gray-700">
                                    {{ $history->order_code_snapshot }}
                                </td>

                                <td class="px-4 py-4 text-gray-700">
                                    {{ $history->action_type->label() }}
                                </td>

                                <td class="px-4 py-4 text-gray-700">
                                    <div class="truncate">
                                        {{ $history->action_summary }}
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-gray-700">
                                    {{ $history->user->name ?? '不明' }}
                                </td>

                                <td class="px-4 py-4">
                                    <x-link-button color="blue" :href="route('customers.order-histories.show', [$customer, $history])">詳細</x-link-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ページネーション --}}
                @if ($orderHistories->hasPages())
                    <div class="bg-white shadow-sm border border-gray-100 px-4 py-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                            <div class="text-sm text-gray-500">
                                全 {{ $orderHistories->total() }} 件中
                                {{ $orderHistories->firstItem() ?? 0 }}〜{{ $orderHistories->lastItem() ?? 0 }} 件を表示
                            </div>

                            <div class="flex items-center justify-center md:justify-end">
                                {{ $orderHistories->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- 戻るボタン --}}
            <div>
                <x-link-button color="white" :href="route('customers.show', $customer)">顧客詳細へ戻る</x-link-button>
            </div>

        </div>
    </div>
</x-app-layout>
