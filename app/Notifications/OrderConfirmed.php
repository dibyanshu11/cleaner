<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\OrderConfirmedMail;

class OrderConfirmed extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     *e
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailable = new OrderConfirmedMail($notifiable, $this->order);
        // return (new MailMessage)
        //             ->line('The introduction to the notification.' . $this->order->name)
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you ,Your Order Confirmed!');

        return $mailable->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
