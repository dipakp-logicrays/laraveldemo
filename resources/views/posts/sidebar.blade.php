<!-- Search Widget -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900">Search</h5>
    </div>
    <div class="p-4">
        <form action="{{ route('posts.search') }}" method="GET">
            <div class="flex">
                <input type="text"
                       name="q"
                       placeholder="Search posts..."
                       value="{{ request('q') }}"
                       class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

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
