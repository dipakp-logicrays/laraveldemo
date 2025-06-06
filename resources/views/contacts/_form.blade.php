@csrf
@if(isset($contact))
    @method('PUT')
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Name Field -->
    <div class="col-span-1">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Name <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <input type="text"
                   name="name"
                   id="name"
                   value="{{ old('name', $contact->name ?? '') }}"
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                   placeholder="John Doe"
                   required>
        </div>
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Phone Field -->
    <div class="col-span-1">
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
            Phone <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </div>
            <input type="tel"
                   name="phone"
                   id="phone"
                   value="{{ old('phone', $contact->phone ?? '') }}"
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('phone') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                   placeholder="+1 (555) 123-4567"
                   required>
        </div>
        @error('phone')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email Field -->
    <div class="col-span-1 md:col-span-2">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email Address <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <input type="email"
                   name="email"
                   id="email"
                   value="{{ old('email', $contact->email ?? '') }}"
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                   placeholder="john.doe@example.com"
                   required>
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Description Field -->
    <div class="col-span-1 md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
            Comments / Notes
        </label>
        <div class="relative">
            <textarea name="description"
                      id="description"
                      rows="4"
                      class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                      placeholder="Add any additional notes or comments about this contact...">{{ old('description', $contact->description ?? '') }}</textarea>
        </div>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Attachment Field -->
    <div class="col-span-1 md:col-span-2">
        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
            Profile Photo
        </label>

        @if(isset($contact) && $contact->attachment && Storage::disk('public')->exists($contact->attachment))
            <!-- Current Attachment -->
            <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">Current Photo:</p>
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/' . $contact->attachment) }}"
                         alt="Current attachment"
                         class="w-24 h-24 rounded-lg object-cover border border-gray-300">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Upload a new photo to replace the current one</p>
                        <label class="inline-flex items-center mt-2">
                            <input type="checkbox"
                                   name="delete_attachment"
                                   value="1"
                                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                            <span class="ml-2 text-sm text-red-600">Remove current photo</span>
                        </label>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="attachment" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                        <span>Upload a file</span>
                        <input id="attachment"
                               name="attachment"
                               type="file"
                               class="sr-only"
                               accept="image/png,image/jpg,image/jpeg">
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
            </div>
        </div>
        @error('attachment')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Form Actions -->
<div class="mt-8 border-t border-gray-200 pt-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('contacts.index') }}"
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            Cancel
        </a>

        <div class="flex items-center space-x-3">
            @if(isset($contact))
                <button type="button"
                        onclick="if(confirm('Are you sure you want to reset this form?')) { this.form.reset(); }"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Reset Changes
                </button>
            @endif

            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ isset($contact) ? 'Update Contact' : 'Save Contact' }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('attachment')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add preview functionality here
                console.log('Image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
