<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">会社名 <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="{{ old('name', $client->name ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
           placeholder="株式会社〇〇">
    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">担当者名</label>
    <input type="text" name="contact_person" value="{{ old('contact_person', $client->contact_person ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
           placeholder="山田 太郎">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
    <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
           placeholder="example@company.com">
    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">電話番号</label>
    <input type="text" name="phone" value="{{ old('phone', $client->phone ?? '') }}"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
           placeholder="03-0000-0000">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">メモ</label>
    <textarea name="notes" rows="3"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="備考など">{{ old('notes', $client->notes ?? '') }}</textarea>
</div>
