<x-sidebar-layout title="ダッシュボード">
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">ダッシュボード</h1>
        <p class="text-sm text-gray-500 mt-1">{{ now()->format('Y年n月j日') }}</p>
    </div>

    {{-- サマリーカード --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-sm text-gray-500 mb-1">入金済売上（累計）</p>
            <p class="text-2xl font-bold text-gray-900">¥{{ number_format($totalRevenue) }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-sm text-gray-500 mb-1">進行中プロジェクト</p>
            <p class="text-2xl font-bold text-gray-900">{{ $activeProjects }}<span class="text-base font-normal text-gray-500 ml-1">件</span></p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-sm text-gray-500 mb-1">今月の請求件数</p>
            <p class="text-2xl font-bold text-gray-900">{{ $invoiceCount }}<span class="text-base font-normal text-gray-500 ml-1">件</span></p>
        </div>
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <p class="text-sm text-gray-500 mb-1">未払い金</p>
            <p class="text-2xl font-bold text-red-600">¥{{ number_format($unpaidAmount) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- 売上グラフ --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-base font-semibold text-gray-900 mb-4">売上推移（過去6ヶ月）</h2>
            <div class="flex items-end justify-between h-48 gap-2">
                @php $maxAmount = collect($monthlyRevenue)->max('amount') ?: 1; @endphp
                @foreach($monthlyRevenue as $data)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-xs text-gray-500">
                        {{ $data['amount'] > 0 ? '¥' . number_format($data['amount'] / 10000) . '万' : '' }}
                    </span>
                    <div class="w-full bg-indigo-500 rounded-t transition-all"
                         style="height: {{ $maxAmount > 0 ? max(4, round(($data['amount'] / $maxAmount) * 160)) : 4 }}px;">
                    </div>
                    <span class="text-xs text-gray-500">{{ $data['month'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- 今月の締切案件 --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-900">今月の締切案件</h2>
                <a href="{{ route('projects.index') }}" class="text-xs text-indigo-600 hover:underline">すべて見る</a>
            </div>
            @forelse($upcomingProjects as $project)
            <div class="mb-3 pb-3 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                <a href="{{ route('projects.show', $project) }}" class="text-sm font-medium text-gray-800 hover:text-indigo-600 block">
                    {{ $project->name }}
                </a>
                <p class="text-xs text-gray-500 mt-0.5">{{ $project->client->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">締切: {{ $project->deadline->format('n/j') }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">今月締切の案件はありません</p>
            @endforelse
        </div>
    </div>
</div>
</x-sidebar-layout>
