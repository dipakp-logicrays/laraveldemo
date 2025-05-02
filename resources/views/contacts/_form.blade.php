@csrf
@if(isset($contact))
    @method('PUT')
@endif

<div class="mb-4">
    <label class="block">Name</label>
    <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $contact->name ?? '') }}">
</div>

<div class="mb-4">
    <label class="block">Phone</label>
    <input type="text" name="phone" class="w-full border rounded p-2" value="{{ old('phone', $contact->phone ?? '') }}">
</div>

<div class="mb-4">
    <label class="block">Email</label>
    <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email', $contact->email ?? '') }}">
</div>

<div class="mb-4">
    <label class="block">Description</label>
    <textarea name="description" class="w-full border rounded p-2">{{ old('description', $contact->description ?? '') }}</textarea>
</div>

<button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
    {{ isset($contact) ? 'Update' : 'Save' }}
</button>
