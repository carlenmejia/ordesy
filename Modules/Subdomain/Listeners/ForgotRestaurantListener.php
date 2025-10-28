<?php

namespace Modules\Subdomain\Listeners;

use App\Models\GlobalSetting;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Modules\Subdomain\Events\RestaurantUrlEvent;
use Modules\Subdomain\Notifications\RestaurantUrlNotification;

class ForgotRestaurantListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(RestaurantUrlEvent $event)
    {
        $restaurant = $event->restaurant;

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })
            ->where('restaurant_id', $restaurant->id)
            ->get();

        Notification::send($users, new RestaurantUrlNotification($restaurant));
    }
}
