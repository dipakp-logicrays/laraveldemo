<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
        $postTitle = $this->comment->post->title;
        $commenterName = $this->comment->user->name;
        $postUrl = route('posts.show', $this->comment->post) . '#comment-' . $this->comment->id;

        return (new MailMessage)
            ->subject("New comment on your post \"{$postTitle}\"")
            ->greeting("Hello {$notifiable->name}!")
            ->line("{$commenterName} commented on your post \"{$postTitle}\":")
            ->line("Comment: \"" . \Str::limit($this->comment->content, 150) . "\"")
            ->action('View Comment', $postUrl)
            ->line('Keep creating great content!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'new_comment',
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'post_title' => $this->comment->post->title,
            'commenter_name' => $this->comment->user->name,
            'commenter_id' => $this->comment->user_id,
            'comment_content' => \Str::limit($this->comment->content, 100),
            'created_at' => $this->comment->created_at,
        ];
    }
}
