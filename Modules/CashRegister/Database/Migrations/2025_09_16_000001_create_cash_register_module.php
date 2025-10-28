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
        $cashRegisterModule = Module::firstOrCreate(['name' => 'Cash Register']);
        
        $permissions = [
            'Manage Cash Register',
            'View Cash Register Reports',
            'Manage Denominations',
            'Approve Cash Register',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'guard_name' => 'web',
                'name' => $name,
                'module_id' => $cashRegisterModule->id,
            ]);
        }

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $cashRegisterModule = Module::where('name', 'Cash Register')->first();
        
        if ($cashRegisterModule) {
            $permissions = Permission::where('module_id', $cashRegisterModule->id)->delete();
        }
    }

};