<?php

namespace Modules\Subdomain\Listeners;

use App\Events\NewRestaurantCreatedEvent;
use Modules\Subdomain\Entities\SubdomainSetting;


class NewRestaurantListener
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
    public function handle(NewRestaurantCreatedEvent $event)
    {
        $restaurant = $event->restaurant;

        // Add default subdomain if not exists
        // good to demo purpose
        if (!$restaurant->sub_domain) {
            SubdomainSetting::addDefaultSubdomain($restaurant);
        }
    }
}
