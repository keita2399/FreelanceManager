<x-sidebar-layout title="請求書一覧">
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">請求書</h1>
        <a href="{{ route('invoices.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            新規請求書
        </a>
    </div>

    {{-- サマリーカード --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-xs text-gray-500 mb-1">総請求金額</p>
            <p class="text-xl font-bold text-gray-900">¥{{ number_format($totalAmount) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-xs text-gray-500 mb-1">未払い金</p>
            <p class="text-xl font-bold text-red-600">¥{{ number_format($unpaidAmount) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-xs text-gray-500 mb-1">入金済み</p>
            <p class="text-xl font-bold text-green-600">¥{{ number_format($paidAmount) }}</p>
        </div>
    </div>

    {{-- フィルター --}}
    <form method="GET" action="{{ route('invoices.index') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex gap-3">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">すべて</option>
            @foreach(['未送付','送付済','入金済','未入金期限超過'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">絞り込む</button>
        @if(request('status'))
        <a href="{{ route('invoices.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">クリア</a>
        @endif
    </form>

    {{-- テーブル --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @if($invoices->isEmpty())
        <div class="p-12 text-center">
            <p class="text-gray-400 text-sm">請求書がありません</p>
        </div>
        @else
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">請求番号</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">案件</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">金額</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">発行日</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">支払期限</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">ステータス</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoices as $invoice)
                @php
                    $iColors = ['未送付'=>'bg-gray-100 text-gray-600','送付済'=>'bg-blue-100 text-blue-700','入金済'=>'bg-green-100 text-green-700','未入金期限超過'=>'bg-red-100 text-red-700'];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        <a href="{{ route('projects.show', $invoice->project) }}" class="hover:text-indigo-600">{{ $invoice->project->name }}</a>
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">¥{{ number_format($invoice->amount) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $invoice->issue_date->format('Y/m/d') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $invoice->due_date ? $invoice->due_date->format('Y/m/d') : '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $iColors[$invoice->status] ?? '' }}">{{ $invoice->status }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('invoices.edit', $invoice) }}"
                               class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                                編集
                            </a>
                            <button onclick="printInvoice('{{ route('invoices.pdf', $invoice) }}')"
                               class="px-3 py-1.5 text-xs text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                PDF
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<iframe id="print-frame" style="display:none;"></iframe>
<script>
function printInvoice(url) {
    const frame = document.getElementById('print-frame');
    frame.onload = function() {
        frame.contentWindow.print();
        frame.onload = null;
    };
    frame.src = url;
}
</script>
</x-sidebar-layout>
