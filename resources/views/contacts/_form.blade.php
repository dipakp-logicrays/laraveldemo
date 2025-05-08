@csrf
@if(isset($contact))
    @method('PUT')
@endif

<div class="mb-4">
    <label class="block">Name</label>
    <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $contact->name ?? '') }}">
    @error('name')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="block">Phone</label>
    <input type="text" name="phone" class="w-full border rounded p-2" value="{{ old('phone', $contact->phone ?? '') }}">
    @error('phone')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="block">Email</label>
    <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email', $contact->email ?? '') }}">
    @error('email')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="block">Comments</label>
    <textarea name="description" class="w-full border rounded p-2">{{ old('description', $contact->description ?? '') }}</textarea>
    @error('description')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label class="block">Attachment (PNG, JPG, JPEG - Max 2MB)</label>
    <input type="file" name="attachment" class="w-full border rounded p-2">
    @error('attachment')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
    @if (!empty($contact->attachment) && Storage::disk('public')->exists($contact->attachment))
        <div class="mb-4">
            <label class="block font-semibold">Current Attachment:</label>
            <img src="{{ asset('storage/' . $contact->attachment) }}" alt="Attachment" class="h-24 mb-2">
            <label>
                <input type="checkbox" name="delete_attachment" value="1">
                Delete Attachment
            </label>
        </div>
    @endif

</div>


<button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
    {{ isset($contact) ? 'Update' : 'Save' }}
</button>
