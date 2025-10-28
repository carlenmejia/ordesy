<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Modules\Subdomain\Entities\SubdomainSetting;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sub_domain_module_settings')) {
            Schema::create('sub_domain_module_settings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('license_type', 20)->nullable();
                $table->string('purchase_code')->nullable();
                $table->timestamp('purchased_on')->nullable();
                $table->timestamp('supported_until')->nullable();
                $table->longText('banned_subdomain')->nullable();
                $table->boolean('notify_update')->default(1);
                $table->timestamps();
            });
        }

        SubdomainSetting::create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_domain_module_settings');
    }

};
