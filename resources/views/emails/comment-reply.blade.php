@component('mail::message')
# New Reply to Your Comment

Hello {{ $notifiable->name }},

{{ $replierName }} has replied to your comment on "{{ $postTitle }}".

@component('mail::panel')
**Your Comment:**
"{{ Str::limit($originalComment->content, 150) }}"

**{{ $replierName }}'s Reply:**
"{{ Str::limit($reply->content, 200) }}"
@endcomponent

@component('mail::button', ['url' => $url])
View Conversation
@endcomponent

---

### Don't want these emails?
You can manage your notification preferences in your [account settings]({{ route('profile.edit') }}).

Thanks,<br>
{{ config('app.name') }}
@endcomponent
