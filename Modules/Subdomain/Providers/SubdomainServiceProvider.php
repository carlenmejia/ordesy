<?php

namespace Modules\Subdomain\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Subdomain\Console\ActivateModuleCommand;
use Modules\Subdomain\Entities\SubdomainSetting;
use Modules\Subdomain\Http\Middleware\SubdomainCheck;
use Modules\Subdomain\Http\Middleware\RestaurantNotFound;
use Modules\Subdomain\Livewire\SuperAdmin\Setting;
use Modules\Subdomain\Livewire\Workspace;
use Modules\Subdomain\Livewire\ForgetRestaurant;
use App\Events\NewRestaurantCreatedEvent;
use Modules\Subdomain\Listeners\NewRestaurantListener;
use Modules\Subdomain\Events\RestaurantUrlEvent;
use Modules\Subdomain\Listeners\ForgotRestaurantListener;

class SubdomainServiceProvider extends ServiceProvider
{

    /**
     * Boot the application events.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path('Subdomain', 'Database/Migrations'));
        $this->registerCommands();
        $this->registerLivewireComponents();

        // Middleware
        $router->aliasMiddleware('sub-domain-check', SubdomainCheck::class);
        $router->aliasMiddleware('restaurant-not-found', RestaurantNotFound::class);

        // Events
        Event::listen(RestaurantUrlEvent::class, ForgotRestaurantListener::class);
        Event::listen(NewRestaurantCreatedEvent::class, NewRestaurantListener::class);
        // Banned_sub_domain
        Validator::extend('banned_sub_domain', function ($attribute, $value, $parameters, $validator) {

            $setting = SubdomainSetting::first();

            $value = explode('.' . getDomain(), $value)[0];
            $main = config('app.main_application_subdomain');

            if ($main == $value . '.' . getDomain()) {
                return false;
            }

            if (is_null($setting->banned_subdomain)) {
                return true;
            }


            $value = strtolower($value);

            return $this->isSubdomainAllowed($value, $setting);
        }, __('subdomain::app.messages.notAllowedToUseThisSubdomain'));
    }

    public function isSubdomainAllowed($subdomain, $setting): bool
    {

        $bannedSubdomains = $setting->banned_subdomain;

        if (in_array($subdomain, $bannedSubdomains)) {
            return false;
        }

        // Check each pattern
        foreach ($bannedSubdomains as $pattern) {
            // Convert SQL wildcards to regex
            $regexPattern = '/^' . str_replace(['%', '_'], ['.*', '.'], preg_quote($pattern, '/')) . '$/';


            if (preg_match($regexPattern, $subdomain)) {
                return false; // Return false if any pattern matches
            }
        }

        return true; // Return true if no pattern matches
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Subdomain', 'Config/config.php') => config_path('subdomain.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Subdomain', 'Config/config.php'),
            'subdomain'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/subdomain');

        $sourcePath = module_path('Subdomain', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/subdomain';
        }, \Config::get('view.paths')), [$sourcePath]), 'subdomain');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/subdomain');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'subdomain');
        } else {
            $this->loadTranslationsFrom(module_path('Subdomain', 'Resources/lang'), 'subdomain');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function registerCommands()
    {
        $this->commands(
            [
                ActivateModuleCommand::class,
            ]
        );
    }

    private function registerLivewireComponents()
    {
        Livewire::component('subdomain::super-admin.setting', Setting::class);
        Livewire::component('subdomain::workspace', Workspace::class);
        Livewire::component('subdomain::forget-restaurant', ForgetRestaurant::class);
    }
}
