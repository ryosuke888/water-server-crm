<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="my-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <x-flash-message type="success" :message="session('success')" />
            <x-flash-message type="error" :message="session('error')" />

            <!-- 顧客検索 -->

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <form action="{{ route('customers.index') }}" id="search" method="GET" class="flex flex-col gap-4 md:flex-row md:items-end">
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
                            id="reset-button"
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
                    <span id="customer-total" class="font-semibold text-gray-900">{{ $customers->total() }}</span>
                    件
                </p>

                <p id="customer-range" class="text-sm text-gray-500">
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
                    <tbody id="customer-table-body" class="divide-y divide-gray-100 bg-white">
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
                <div class="bg-white shadow-sm border border-gray-100 px-4 py-4" id="pagination-area">
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
    <script>
        document.getElementById('search').addEventListener('submit', async (event) => {
                event.preventDefault();
                fetchCustomers();
        });

        document.getElementById('reset-button').addEventListener('click', async () => {
            event.preventDefault();
            keyword.value = '';
            await fetchCustomers('');
        });

        async function fetchCustomers(page = '') {
            const keyword = document.getElementById('keyword').value;
            const url = new URL('{{ route('api.customers.index') }}');
            url.searchParams.set('keyword', keyword);
            if (page) {
                url.searchParams.append('page', page);
            }
            const response = await fetch(url);
            const data = await response.json();
            const customers = data.data;
            const meta = data.meta;
            const resetButton = document.getElementById('reset-button');
            renderTableBody(customers);
            renderTotalEl(meta);
            renderRangeEl(meta);
            renderPaginationArea(meta);

        }

        function renderTableBody(customers) {
            const tableBody = document.getElementById('customer-table-body');

            if (customers.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                            該当する顧客がありません。
                        </td>
                    </tr>
                `;
                return;
            }

            const rows = customers.map(customer => `
                    <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 text-gray-700 text-left">
                            <div class="truncate">${customer.name}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">${customer.phone_number}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">${customer.email}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">${customer.contract_status}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">${formatDate(customer.created_at)}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div>
                                    <form action="/customers/${customer.id}" method="get">
                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">詳細</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                `
            ).join('');

            tableBody.innerHTML = rows;
        }

        function renderTotalEl(meta) {
            const totalEl = document.getElementById('customer-total');
            totalEl.innerHTML = `
            <span id="customer-total" class="font-semibold text-gray-900">${meta.total}</span>
        `;
        }

        function renderRangeEl(meta) {
            const rangeEl = document.getElementById('customer-range');
            rangeEl.innerHTML = `
            ${meta.firstItem ?? 0} - ${meta.lastItem ?? 0} 件を表示
        `;
        }

        function renderPaginationArea(meta) {
            const paginationArea = document.getElementById('pagination-area');
            let pageButtons = '';

            // // previousボタン
            // if (meta.currentPage === 1) {
            //     pageButtons += `
            //         <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-l-md leading-5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400" aria-hidden="true">
            //             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            //                 <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            //             </svg>
            //         </span>
            //     `
            // }
            // if (1 < meta.currentPage && meta.currentPage <= meta.lastPage) {
            //     pageButtons += `
            //         <a href="http://localhost:8080/customers?page=${meta.currentPage - 1}" rel="prev" class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:active:bg-gray-700 dark:focus:border-blue-800 dark:text-gray-300 dark:hover:bg-gray-900 dark:hover:text-gray-300" aria-label="&amp;laquo; Previous">
            //             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            //                 <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            //             </svg>
            //         </a>

            //     `;
            // }

            // // pageボタン
            // const startPage = Math.max(1, meta.currentPage - 1);
            // const endPage = Math.min(meta.lastPage, meta.currentPage + 1);

            // for (let page = startPage; page <= endPage; page++) {
            //     pageButtons += `
            //         <a href="http://localhost:8080/customers?page=${page}" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:text-gray-300 dark:active:bg-gray-700 dark:focus:border-blue-800 hover:bg-gray-100 dark:hover:bg-gray-900" aria-label="Go to page ${page}">
            //             ${page}
            //         </a>
            //     `;
            // }

            // // nextボタン
            // if (meta.currentPage < meta.lastPage) {
            //     pageButtons += `
            //         <a href="http://localhost:8080/customers?page=${meta.currentPage + 1}" rel="next" class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:active:bg-gray-700 dark:focus:border-blue-800 dark:text-gray-300 dark:hover:bg-gray-900 dark:hover:text-gray-300" aria-label="Next &amp;raquo;">
            //             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            //                 <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            //             </svg>
            //         </a>

            //     `;
            // }

            // if (meta.currentPage === meta.lastPage) {
            //     pageButtons += `
            //         <span class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed rounded-r-md leading-5 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400" aria-hidden="true">
            //             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            //                 <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            //             </svg>
            //         </span>
            //     `
            // }

            paginationArea.innerHTML = `
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="text-sm text-gray-500">
                        全 ${meta.total} 件中
                        ${meta.firstItem ?? 0}〜${meta.lastItem ?? 0} 件を表示
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            id="prev-page"
                            class="px-3 py-2 text-sm border rounded bg-white hover:bg-gray-50"
                            ${meta.currentPage === 1 ? 'disabled' : ''}
                        >
                            前へ
                        </button>

                        <span class="text-sm text-gray-600">
                            ${meta.currentPage} / ${meta.lastPage}
                        </span>

                        <button
                            type="button"
                            id="next-page"
                            class="px-3 py-2 text-sm border rounded bg-white hover:bg-gray-50"
                            ${meta.currentPage === meta.lastPage ? 'disabled' : ''}
                        >
                            次へ
                        </button>
                    </div>
                </div>
            `;

            const prevButton = document.getElementById('prev-page');
            const nextButton = document.getElementById('next-page');

            if (prevButton && meta.currentPage > 1) {
                prevButton.addEventListener('click', () => {
                    fetchCustomers(meta.currentPage - 1);
                });
            }

            if (nextButton && meta.currentPage < meta.lastPage) {
                nextButton.addEventListener('click', () => {
                    fetchCustomers(meta.currentPage + 1);
                });
            }
        }

        function formatDate(value) {
            if (!value) return '';
            const date = new Date(value);
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1 ).padStart(2, '0');
            const d = date.getDate().toString().padStart(2, '0');
            return `${y}/${m}/${d}`;
        }
    </script>

</x-app-layout>
