<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SettlementPaidNotification extends Notification
{
    use Queueable;

    protected $amount;
    protected $payerName;
    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(float $amount, string $payerName, Event $event)
    {
        $this->amount = $amount;
        $this->payerName = $payerName;
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
            'title' => 'Payment Received (Pending Confirmation)',
            'message' => "{$this->payerName} has marked a payment of " . number_format($this->amount, 2) . " EGP as paid. Please confirm it in '{$this->event->name}'.",
            'link' => route('events.show', $this->event),
            'icon' => 'banknotes',
            'color' => 'yellow-500'
        ];
    }
}
