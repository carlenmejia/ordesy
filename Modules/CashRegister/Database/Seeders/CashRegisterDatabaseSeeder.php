<?php

namespace Modules\CashRegister\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CashRegister\Database\Seeders\DenominationSeeder;

class CashRegisterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed demo denominations here. Module record and permissions are handled in migrations.
    }
}
