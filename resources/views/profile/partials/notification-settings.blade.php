<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Notification Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Manage your email notification preferences.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.notifications') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox"
                       name="email_notifications"
                       value="1"
                       {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2">{{ __('Receive email notifications') }}</span>
            </label>
        </div>

        <div class="ml-6 space-y-2">
            <label class="inline-flex items-center">
                <input type="checkbox"
                       name="comment_reply_notifications"
                       value="1"
                       {{ old('comment_reply_notifications', $user->comment_reply_notifications) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2">{{ __('Someone replies to my comment') }}</span>
            </label>
        </div>

        <div class="ml-6 space-y-2">
            <label class="inline-flex items-center">
                <input type="checkbox"
                       name="new_comment_notifications"
                       value="1"
                       {{ old('new_comment_notifications', $user->new_comment_notifications) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <span class="ml-2">{{ __('Someone comments on my post') }}</span>
            </label>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'notifications-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
