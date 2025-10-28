<?php

namespace Modules\Subdomain\Notifications;

use App\Models\GlobalSetting;
use App\Models\Restaurant;
use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ForgotRestaurant extends BaseNotification
{

    protected $restaurant;
    protected $settings;

    /**
     * Create a new notification instance.
     *
     * @param Restaurant $restaurant
     */
    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
        $this->settings = GlobalSetting::first();
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
        $url = getDomainSpecificUrl($url, $this->restaurant);

        return parent::build()
            ->subject(__('subdomain::app.email.subject'))
            ->greeting(__('subdomain::app.email.greeting'))
            ->line(__('subdomain::app.email.line1'))
            ->line(__('subdomain::app.email.line2') . $this->restaurant->name)
            ->line(__('subdomain::app.email.instructions'))
            ->line(__('subdomain::app.email.noteLoginUrl') . ": [**$url**]($url) ")
            ->action(__('app.login'), $url)
            ->line(__('subdomain::app.email.support'))
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
