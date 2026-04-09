<x-sidebar-layout title="クライアント編集">
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('clients.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">クライアント編集</h1>
    </div>

    <form method="POST" action="{{ route('clients.update', $client) }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        @csrf @method('PATCH')
        @include('clients._form')
        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                更新する
            </button>
            <a href="{{ route('clients.index') }}"
               class="px-6 py-2 text-gray-600 text-sm font-medium border border-gray-200 rounded-lg hover:bg-gray-50">
                キャンセル
            </a>
        </div>
    </form>
</div>
</x-sidebar-layout>
