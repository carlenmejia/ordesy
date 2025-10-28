<?php

namespace Modules\Subdomain\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Subdomain\Events\RestaurantUrlEvent;
use App\Models\Restaurant;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Providers\RouteServiceProvider;
use App\Livewire\Forms\RestaurantSignup;

class SubdomainController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function shopIndex()
    {
        $restaurant = getRestaurantBySubDomain();

        // If someone opens / url then if restaurant found then showing landing page
        if (!$restaurant) {

            return (new HomeController)->landing();
        }

        $queryParams = request()->query();

        $url = '/';

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        // Pass the restaurant hash and all URL parameters to the cart method
        return (new ShopController)->cart($restaurant->hash, $url);
    }


    public function redirectHash($hash)
    {
        $queryParams = request()->query();

        $url = '/';

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        return redirect($url);
    }
    /**
     * @param null $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function workspace()
    {
        $this->pageTitle = __('subdomain::app.core.workspaceTitle');
        return view('subdomain::workspace', $this->data);
    }

    public function forgotRestaurant()
    {
        $this->pageTitle = __('subdomain::app.core.forgotRestaurantTitle');

        return view('subdomain::forgot-restaurant', $this->data);
    }

    public function notifyDomain(Request $request)
    {
        $restaurant = Restaurant::findOrFail($request->restaurant_id);
        event(new RestaurantUrlEvent($restaurant));

        return Reply::success('Successfully notified to all admins');
    }

    public function quickLoginSubdomain($hash)
    {
        $user = User::find(decrypt($hash));

        if ($user) {

            if (cache()->has('quick_login_' . $user->id) && (cache('quick_login_' . $user->id) == $hash)) {

                // Login the user and make session variables
                (new RestaurantSignup)->authLogin($user);

                cache()->forget('quick_login_' . $user->id);

                return redirect(RouteServiceProvider::ONBOARDING_STEPS);
            }
        }


        cache()->forget('quick_login_' . $user->id);

        return redirect('/');
    }
}
