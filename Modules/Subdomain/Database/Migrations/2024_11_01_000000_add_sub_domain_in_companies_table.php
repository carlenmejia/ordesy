<?php

use App\Models\Restaurant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Subdomain\Entities\SubdomainSetting;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('restaurants', 'sub_domain')) {
            Schema::table('restaurants', function (Blueprint $table) {
                $table->string('sub_domain')->after('id')->nullable();
            });

            $restaurants = Restaurant::select(['id', 'name'])
                ->whereNull('sub_domain')
                ->get();

            foreach ($restaurants as $restaurant) {
                SubdomainSetting::addDefaultSubdomain($restaurant);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('sub_domain');
        });
    }
};
