@extends('layouts.app')

@section('title', $post->title)

@push('meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('posts.show', $post) }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->excerpt }}">
    @if($post->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $post->featured_image) }}">
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('posts.show', $post) }}">
    <meta property="twitter:title" content="{{ $post->title }}">
    <meta property="twitter:description" content="{{ $post->excerpt }}">
    @if($post->featured_image)
        <meta property="twitter:image" content="{{ asset('storage/' . $post->featured_image) }}">
    @endif

    <!-- Article specific -->
    <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->user->name }}">
    <meta property="article:section" content="{{ $post->category->name }}">
    @foreach($post->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach
@endpush

@section('content')
<!-- Reading Progress Bar -->
<div class="fixed top-0 left-0 w-full h-1 bg-gray-200 z-50">
    <div id="reading-progress" class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 transition-all duration-300 ease-out" style="width: 0%"></div>
</div>

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

                        <!-- Post Meta with Enhanced Reading Time -->
                        @include('posts.partials.post-meta', ['post' => $post])

                        <!-- Post content -->
                        <div class="prose prose-lg max-w-none text-gray-700">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                        <!-- Social Sharing -->
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <x-social-share
                                :url="route('posts.show', $post)"
                                :title="$post->title"
                                :description="$post->excerpt"
                            />
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

<!-- Floating Share Button (Mobile) -->
<div class="lg:hidden fixed bottom-6 right-6 z-40">
    <button onclick="document.getElementById('mobile-share-modal').classList.remove('hidden')"
            class="bg-indigo-600 text-white rounded-full p-4 shadow-lg hover:bg-indigo-700 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
        </svg>
    </button>
</div>

<!-- Mobile Share Modal -->
<div id="mobile-share-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end">
    <div class="bg-white w-full rounded-t-xl p-6 transform transition-transform">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Share this post</h3>
            <button onclick="document.getElementById('mobile-share-modal').classList.add('hidden')"
                    class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <x-social-share
            :url="route('posts.show', $post)"
            :title="$post->title"
            :description="$post->excerpt"
        />
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Existing comment functions
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

    // Voting function
    function voteComment(commentId, type) {
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Send AJAX request
        fetch(`/comments/${commentId}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                type: type
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update vote counts
                document.getElementById('likes-count-' + commentId).textContent = data.likes_count;
                document.getElementById('dislikes-count-' + commentId).textContent = data.dislikes_count;

                // Update button styles
                const voteButtons = document.getElementById('vote-buttons-' + commentId);
                const likeButton = voteButtons.querySelector('button:first-child');
                const dislikeButton = voteButtons.querySelector('button:last-child');
                const likeSvg = likeButton.querySelector('svg');
                const dislikeSvg = dislikeButton.querySelector('svg');

                // Reset styles
                likeButton.classList.remove('text-green-600');
                likeButton.classList.add('text-gray-500');
                likeSvg.setAttribute('fill', 'none');

                dislikeButton.classList.remove('text-red-600');
                dislikeButton.classList.add('text-gray-500');
                dislikeSvg.setAttribute('fill', 'none');

                // Apply active styles based on user vote
                if (data.user_vote === 'like') {
                    likeButton.classList.remove('text-gray-500');
                    likeButton.classList.add('text-green-600');
                    likeSvg.setAttribute('fill', 'currentColor');
                } else if (data.user_vote === 'dislike') {
                    dislikeButton.classList.remove('text-gray-500');
                    dislikeButton.classList.add('text-red-600');
                    dislikeSvg.setAttribute('fill', 'currentColor');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while voting. Please try again.');
        });
    }

    // Reading Progress Bar
    document.addEventListener('DOMContentLoaded', function() {
        const progressBar = document.getElementById('reading-progress');
        const article = document.querySelector('article');

        if (progressBar && article) {
            window.addEventListener('scroll', () => {
                const articleTop = article.offsetTop;
                const articleHeight = article.offsetHeight;
                const windowHeight = window.innerHeight;
                const scrolled = window.scrollY;

                // Calculate progress based on article position
                const start = articleTop - windowHeight;
                const end = articleTop + articleHeight - windowHeight;
                const progress = Math.max(0, Math.min(100, ((scrolled - start) / (end - start)) * 100));

                progressBar.style.width = progress + '%';
            });
        }
    });

    // Copy Link Function
    function copyPostLink(url) {
        // Try using the modern clipboard API first
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function() {
                showCopySuccess();
            }).catch(function(err) {
                // Fallback method
                fallbackCopyTextToClipboard(url);
            });
        } else {
            // Fallback method for older browsers
            fallbackCopyTextToClipboard(url);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.top = 0;
        textArea.style.left = 0;
        textArea.style.width = "2em";
        textArea.style.height = "2em";
        textArea.style.padding = 0;
        textArea.style.border = "none";
        textArea.style.outline = "none";
        textArea.style.boxShadow = "none";
        textArea.style.background = "transparent";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            showCopySuccess();
        } catch (err) {
            console.error('Failed to copy: ', err);
            alert('Failed to copy link');
        }

        document.body.removeChild(textArea);
    }

    function showCopySuccess() {
        const button = document.getElementById('copy-link-btn');
        const originalClasses = button.className;

        // Change button appearance
        button.className = 'inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white transition-colors';
        button.setAttribute('title', 'Copied!');

        // Change icon to checkmark
        button.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        `;

        // Reset after 2 seconds
        setTimeout(() => {
            button.className = originalClasses;
            button.setAttribute('title', 'Copy link');
            button.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            `;
        }, 2000);
    }
</script>
@endpush
