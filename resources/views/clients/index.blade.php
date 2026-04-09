<x-sidebar-layout title="クライアント一覧">
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">クライアント</h1>
        <a href="{{ route('clients.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            新規クライアント
        </a>
    </div>

    @if($clients->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <p class="text-gray-400 text-sm">クライアントがまだ登録されていません</p>
        <a href="{{ route('clients.create') }}" class="mt-3 inline-block text-indigo-600 text-sm hover:underline">最初のクライアントを追加</a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($clients as $client)
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <a href="{{ route('clients.show', $client) }}" class="font-semibold text-gray-900 hover:text-indigo-600">{{ $client->name }}</a>
                    @if($client->contact_person)
                    <p class="text-sm text-gray-500 mt-0.5">担当: {{ $client->contact_person }}</p>
                    @endif
                </div>
                <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full">
                    {{ $client->projects_count }}件
                </span>
            </div>
            @if($client->email)
            <p class="text-sm text-gray-500 flex items-center mb-1">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ $client->email }}
            </p>
            @endif
            @if($client->phone)
            <p class="text-sm text-gray-500 flex items-center mb-3">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                {{ $client->phone }}
            </p>
            @endif
            <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                <a href="{{ route('clients.edit', $client) }}"
                   class="flex-1 text-center text-xs text-gray-600 hover:text-indigo-600 py-1.5 border border-gray-200 rounded-lg hover:border-indigo-300">
                    編集
                </a>
                <form method="POST" action="{{ route('clients.destroy', $client) }}"
                      onsubmit="return confirm('このクライアントを削除しますか？')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="text-xs text-red-500 hover:text-red-700 px-3 py-1.5 border border-red-100 rounded-lg hover:border-red-300">
                        削除
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
</x-sidebar-layout>
