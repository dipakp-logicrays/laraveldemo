<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reply;
    protected $originalComment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $reply, Comment $originalComment)
    {
        $this->reply = $reply;
        $this->originalComment = $originalComment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        // Check if user wants email notifications
        if ($notifiable->email_notifications ?? true) {
            return ['mail', 'database'];
        }

        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $postTitle = $this->reply->post->title;
        $replierName = $this->reply->user->name;
        $postUrl = route('posts.show', $this->reply->post) . '#comment-' . $this->reply->id;

        return (new MailMessage)
            ->subject("New reply to your comment on \"{$postTitle}\"")
            ->greeting("Hello {$notifiable->name}!")
            ->line("{$replierName} replied to your comment on \"{$postTitle}\":")
            ->line("Original comment: \"" . \Str::limit($this->originalComment->content, 100) . "\"")
            ->line("Reply: \"" . \Str::limit($this->reply->content, 150) . "\"")
            ->action('View Reply', $postUrl)
            ->line('Thank you for being an active member of our community!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'comment_reply',
            'reply_id' => $this->reply->id,
            'comment_id' => $this->originalComment->id,
            'post_id' => $this->reply->post_id,
            'post_title' => $this->reply->post->title,
            'replier_name' => $this->reply->user->name,
            'replier_id' => $this->reply->user_id,
            'reply_content' => \Str::limit($this->reply->content, 100),
            'created_at' => $this->reply->created_at,
        ];
    }
}
