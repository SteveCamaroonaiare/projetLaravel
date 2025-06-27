<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderConfirmationNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Confirmation de votre commande')
                    ->greeting('Bonjour !')
                    ->line("Merci pour votre commande #{$this->order->id}")
                    ->line("Adresse de livraison : {$this->order->shipping_address}")
                    ->line("Montant total : " . $this->order->total . " MAD")
                    ->line('Nous vous informerons une fois que la commande sera expédiée.')
                    ->salutation('Merci pour votre confiance.');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    
}
