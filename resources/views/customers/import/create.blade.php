<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="mx-auto max-w-3xl px-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h1 class="text-2xl font-bold text-gray-900">顧客CSV取込</h1>
                <p class="mt-1 text-sm text-gray-500">CSVファイルから顧客情報を一括登録します</p>

                <x-flash-message type="success" :message="session('success')" />
                <x-flash-message type="error" :message="session('error')" />

                <form action="{{ route('customers.import.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CSVファイル</label>
                        <input
                            type="file"
                            name="csv_file"
                            accept=".csv,text/csv"
                            class="block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm"
                        >
                        <x-input-error :messages="$errors->get('csv_file')" />
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4 text-sm text-gray-600">
                        <p class="font-medium text-gray-800 mb-2">取込フォーマット</p>
                        <p>1行目はヘッダー行を設定してください。</p>
                        <p>文字コードは UTF-8 を想定しています。</p>
                        <p>同時にcsvを更新しないで下さい。</p>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('customers.index') }}"
                           class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm hover:bg-gray-50">
                            戻る
                        </a>

                        <button
                            type="submit"
                            class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700"
                        >
                            取込開始
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
