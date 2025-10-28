<?php

namespace Modules\Subdomain\Http\Middleware;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Closure;

class SubdomainCheck
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $host = str_replace('www.', '', request()->getHost());
        $subdomain = config('app.main_application_subdomain');

        $rootCrmSubDomain = preg_replace('#^https?://#', '', $subdomain); // Remove 'http://' or 'https://'

        // tabletrack.test
        $root = getDomain();

        $routeName = request()->route()->getName();


        // If the main application is installed on sub_domain
        // Example main application is installed on froiden.tabletrack.test
        if ($rootCrmSubDomain !== null && $rootCrmSubDomain == $host) {

            // Check login page
            if ($routeName === 'login') {
                return redirect('//' . $host . '/signin');
            }

            return $next($request);
        }

        try {
            $restaurant = Restaurant::where('sub_domain', $host)->first();
        } catch (\Exception $e) {
            $restaurant = null;
        }

        $subdomain = head(explode('.', $host));
        // If subdomain exist is database and root is not to host
        if ($restaurant) {
            // Check if the url is login then do not redirect
            // https://abc.tabletrack.test/login

            $ignore = ['login', 'password.request', 'password.reset', 'logout', 'home', 'shop_restaurant', 'quick_login', 'customer.display'];



            if (in_array($routeName, $ignore)) {
                return $next($request);
            }

            return redirect(route('login'));
        }


        // If Home is opened in root then continue else show not found
        if ($routeName === 'shop_restaurant') {

            if ($root == $host) {
                return $next($request);
            }

            // Show Restaurant Not found Error Page
            $this->restaurantNotFound();
        }

        // Check login page
        if ($routeName === 'login') {

            try {
                if (auth()->check()) {
                    return redirect()->route('dashboard');
                }
            } catch (\Exception $e) {
                // Do nothing
            }

            // If opened login in main domain then redirect to workspace login page
            // https://tabletrack.test/login
            if ($root == $host) {
                return redirect('//' . $root . '/signin');
            }

            // Show Restaurant Not found Error Page
            $this->restaurantNotFound();
        }


        if ($subdomain == head(explode('.', $root))) {
            return $next($request);
        }

        // Redirect to forgot-password when from 325 page
        if ($routeName == 'front.forgot-restaurant') {
            return redirect('//' . $root . '/forgot-restaurant');
        }

        // Redirect to signup when from 325 page
        if ($routeName == 'restaurant_signup') {
            return redirect('//' . $root . '/restaurant-signup');
        }

        // If sub-domain do not exist in database then redirect to works
        return redirect('//' . $root . '/signin');
    }

    public function restaurantNotFound(): void
    {
        // Get the main application subdomain
        $subdomain = config('app.main_application_subdomain');

        // Extract root CRM subdomain
        $rootCrmSubDomain = preg_replace('#^https?://#', '', $subdomain);

        // Get the current domain
        $domain = getDomain();

        // When root domain is a subdomain
        if ($rootCrmSubDomain !== null && $rootCrmSubDomain !== $domain) {
            $domain = $rootCrmSubDomain;
        }

        // Build signup and forgot-restaurant links
        $signupLink = '//' . $domain . '/signup';
        $forgetLink = '//' . $domain . '/forgot-restaurant';

        // Abort with custom HTTP status code and message
        abort(325, __('subdomain::app.restaurantNotFound'), ['signup' => $signupLink, 'forgot-restaurant' => $forgetLink]);
    }
}
