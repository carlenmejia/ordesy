<?php

namespace Modules\Subdomain\Http\Middleware;


use App\Models\Restaurant;
use Closure;

class RestaurantNotFound
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $restaurant = Restaurant::where('sub_domain', request()->getHost())->first();

        if ($restaurant) {
            return $next($request);
        }

        abort(325);
    }
}
