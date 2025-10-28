<?php

namespace Modules\Subdomain\Console;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Modules\Subdomain\Entities\SubdomainSetting;
use Modules\Subdomain\Notifications\SuperAdminLoginUrlEmail;
use Illuminate\Support\Facades\Artisan;

class ActivateModuleCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'subdomain:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all the module settings of asset module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            Artisan::call('module:migrate Subdomain');
        } catch (\Exception $e) {
            // Silent exception
        }

        $restaurants = Restaurant::select(['id', 'name'])->whereNull('sub_domain')->get();

        foreach ($restaurants as $restaurant) {
            SubdomainSetting::addDefaultSubdomain($restaurant);
        }

        $this->sendSuperAdminLoginUrl();
    }

    public function sendSuperAdminLoginUrl()
    {
        $users = User::role('Super Admin')->whereNull('restaurant_id')->get();

        if ($users->count() > 0) {
            try {
                Notification::send($users, new SuperAdminLoginUrlEmail());
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
