<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-8 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h1 class="text-2xl font-bold text-gray-900">対応履歴詳細</h1>
            <div class="mt-4 text-sm text-gray-600 space-y-1">
                <p>注文番号: {{ $orderHistory->order_code_snapshot }}</p>
                <p>操作種別: {{ $orderHistory->action_type->label() }}</p>
                <p>概要: {{ $orderHistory->action_summary }}</p>
                <p>対応者: {{ $orderHistory->user->name ?? '不明' }}</p>
                <p>日時: {{ $orderHistory->acted_at }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold mb-4">変更前</h2>
                @foreach (($orderHistory->before_values ?? []) as $key => $value)
                    <div class="border-b py-2">
                        <p class="text-xs text-gray-500">{{ $key }}</p>
                        <p class="text-sm text-gray-800">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold mb-4">変更後</h2>
                @foreach (($orderHistory->after_values ?? []) as $key => $value)
                    <div class="border-b py-2">
                        <p class="text-xs text-gray-500">{{ $key }}</p>
                        <p class="text-sm text-gray-800">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <a href="{{ route('customers.order-histories.index', $customer) }}"
               class="px-4 py-2 rounded border bg-white text-sm">
                一覧へ戻る
            </a>
        </div>
    </div>
</x-app-layout>
