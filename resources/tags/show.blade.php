@extends('layouts.app')

@section('title', '#' . $tag->name . ' - Posts')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <span class="text-indigo-600">#</span>{{ $tag->name }}
            </h1>
            <p class="text-sm text-gray-500 mt-2">{{ $posts->total() }} posts tagged with {{ $tag->name }}</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="lg:w-2/3">
                @forelse($posts as $post)
                    <article class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}"
                                 class="w-full h-64 object-cover"
                                 alt="{{ $post->title }}">
                        @endif

                        <div class="p-6">
                            <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                                <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            <div class="flex items-center text-sm text-gray-500 mb-4 space-x-4">
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
                                    {{ $post->published_at->format('M d, Y') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $post->category->name }}
                                </span>
                            </div>

                            <p class="text-gray-600 mb-4">{{ $post->excerpt }}</p>

                            <div class="flex items-center justify-between">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->tags as $postTag)
                                        <a href="{{ route('tags.show', $postTag) }}"
                                           class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $postTag->id === $tag->id ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}">
                                            {{ $postTag->name }}
                                        </a>
                                    @endforeach
                                </div>

                                <a href="{{ route('posts.show', $post) }}"
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-500">
                                    Read More
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <p class="text-gray-500">
                            No posts found with this tag yet.
                        </p>
                    </div>
                @endforelse

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:w-1/3">
                @include('posts.sidebar')
            </div>
        </div>
    </div>
</div>
@endsection
