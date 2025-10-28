<?php

namespace Modules\Subdomain\Events;

use App\Models\Restaurant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RestaurantUrlEvent
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }
}
