<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-5xl px-4">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">コール履歴編集</h1>
                <p class="mt-1 text-sm text-gray-500">コール履歴の内容を更新します</p>
            </div>

            <x-flash-message type="success" :message="session('success')" />
            <x-flash-message type="error" :message="session('error')" />

            @can('update', $callHistory)
                <form action="{{ route('customers.calls.update', [$customer, $callHistory]) }}"
                    method="POST"
                    class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">顧客情報</h2>
                        </div>

                        <div class="grid gap-6 px-6 py-6 md:grid-cols-2">
                            <div>
                                <p class="text-sm font-medium text-gray-500">顧客名</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $customer->name }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">顧客ID</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $customer->customer_code }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">電話番号</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $customer->phone_number }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">メールアドレス</p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
                        <div class="border-b px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">コール履歴情報</h2>
                        </div>

                        <div class="grid gap-6 px-6 py-6 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">対応種別</label>
                                <select name="call_type" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                                    @foreach (\App\Enums\CallType::cases() as $type)
                                        <option value="{{ $type->value }}" @selected(old('call_type', $callHistory->call_type->value) === $type->value)>
                                            {{ $type->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error name="call_type" />
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">対応結果</label>
                                <select name="call_result" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                                    @foreach (\App\Enums\CallResult::cases() as $result)
                                        <option value="{{ $result->value }}" @selected(old('call_result', $callHistory->call_result->value) === $result->value)>
                                            {{ $result->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error name="call_result" />

                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">対応チャネル</label>
                                <select name="channel" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                                    @foreach (\App\Enums\CallChannel::cases() as $channel)
                                        <option value="{{ $channel->value }}" @selected(old('channel', $callHistory->channel->value) === $channel->value)>
                                            {{ $channel->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error name="channel" />
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">対応日時</label>
                                <input
                                    type="datetime-local"
                                    name="called_at"
                                    value="{{ old('called_at', optional($callHistory->called_at)->format('Y-m-d\TH:i')) }}"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
                                >
                                <x-input-error name="called_at" />
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-medium text-gray-700">関連受注</label>
                                <select name="order_id" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                                    <option value="">選択しない</option>
                                    @foreach ($customer->orders as $order)
                                        <option value="{{ $order->id }}" @selected(old('order_id', $callHistory->order_id) == $order->id)>
                                            {{ $order->order_code }} / {{ $order->product->name ?? '商品名' }} / {{ $order->order_status }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error name="order_id" />
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-medium text-gray-700">対応内容</label>
                                <textarea
                                    name="call_summary"
                                    rows="5"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
                                >{{ old('call_summary', $callHistory->call_summary) }}</textarea>
                                <x-input-error name="call_summary" />
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">再対応要否</label>
                                <select name="needs_follow_up" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                                    <option value="0" @selected(old('needs_follow_up', $callHistory->needs_follow_up) == 0)>不要</option>
                                    <option value="1" @selected(old('needs_follow_up', $callHistory->needs_follow_up) == 1)>必要</option>
                                </select>
                                <x-input-error name="needs_follow_up" />
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">次回対応予定日</label>
                                <input
                                    type="date"
                                    name="follow_up_date"
                                    value="{{ old('follow_up_date', optional($callHistory->follow_up_date)->format('Y-m-d')) }}"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
                                >
                                <x-input-error name="follow_up_date" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button" onclick="history.back()"
                        class="px-5 py-3 text-sm border rounded-xl bg-blue hover:bg-blue-50">
                        戻る
                        </button>

                        <button
                            type="submit"
                            class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700"
                        >
                            更新
                        </button>
                    </div>
                </form>
            @endcan
        </div>
    </div>
</x-app-layout>
