<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">案件名 <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
           placeholder="Webサイトリニューアル">
    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">クライアント <span class="text-red-500">*</span></label>
    <select name="client_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('client_id') border-red-500 @enderror">
        <option value="">選択してください</option>
        @foreach($clients as $client)
        <option value="{{ $client->id }}" {{ old('client_id', $project->client_id ?? '') == $client->id ? 'selected' : '' }}>
            {{ $client->name }}
        </option>
        @endforeach
    </select>
    @error('client_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">予算（円）</label>
    <input type="number" name="budget" value="{{ old('budget', $project->budget ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
           placeholder="1000000" min="0">
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">開始日</label>
        <input type="date" name="start_date" value="{{ old('start_date', isset($project->start_date) ? $project->start_date->format('Y-m-d') : '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">締切日</label>
        <input type="date" name="deadline" value="{{ old('deadline', isset($project->deadline) ? $project->deadline->format('Y-m-d') : '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('deadline')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">ステータス <span class="text-red-500">*</span></label>
    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @foreach(['商談中','進行中','完了','中断'] as $s)
        <option value="{{ $s }}" {{ old('status', $project->status ?? '商談中') === $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">説明</label>
    <textarea name="description" rows="3"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="案件の詳細を入力（任意）">{{ old('description', $project->description ?? '') }}</textarea>
</div>
