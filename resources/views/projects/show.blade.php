<x-sidebar-layout title="{{ $project->name }}">
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
                <p class="text-sm text-gray-500">{{ $project->client->name }}</p>
            </div>
        </div>
        <a href="{{ route('projects.edit', $project) }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
            編集
        </a>
    </div>

    <div class="grid grid-cols-3 gap-6">
        {{-- 左：案件情報 + 請求書 --}}
        <div class="col-span-2 space-y-4">
            {{-- 案件情報 --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">案件情報</h2>
                @php
                    $statusColors = ['商談中'=>'bg-gray-100 text-gray-700','進行中'=>'bg-indigo-100 text-indigo-700','完了'=>'bg-green-100 text-green-700','中断'=>'bg-red-100 text-red-700'];
                @endphp
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">金額</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $project->budget ? '¥' . number_format($project->budget) : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">ステータス</dt>
                        <dd><span class="text-xs px-2 py-1 rounded-full {{ $statusColors[$project->status] ?? '' }}">{{ $project->status }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">開始日</dt>
                        <dd class="text-sm text-gray-700">{{ $project->start_date ? $project->start_date->format('Y/m/d') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">締切日</dt>
                        <dd class="text-sm text-gray-700">{{ $project->deadline ? $project->deadline->format('Y/m/d') : '-' }}</dd>
                    </div>
                </dl>
                @if($project->description)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <dt class="text-xs text-gray-400 mb-1">説明</dt>
                    <dd class="text-sm text-gray-700 whitespace-pre-line">{{ $project->description }}</dd>
                </div>
                @endif
            </div>

            {{-- 関連請求書 --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-700">関連請求書</h2>
                    <a href="{{ route('invoices.create', ['project_id' => $project->id]) }}"
                       class="text-xs text-indigo-600 hover:underline">+ 新規請求書</a>
                </div>
                @if($project->invoices->isEmpty())
                <p class="text-sm text-gray-400 text-center py-4">請求書はまだありません</p>
                @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left text-xs text-gray-400 pb-2">請求番号</th>
                            <th class="text-left text-xs text-gray-400 pb-2">金額</th>
                            <th class="text-left text-xs text-gray-400 pb-2">発行日</th>
                            <th class="text-left text-xs text-gray-400 pb-2">ステータス</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($project->invoices as $invoice)
                        @php
                            $iColors = ['未送付'=>'bg-gray-100 text-gray-600','送付済'=>'bg-blue-100 text-blue-700','入金済'=>'bg-green-100 text-green-700','未入金期限超過'=>'bg-red-100 text-red-700'];
                        @endphp
                        <tr>
                            <td class="py-2 text-sm text-indigo-600">
                                <a href="{{ route('invoices.edit', $invoice) }}" class="hover:underline">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="py-2 text-sm text-gray-700">¥{{ number_format($invoice->amount) }}</td>
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

        {{-- 右：アクティビティ --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">アクティビティ</h2>
                <form method="POST" action="{{ route('projects.activities.store', $project) }}" class="mb-5">
                    @csrf
                    <textarea name="content" rows="3" placeholder="進捗メモを入力..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                    <button type="submit"
                            class="mt-2 w-full px-3 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                        メモを追加
                    </button>
                </form>
                <div class="space-y-3">
                    @forelse($project->activities as $activity)
                    @if($activity->type === 'status_change')
                    {{-- ステータス変更履歴 --}}
                    <div class="flex items-start gap-2">
                        <div class="mt-1 w-5 h-5 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div class="text-sm flex-1">
                            <span class="text-gray-500">ステータス変更: </span>
                            <span class="text-gray-500 line-through">{{ $activity->old_value }}</span>
                            <span class="text-gray-400 mx-1">→</span>
                            <span class="font-medium text-gray-800">{{ $activity->new_value }}</span>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $activity->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>
                    @else
                    {{-- 通常メモ --}}
                    <div class="flex items-start gap-2">
                        <div class="mt-1 w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="text-sm flex-1">
                            <p class="text-gray-700">{{ $activity->content }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $activity->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    @empty
                    <p class="text-sm text-gray-400 text-center py-2">アクティビティはまだありません</p>
                    @endforelse
                </div>
            </div>

            {{-- 削除ボタン --}}
            <form method="POST" action="{{ route('projects.destroy', $project) }}"
                  onsubmit="return confirm('この案件を削除しますか？')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 text-red-500 text-sm border border-red-200 rounded-lg hover:bg-red-50">
                    案件を削除
                </button>
            </form>
        </div>
    </div>
</div>
</x-sidebar-layout>
