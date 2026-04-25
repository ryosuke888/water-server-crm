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

                        <button
                            type="button"
                            id="reset-button"
                            class="px-5 py-3 rounded-xl border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            リセット
                        </button>
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
                                            <x-link-button color="blue" :href="route('customers.show', $customer)">詳細</x-link-button>
                                        </div>
                                    @endcan

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            <!-- ページネーション -->
                <div class="bg-white shadow-sm border border-gray-100 px-4 py-4" id="pagination-area">
                    @if ($customers->hasPages())
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                            <div class="text-sm text-gray-500">
                                全 {{ $customers->total() }} 件中
                                {{ $customers->firstItem() ?? 0 }}〜{{ $customers->lastItem() ?? 0 }} 件を表示
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="px-3 py-2 text-sm border rounded bg-white hover:bg-gray-50"
                                    data-page="{{ $customers->currentPage() - 1 }}"
                                    @disabled($customers->onFirstPage())
                                >
                                    前へ
                                </button>

                                <span class="text-sm text-gray-600">
                                    {{ $customers->currentPage() }} / {{ $customers->lastPage() }}
                                </span>

                                <button
                                    type="button"
                                    class="px-3 py-2 text-sm border rounded bg-white hover:bg-gray-50"
                                    data-page="{{ $customers->currentPage() + 1 }}"
                                    @disabled(!$customers->hasMorePages())
                                >
                                    次へ
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
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
                await fetchCustomers();
        });

        document.getElementById('reset-button').addEventListener('click', async (event) => {
            event.preventDefault();
            document.getElementById('keyword').value = '';
            await fetchCustomers('');
        });

        document.getElementById('pagination-area').addEventListener('click', async (event) => {
            const button = event.target.closest('button[data-page]');

            if (!button || button.disabled) {
                return;
            }

            await fetchCustomers(Number(button.dataset.page));
        });

        async function fetchCustomers(page = '') {
            try {
                const keyword = document.getElementById('keyword').value;
                const apiUrl = new URL('{{ route('api.customers.index') }}');
                apiUrl.searchParams.set('keyword', keyword);

                if (page) {
                    apiUrl.searchParams.set('page', page);
                }

                const browserUrl = new URL('{{ route('customers.index') }}');

                if (keyword) {
                    browserUrl.searchParams.set('keyword', keyword);
                }

                if (page) {
                    browserUrl.searchParams.set('page', page);
                }

                history.pushState(null, '', browserUrl);

                const response = await fetch(apiUrl, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                if (!response.ok) {
                    throw new Error('顧客データの取得に失敗しました。');
                }
                const data = await response.json();
                const customers = data.data;
                const meta = data.meta;

                renderTableBody(customers);
                renderTotalEl(meta);
                renderRangeEl(meta);
                renderPaginationArea(meta);
            } catch(error) {
                alert('顧客データの取得に失敗しました。');
            }

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
                            <div class="truncate">${escapeHtml(customer.name)}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">${escapeHtml(customer.phone_number)}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">${escapeHtml(customer.email)}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">${escapeHtml(customer.contract_status)}</div>
                            </td>
                            <td class="px-4 py-4 text-gray-700 text-left">
                                <div class="truncate">${escapeHtml(formatDate(customer.created_at))}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div>
                                    <a href="/customers/${escapeHtml(customer.id)}" class="inline-flex items-center px-5 py-2 rounded-lg text-sm font-medium text-white bg-blue-500 hover:bg-blue-700">詳細</a>
                                </div>
                            </td>
                        </tr>
                `
            ).join('');

            tableBody.innerHTML = rows;
        }

        function renderTotalEl(meta) {
            const totalEl = document.getElementById('customer-total');
            totalEl.textContent = meta.total ?? 0;
        }

        function renderRangeEl(meta) {
            const rangeEl = document.getElementById('customer-range');
            rangeEl.textContent = `
            ${meta.firstItem ?? 0} - ${meta.lastItem ?? 0} 件を表示
        `;
        }

        function renderPaginationArea(meta) {
            const paginationArea = document.getElementById('pagination-area');
            let pageButtons = '';

            paginationArea.innerHTML = `
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="text-sm text-gray-500">
                        全 ${escapeHtml(meta.total)} 件中
                        ${escapeHtml(meta.firstItem ?? 0)}〜${escapeHtml(meta.lastItem ?? 0)} 件を表示
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            id="prev-page"
                            class="px-3 py-2 text-sm border rounded bg-white hover:bg-gray-50"
                            data-page="${meta.currentPage - 1}"
                            ${meta.currentPage === 1 ? 'disabled' : ''}
                        >
                            前へ
                        </button>

                        <span class="text-sm text-gray-600">
                            ${escapeHtml(meta.currentPage)} / ${escapeHtml(meta.lastPage)}
                        </span>

                        <button
                            type="button"
                            id="next-page"
                            class="px-3 py-2 text-sm border rounded bg-white hover:bg-gray-50"
                            data-page="${meta.currentPage + 1}"
                            ${meta.currentPage === meta.lastPage ? 'disabled' : ''}
                        >
                            次へ
                        </button>
                    </div>
                </div>
            `;
        }

        function escapeHtml(value) {
            if (value === null || value === undefined) return '';
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
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
