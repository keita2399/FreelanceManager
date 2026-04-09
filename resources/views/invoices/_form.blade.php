<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">案件 <span class="text-red-500">*</span></label>
    <select name="project_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('project_id') border-red-500 @enderror">
        <option value="">選択してください</option>
        @foreach($projects as $project)
        <option value="{{ $project->id }}" {{ old('project_id', $invoice->project_id ?? $selectedProject?->id ?? '') == $project->id ? 'selected' : '' }}>
            {{ $project->client->name }} / {{ $project->name }}
        </option>
        @endforeach
    </select>
    @error('project_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">請求番号 <span class="text-red-500">*</span></label>
    <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('invoice_number') border-red-500 @enderror"
           placeholder="INV-2026-001">
    @error('invoice_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">金額（円） <span class="text-red-500">*</span></label>
    <input type="number" name="amount" value="{{ old('amount', $invoice->amount ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('amount') border-red-500 @enderror"
           placeholder="500000" min="0">
    @error('amount')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">発行日 <span class="text-red-500">*</span></label>
        <input type="date" name="issue_date" value="{{ old('issue_date', isset($invoice->issue_date) ? $invoice->issue_date->format('Y-m-d') : '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('issue_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">支払期限</label>
        <input type="date" name="due_date" value="{{ old('due_date', isset($invoice->due_date) ? $invoice->due_date->format('Y-m-d') : '') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">ステータス <span class="text-red-500">*</span></label>
    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @foreach(['未送付','送付済','入金済','未入金期限超過'] as $s)
        <option value="{{ $s }}" {{ old('status', $invoice->status ?? '未送付') === $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">備考</label>
    <textarea name="notes" rows="2"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="備考（任意）">{{ old('notes', $invoice->notes ?? '') }}</textarea>
</div>
