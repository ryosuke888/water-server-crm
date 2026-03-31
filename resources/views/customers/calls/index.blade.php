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

            <!-- コール履歴 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">コール履歴</h2>
                        <p class="text-sm text-gray-500 mt-1">最新の対応内容を確認できます</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($customer->callHistories as $callHistory)
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
                                    <a href="{{ route('customers.calls.show', [$customer, $callHistory]) }}"
                                       class="inline-flex items-center px-3 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-xs font-medium hover:bg-gray-50">
                                        詳細
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- 下部ボタン -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('customers.show', $customer) }}"
                       class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        戻る
                    </a>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('customers.calls.create', $customer) }}"
                           class="inline-flex items-center px-5 py-3 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                            コール登録
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
