<x-sidebar-layout title="{{ $client->name }}">
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('clients.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h1>
                @if($client->contact_person)
                <p class="text-sm text-gray-500">担当: {{ $client->contact_person }}</p>
                @endif
            </div>
        </div>
        <a href="{{ route('clients.edit', $client) }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
            編集
        </a>
    </div>

    <div class="grid grid-cols-3 gap-6">
        {{-- 左：基本情報 + サマリー --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">基本情報</h2>
                <dl class="space-y-3">
                    @if($client->email)
                    <div>
                        <dt class="text-xs text-gray-400">メール</dt>
                        <dd class="text-sm text-gray-700 mt-0.5">{{ $client->email }}</dd>
                    </div>
                    @endif
                    @if($client->phone)
                    <div>
                        <dt class="text-xs text-gray-400">電話</dt>
                        <dd class="text-sm text-gray-700 mt-0.5">{{ $client->phone }}</dd>
                    </div>
                    @endif
                    @if($client->notes)
                    <div>
                        <dt class="text-xs text-gray-400">メモ</dt>
                        <dd class="text-sm text-gray-700 mt-0.5 whitespace-pre-line">{{ $client->notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- サマリー --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">取引サマリー</h2>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">総案件数</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $client->projects->count() }}件</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">進行中</dt>
                        <dd class="text-sm font-semibold text-indigo-600">{{ $client->projects->where('status','進行中')->count() }}件</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">完了</dt>
                        <dd class="text-sm font-semibold text-green-600">{{ $client->projects->where('status','完了')->count() }}件</dd>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex justify-between">
                        <dt class="text-sm text-gray-500">総請求額</dt>
                        <dd class="text-sm font-bold text-gray-900">¥{{ number_format($client->projects->sum('invoices_sum_amount')) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- 右：案件一覧 --}}
        <div class="col-span-2 space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-700">案件履歴</h2>
                    <a href="{{ route('projects.create') }}?client_id={{ $client->id }}"
                       class="text-xs text-indigo-600 hover:underline">+ 新規案件</a>
                </div>
                @if($client->projects->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">案件はまだありません</p>
                @else
                <div class="space-y-3">
                    @foreach($client->projects as $project)
                    @php
                        $statusColors = ['商談中'=>'bg-gray-100 text-gray-700','進行中'=>'bg-indigo-100 text-indigo-700','完了'=>'bg-green-100 text-green-700','中断'=>'bg-red-100 text-red-700'];
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                        <div class="min-w-0">
                            <a href="{{ route('projects.show', $project) }}" class="text-sm font-medium text-indigo-600 hover:underline block">
                                {{ $project->name }}
                            </a>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs text-gray-500">
                                    {{ $project->budget ? '¥'.number_format($project->budget) : '金額未定' }}
                                </span>
                                @if($project->deadline)
                                <span class="text-xs text-gray-400">締切: {{ $project->deadline->format('Y/m/d') }}</span>
                                @endif
                            </div>
                        </div>
                        <span class="ml-4 text-xs px-2 py-1 rounded-full flex-shrink-0 {{ $statusColors[$project->status] ?? '' }}">
                            {{ $project->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- 請求履歴 --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">請求履歴</h2>
                @php
                    $allInvoices = $client->projects->flatMap->invoices->sortByDesc('issue_date');
                @endphp
                @if($allInvoices->isEmpty())
                <p class="text-sm text-gray-400 text-center py-4">請求書はまだありません</p>
                @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left text-xs text-gray-400 pb-2">請求番号</th>
                            <th class="text-left text-xs text-gray-400 pb-2">案件</th>
                            <th class="text-left text-xs text-gray-400 pb-2">金額</th>
                            <th class="text-left text-xs text-gray-400 pb-2">発行日</th>
                            <th class="text-left text-xs text-gray-400 pb-2">ステータス</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($allInvoices as $invoice)
                        @php $iColors = ['未送付'=>'bg-gray-100 text-gray-600','送付済'=>'bg-blue-100 text-blue-700','入金済'=>'bg-green-100 text-green-700','未入金期限超過'=>'bg-red-100 text-red-700']; @endphp
                        <tr>
                            <td class="py-2 text-sm text-gray-700">{{ $invoice->invoice_number }}</td>
                            <td class="py-2 text-sm text-gray-500">{{ $invoice->project->name }}</td>
                            <td class="py-2 text-sm font-medium text-gray-900">¥{{ number_format($invoice->amount) }}</td>
                            <td class="py-2 text-sm text-gray-500">{{ $invoice->issue_date->format('Y/m/d') }}</td>
                            <td class="py-2">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $iColors[$invoice->status] ?? '' }}">{{ $invoice->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
</x-sidebar-layout>
