<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="my-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
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
                                <div class="truncate">{{ $customer->customer_name }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-left">
                                    <div class="truncate">{{ $customer->phone_number }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-left">
                                    <div class="truncate">{{ $customer->email }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-left">
                                    <div class="truncate">{{ $customer->contract_status }}</div>
                                </td>
                                <td class="px-4 py-4 text-gray-700 text-left">
                                    <div class="truncate">{{ $customer->created_at->format('Y/m/d') }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div>
                                        <form action="{{ route('customers.show', $customer) }}" method="get">
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">詳細</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
