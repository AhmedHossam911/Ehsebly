<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementConfirmedNotification extends Notification
{
    use Queueable;

    protected $amount;
    protected $creditorName;
    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(float $amount, string $creditorName, Event $event)
    {
        $this->amount = $amount;
        $this->creditorName = $creditorName;
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
            'title' => 'Payment Confirmed',
            'message' => "{$this->creditorName} confirmed receiving your " . number_format($this->amount, 2) . " EGP payment in '{$this->event->name}'.",
            'link' => route('events.show', $this->event),
            'icon' => 'check-badge',
            'color' => 'brand-500'
        ];
    }
}
