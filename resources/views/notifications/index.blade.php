@extends('layouts.app')

@section('title', 'All Notifications')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-900">Notifications</h2>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.mark-as-read') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-500">
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($notifications as $notification)
                    <div class="p-6 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                @if($notification->type === 'App\Notifications\CommentReplyNotification')
                                    <a href="{{ route('posts.show', $notification->data['post_id']) }}#comment-{{ $notification->data['reply_id'] }}"
                                       class="block hover:bg-gray-50 -m-2 p-2 rounded">
                                        <p class="font-medium text-gray-900">
                                            {{ $notification->data['replier_name'] }} replied to your comment
                                        </p>
                                        <p class="text-gray-600 mt-1">
                                            on "<span class="font-medium">{{ $notification->data['post_title'] }}</span>"
                                        </p>
                                        <p class="text-gray-500 text-sm mt-2 italic">
                                            "{{ $notification->data['reply_content'] }}"
                                        </p>
                                        <p class="text-gray-400 text-sm mt-2">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->format('M d, Y \a\t g:i A') }}
                                        </p>
                                    </a>
                                @elseif($notification->type === 'App\Notifications\NewCommentNotification')
                                    <a href="{{ route('posts.show', $notification->data['post_id']) }}#comment-{{ $notification->data['comment_id'] }}"
                                       class="block hover:bg-gray-50 -m-2 p-2 rounded">
                                        <p class="font-medium text-gray-900">
                                            {{ $notification->data['commenter_name'] }} commented on your post
                                        </p>
                                        <p class="text-gray-600 mt-1">
                                            "<span class="font-medium">{{ $notification->data['post_title'] }}</span>"
                                        </p>
                                        <p class="text-gray-500 text-sm mt-2 italic">
                                            "{{ $notification->data['comment_content'] }}"
                                        </p>
                                        <p class="text-gray-400 text-sm mt-2">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->format('M d, Y \a\t g:i A') }}
                                        </p>
                                    </a>
                                @endif
                            </div>

                            <form action="{{ route('notifications.destroy', $notification->id) }}"
                                  method="POST"
                                  class="ml-4"
                                  onsubmit="return confirm('Delete this notification?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <p class="mt-2 text-gray-500">No notifications yet</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="p-6 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
