<?php

namespace Modules\Subdomain\Notifications;

use App\Models\Restaurant;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class RestaurantUrlNotification extends BaseNotification
{

    protected $forRestaurant;

    /**
     * Create a new notification instance.
     *
     * @param Restaurant $restaurant
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->forRestaurant = $restaurant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = [];

        if ($notifiable->email != '') {
            $via = ['mail'];
        }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $url = route('login');
        $url = getDomainSpecificUrl($url, $this->forRestaurant);

        return parent::build()
            ->subject(__('subdomain::app.email.subject'))
            ->line(__('subdomain::app.email.line2') . $this->forRestaurant->name)
            ->line(__('subdomain::app.email.line3'))
            ->line(__('subdomain::app.email.line4'))
            ->line(__('subdomain::app.email.noteLoginUrlChanged') . ": [**$url**]($url) ")
            ->action(__('app.login'), $url)
            ->line(__('subdomain::app.email.line5'))
            ->line(__('subdomain::app.email.thankYou'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
