{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Posts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Posts</dt>
                                    <dd class="text-3xl font-semibold text-gray-900">{{ $totalPosts }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Published Posts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Published</dt>
                                    <dd class="text-3xl font-semibold text-gray-900">{{ $publishedPosts }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Views -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Views</dt>
                                    <dd class="text-3xl font-semibold text-gray-900">{{ number_format($totalViews) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Comments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Comments</dt>
                                    <dd class="text-3xl font-semibold text-gray-900">{{ $totalComments }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Post
                        </a>
                        <a href="{{ route('posts.my') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            My Posts
                        </a>
                        <a href="{{ route('posts.index') }}?status=draft" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            View Drafts
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Posts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
                            <a href="{{ route('posts.my') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all</a>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentPosts as $post)
                                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600">
                                                    {{ Str::limit($post->title, 50) }}
                                                </a>
                                            </h4>
                                            <div class="mt-1 flex items-center text-xs text-gray-500">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $post->views }} views</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('posts.edit', $post) }}" class="ml-2 text-indigo-600 hover:text-indigo-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No posts yet. <a href="{{ route('posts.create') }}" class="text-indigo-600 hover:text-indigo-900">Create your first post!</a></p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Comments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Comments</h3>
                            <span class="text-sm text-gray-500">On your posts</span>
                        </div>
                        <div class="space-y-4">
                            @forelse($recentComments as $comment)
                                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-600">
                                                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm text-gray-900">
                                                <span class="font-medium">{{ $comment->user->name }}</span>
                                                commented on
                                                <a href="{{ route('posts.show', $comment->post) }}#comment-{{ $comment->id }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                                    {{ Str::limit($comment->post->title, 30) }}
                                                </a>
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                "{{ Str::limit($comment->content, 100) }}"
                                            </p>
                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No comments yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Posts & Categories -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- Popular Posts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Posts</h3>
                        <div class="space-y-3">
                            @forelse($popularPosts as $index => $post)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-indigo-600">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <a href="{{ route('posts.show', $post) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                            {{ Str::limit($post->title, 40) }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ number_format($post->views) }} views</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No popular posts yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Category Distribution -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Posts by Category</h3>
                        <div class="space-y-3">
                            @forelse($categoriesWithCount as $category)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-indigo-500 rounded-full mr-3"></div>
                                        <span class="text-sm text-gray-900">{{ $category->name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900 mr-2">{{ $category->posts_count }}</span>
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $totalPosts > 0 ? ($category->posts_count / $totalPosts) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No categories yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Writing Activity Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Writing Activity (Last 7 Days)</h3>
                    <div class="grid grid-cols-7 gap-2">
                        @foreach($writingActivity as $day => $count)
                            <div class="text-center">
                                <div class="text-xs text-gray-500 mb-1">{{ $day }}</div>
                                <div class="bg-gray-200 rounded-lg p-2 relative">
                                    <div class="absolute bottom-0 left-0 right-0 bg-indigo-500 rounded-lg transition-all duration-300"
                                         style="height: {{ $count > 0 ? min(($count * 20), 100) : 0 }}%"></div>
                                    <span class="relative text-xs font-medium {{ $count > 0 ? 'text-white' : 'text-gray-500' }}">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
