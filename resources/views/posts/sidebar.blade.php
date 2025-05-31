<!-- Categories Widget -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Categories</h5>
    </div>
    <div class="p-4">
        <ul class="space-y-2">
            @foreach($sidebarCategories as $category)
                <li>
                    <a href="{{ route('categories.show', $category) }}"
                       class="flex justify-between items-center text-gray-700 hover:text-indigo-600">
                        <span>{{ $category->name }}</span>
                        <span class="text-sm text-gray-500">({{ $category->posts_count }})</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<!-- Popular Tags Widget -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Popular Tags</h5>
    </div>
    <div class="p-4">
        <div class="flex flex-wrap gap-2">
            @foreach($sidebarTags as $tag)
                <a href="{{ route('tags.show', $tag) }}"
                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-gray-200">
                    {{ $tag->name }}
                    <span class="ml-1 text-xs text-gray-500">({{ $tag->posts_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
