<x-sidebar-layout title="請求書編集">
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('invoices.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">請求書編集</h1>
        <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank"
           class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 text-sm rounded-lg hover:bg-indigo-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            PDFダウンロード
        </a>
    </div>
    <form method="POST" action="{{ route('invoices.update', $invoice) }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        @csrf @method('PATCH')
        @include('invoices._form')
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">更新する</button>
            <a href="{{ route('invoices.index') }}" class="px-6 py-2 text-gray-600 text-sm font-medium border border-gray-200 rounded-lg hover:bg-gray-50">キャンセル</a>
        </div>
    </form>
    <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" class="mt-4"
          onsubmit="return confirm('この請求書を削除しますか？')">
        @csrf @method('DELETE')
        <button type="submit" class="px-4 py-2 text-red-500 text-sm border border-red-200 rounded-lg hover:bg-red-50">
            請求書を削除
        </button>
    </form>
</div>
</x-sidebar-layout>
