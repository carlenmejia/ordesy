<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run($restaurant): void
    {
        Tax::create([
            'tax_name' => 'ITBIS',
            'tax_percent' => '18',
            'restaurant_id' => $restaurant->id
        ]);

        Tax::create([
            'tax_name' => 'Servicio',
            'tax_percent' => '10',
            'restaurant_id' => $restaurant->id
        ]);
    }

}
