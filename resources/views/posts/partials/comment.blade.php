<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6" id="comment-{{ $comment->id }}">
    <div class="flex items-start space-x-4">
        <!-- Avatar -->
        <div class="flex-shrink-0">
            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                <span class="text-white font-medium text-sm">
                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                </span>
            </div>
        </div>

        <!-- Comment Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-sm font-semibold text-gray-900">
                        {{ $comment->user->name }}
                    </h5>
                    <p class="text-xs text-gray-500">
                        {{ $comment->created_date }}
                    </p>
                </div>

                <!-- Actions -->
                @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->is_admin))
                    <div class="flex items-center space-x-2">
                        <!-- Edit Button -->
                        @if(auth()->id() === $comment->user_id)
                            <button onclick="editComment({{ $comment->id }})"
                                    class="text-gray-400 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>
                        @endif

                        <!-- Delete Button -->
                        @if(!$comment->hasReplies())
                            <form action="{{ route('comments.destroy', $comment) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Comment Text -->
            <div class="mt-2 text-sm text-gray-700 comment-content" id="comment-content-{{ $comment->id }}">
                {{ $comment->content }}
            </div>

            <!-- Edit Form (Hidden by default) -->
            @if(auth()->check() && auth()->id() === $comment->user_id)
                <form action="{{ route('comments.update', $comment) }}"
                      method="POST"
                      class="mt-2 hidden"
                      id="comment-edit-form-{{ $comment->id }}">
                    @csrf
                    @method('PUT')
                    <textarea name="content"
                              rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              required>{{ $comment->content }}</textarea>
                    <div class="mt-2 flex space-x-2">
                        <button type="submit"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Update
                        </button>
                        <button type="button"
                                onclick="cancelEdit({{ $comment->id }})"
                                class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </form>
            @endif

            <!-- Reply Button -->
            @auth
                <button onclick="showReplyForm({{ $comment->id }})"
                        class="mt-2 text-sm text-indigo-600 hover:text-indigo-500">
                    Reply
                </button>

                <!-- Reply Form (Hidden by default) -->
                <form action="{{ route('comments.store', $post) }}"
                      method="POST"
                      class="mt-4 hidden"
                      id="reply-form-{{ $comment->id }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="content"
                              rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              placeholder="Write your reply..."
                              required></textarea>
                    <div class="mt-2 flex space-x-2">
                        <button type="submit"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Post Reply
                        </button>
                        <button type="button"
                                onclick="hideReplyForm({{ $comment->id }})"
                                class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </form>
            @endauth

            <!-- Nested Replies -->
            @if($comment->replies->count() > 0)
                <div class="mt-4 space-y-4 pl-4 border-l-2 border-gray-200">
                    @foreach($comment->replies as $reply)
                        @include('posts.partials.comment', ['comment' => $reply])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
