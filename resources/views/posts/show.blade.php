@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <article class="lg:w-2/3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @if($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}"
                             class="w-full h-96 object-cover"
                             alt="{{ $post->title }}">
                    @endif

                    <div class="p-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

                        <div class="flex flex-wrap items-center text-sm text-gray-500 mb-6 gap-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $post->user->name }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $post->published_at->format('F d, Y') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <a href="{{ route('categories.show', $post->category) }}" class="hover:text-indigo-600">
                                    {{ $post->category->name }}
                                </a>
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $post->views }} views
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $post->reading_time }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($post->tags as $tag)
                                <a href="{{ route('tags.show', $tag) }}"
                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>

                        @if(!$post->isPublished())
                            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            This post is currently in draft status.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="prose prose-lg max-w-none text-gray-700">
                            {!! nl2br(e($post->content)) !!}
                        </div>

                        @if(auth()->check() && auth()->id() === $post->user_id)
                            <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="text-lg font-semibold text-gray-900 mb-3">Author Actions</h5>
                                <div class="flex space-x-3">
                                    <a href="{{ route('posts.edit', $post) }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit Post
                                    </a>

                                    <form action="{{ route('posts.destroy', $post) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete Post
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        <!-- Comments Section -->
                        <div class="mt-12 border-t pt-8">
                            <h3 class="text-2xl font-semibold text-gray-900 mb-6">
                                Comments ({{ $post->comment_count }})
                            </h3>

                            <!-- Comment Form -->
                            @auth
                                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Leave a Comment</h4>
                                    <form action="{{ route('comments.store', $post) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <textarea name="content"
                                                    rows="4"
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('content') border-red-300 @enderror"
                                                    placeholder="Write your comment here..."
                                                    required>{{ old('content') }}</textarea>
                                            @error('content')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Post Comment
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-6 mb-8 text-center">
                                    <p class="text-gray-600">
                                        Please <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">login</a>
                                        to leave a comment.
                                    </p>
                                </div>
                            @endauth

                            <!-- Comments List -->
                            <div class="space-y-6">
                                @forelse($post->comments as $comment)
                                    @include('posts.partials.comment', ['comment' => $comment])
                                @empty
                                    <p class="text-gray-500 text-center py-8">
                                        No comments yet. Be the first to comment!
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                    <div class="mt-12">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Related Posts</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedPosts as $related)
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    @if($related->featured_image)
                                        <img src="{{ Storage::url($related->featured_image) }}"
                                             class="w-full h-40 object-cover"
                                             alt="{{ $related->title }}">
                                    @endif
                                    <div class="p-4">
                                        <h5 class="text-lg font-semibold text-gray-900 mb-2">
                                            <a href="{{ route('posts.show', $related) }}"
                                               class="hover:text-indigo-600">
                                                {{ Str::limit($related->title, 50) }}
                                            </a>
                                        </h5>
                                        <p class="text-sm text-gray-600">
                                            {{ Str::limit($related->excerpt, 100) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <!-- Sidebar -->
            <div class="lg:w-1/3">
                @include('posts.sidebar')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showReplyForm(commentId) {
        document.getElementById('reply-form-' + commentId).classList.remove('hidden');
    }

    function hideReplyForm(commentId) {
        document.getElementById('reply-form-' + commentId).classList.add('hidden');
    }

    function editComment(commentId) {
        document.getElementById('comment-content-' + commentId).classList.add('hidden');
        document.getElementById('comment-edit-form-' + commentId).classList.remove('hidden');
    }

    function cancelEdit(commentId) {
        document.getElementById('comment-content-' + commentId).classList.remove('hidden');
        document.getElementById('comment-edit-form-' + commentId).classList.add('hidden');
    }
</script>
@endpush
