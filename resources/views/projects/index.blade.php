<x-sidebar-layout title="案件一覧">
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">案件管理</h1>
        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            新規案件
        </a>
    </div>

    {{-- フィルター --}}
    <form method="GET" action="{{ route('projects.index') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="案件名・クライアント名で検索"
               class="flex-1 min-w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">すべてのステータス</option>
            @foreach(['商談中','進行中','完了','中断'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <select name="client_id" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">すべてのクライアント</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">絞り込む</button>
        @if(request()->hasAny(['search','status','client_id']))
        <a href="{{ route('projects.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700">クリア</a>
        @endif
    </form>

    {{-- テーブル --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @if($projects->isEmpty())
        <div class="p-12 text-center">
            <p class="text-gray-400 text-sm">案件がありません</p>
        </div>
        @else
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">案件名</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">クライアント</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">金額</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">締切</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3">ステータス</th>
                    <th class="text-left text-xs font-medium text-gray-500 px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($projects as $project)
                @php
                    $statusColors = [
                        '商談中' => 'bg-gray-100 text-gray-700',
                        '進行中' => 'bg-indigo-100 text-indigo-700',
                        '完了'   => 'bg-green-100 text-green-700',
                        '中断'   => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <a href="{{ route('projects.show', $project) }}" class="text-sm font-medium text-indigo-600 hover:underline">
                            {{ $project->name }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $project->client->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $project->budget ? '¥' . number_format($project->budget) : '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $project->deadline ? $project->deadline->format('Y/m/d') : '-' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $statusColors[$project->status] ?? '' }}">
                            {{ $project->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('projects.edit', $project) }}" class="text-xs text-gray-500 hover:text-indigo-600">編集</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
</x-sidebar-layout>
