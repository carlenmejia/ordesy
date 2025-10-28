<?php

namespace Modules\Subdomain\Notifications;


use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class SuperAdminLoginUrlEmail extends BaseNotification
{

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
        // This is added to reset the url if any restaurant  is set
        config(['app.url' => url('/')]);
        $url = getDomainSpecificUrl($url);

        return parent::build()
            ->subject(__('subdomain::app.emailSuperAdmin.subject'))
            ->line(__('subdomain::app.emailSuperAdmin.line3'))
            ->line(__('subdomain::app.emailSuperAdmin.noteLoginUrlChanged') . " [**$url**]($url) ")
            ->action(__('app.login'), $url);
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
