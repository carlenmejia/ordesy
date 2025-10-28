<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Restaurant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cashRegisterModule = Module::where('name', 'Cash Register')->first();
        
        if ($cashRegisterModule) {
            // Create the Open Register permission
            $permission = Permission::firstOrCreate([
                'guard_name' => 'web',
                'name' => 'Open Register',
                'module_id' => $cashRegisterModule->id,
            ]);

            // Assign to existing roles (Admin, Branch Head) for all restaurants
            $allPermissions = Permission::where('module_id', $cashRegisterModule->id)->pluck('name')->toArray();
            $restaurants = Restaurant::select('id')->get();

            foreach ($restaurants as $restaurant) {
                $adminRole = Role::where('name', 'Admin_' . $restaurant->id)->first();
                $branchHeadRole = Role::where('name', 'Branch Head_' . $restaurant->id)->first();
                if ($adminRole) {
                    $adminRole->givePermissionTo($allPermissions);
                }
                if ($branchHeadRole) {
                    $branchHeadRole->givePermissionTo($allPermissions);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permission = Permission::where('name', 'Open Register')->first();
        if ($permission) {
            $permission->delete();
        }
    }
};
