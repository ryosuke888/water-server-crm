<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 py-8 space-y-6 mt-5">

            <!-- 顧客ヘッダー -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-center gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>

                                @if ($customer->contract_status === "解約済")
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                        {{ $customer->contract_status }}
                                    </span>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 mt-1">顧客ID: {{ $customer->customer_code }}</p>
                                <p class="text-sm text-gray-500 mt-1">ステータス: {{ $customer->contract_status }}</p>
                                <p class="text-sm text-gray-500">登録日: {{ $customer->created_at->format('Y/m/d') }}</p>
                                <p class="text-sm text-gray-500">更新日: {{ $customer->updated_at->format('Y/m/d') }}</p>
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

            <!-- コール履歴詳細 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">コール履歴詳細</h2>
                        <p class="text-sm text-gray-500 mt-1">対応内容の詳細を確認できます</p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                            {{ $callHistory->call_type->label() }}
                        </span>

                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">
                            {{ $callHistory->call_result->label() }}
                        </span>

                        @if ($callHistory->needs_follow_up)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                再対応あり
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                再対応なし
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- 基本情報 -->
                    <div class="bg-gray-50 rounded-2xl p-5 space-y-4">
                        <h3 class="text-base font-semibold text-gray-900">基本情報</h3>

                        <div>
                            <p class="text-sm text-gray-500">対応種別</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->call_type->label() }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">対応結果</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->call_result->label() }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">対応チャネル</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->channel->label() }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">対応日時</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->called_at }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">対応者</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->user->name ?? '担当者名' }}
                            </p>
                        </div>
                    </div>

                    <!-- 関連情報 -->
                    <div class="bg-gray-50 rounded-2xl p-5 space-y-4">
                        <h3 class="text-base font-semibold text-gray-900">関連情報</h3>

                        <div>
                            <p class="text-sm text-gray-500">関連受注</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->order?->order_code ?? '関連受注なし' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">商品名</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->order?->product?->name ?? 'なし' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">受注ステータス</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->order?->order_status ?? 'なし' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">次回対応予定日</p>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $callHistory->follow_up_date ?? 'なし' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 対応内容 -->
                <div class="mt-6 bg-gray-50 rounded-2xl p-5">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">対応内容</h3>
                    <div class="rounded-xl bg-white border border-gray-100 p-4">
                        <p class="text-sm leading-7 text-gray-800">
                            {{ $callHistory->call_summary }}
                        </p>
                    </div>
                </div>

                <!-- 下部ボタン -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('customers.calls.index', $customer) }}"
                       class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        一覧へ戻る
                    </a>

                    <div class="flex flex-wrap gap-3">
                        @can('update', $callHistory)
                            <a href="{{ route('customers.calls.edit', [$customer, $callHistory]) }}"
                            class="inline-flex items-center px-5 py-3 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                                編集する
                            </a>
                        @endcan

                        @can('create', App\Models\CallHistory::class)
                            <a href="{{ route('customers.calls.create', $customer) }}"
                            class="inline-flex items-center px-5 py-3 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                                新規コール登録
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
