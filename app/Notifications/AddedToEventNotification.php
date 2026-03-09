<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddedToEventNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $inviterName;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event, string $inviterName)
    {
        $this->event = $event;
        $this->inviterName = $inviterName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Event Invitation',
            'message' => "{$this->inviterName} has added you to the event '{$this->event->name}'.",
            'link' => route('events.show', $this->event),
            'icon' => 'calendar-plus',
            'color' => 'brand-500'
        ];
    }
}
