<?php

use App\Models\Restaurant;

if (!function_exists('getRestaurantBySubDomain')) {

    function getRestaurantBySubDomain()
    {
        try {
            return Restaurant::where('sub_domain', request()->getHost())->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}
