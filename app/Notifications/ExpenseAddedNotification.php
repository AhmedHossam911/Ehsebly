<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseAddedNotification extends Notification
{
    use Queueable;

    protected $expense;
    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Expense $expense, Event $event)
    {
        $this->expense = $expense;
        $this->event = $event;
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
            'title' => 'New Expense Added',
            'message' => "An expense for '{$this->expense->description}' (" . number_format($this->expense->total_amount, 2) . " EGP) was added to '{$this->event->name}'.",
            'link' => route('events.show', $this->event),
            'icon' => 'receipt',
            'color' => 'blue-500'
        ];
    }
}
