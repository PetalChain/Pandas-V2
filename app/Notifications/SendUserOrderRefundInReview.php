<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendUserOrderRefundInReview extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected $orderNumber,
        protected $item,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Your refund for some items in #{$this->orderNumber} is under review")
            ->line('Your refund request will be reviewed shortly.')
            ->lines($this->item)
            // ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
