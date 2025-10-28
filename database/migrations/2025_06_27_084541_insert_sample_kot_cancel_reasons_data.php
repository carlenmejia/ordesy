<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all restaurants
        $restaurants = DB::table('restaurants')->get();

        // Sample cancel reasons data
        $cancelReasons = [
            // Razones de cancelación de pedidos
            [
                'reason' => 'El cliente cambió de opinión',
                'cancel_order' => true,
                'cancel_kot' => false,
            ],
            [
                'reason' => 'El cliente solicitó la cancelación',
                'cancel_order' => true,
                'cancel_kot' => false,
            ],
            [
                'reason' => 'Problemas de pago',
                'cancel_order' => true,
                'cancel_kot' => false,
            ],
            
            [
                'reason' => 'Pedido realizado por error',
                'cancel_order' => true,
                'cancel_kot' => false,
            ],
            [
                'reason' => 'El cliente ya no quiere el pedido',
                'cancel_order' => true,
                'cancel_kot' => false,
            ],


            // Razones de cancelación de KOT
            [
                'reason' => 'Ingrediente no disponible',
                'cancel_order' => false,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Artículo agotado',
                'cancel_order' => false,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Cocina sobrecargada',
                'cancel_order' => false,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Tiempo de preparación muy largo',
                'cancel_order' => false,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Problema de calidad con los ingredientes',
                'cancel_order' => false,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Fallo en el equipo',
                'cancel_order' => false,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Chef no disponible',
                'cancel_order' => false,
                'cancel_kot' => true,
            ],

            // Razones de cancelación tanto de pedido como de KOT
            [
                'reason' => 'Error del sistema/Problema técnico',
                'cancel_order' => true,
                'cancel_kot' => true,
            ],

            [
                'reason' => 'Artículo equivocado pedido',
                'cancel_order' => true,
                'cancel_kot' => true,
            ],


            [
                'reason' => 'Personal no disponible',
                'cancel_order' => true,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Problemas de salud y seguridad',
                'cancel_order' => true,
                'cancel_kot' => true,
            ],
              [
                'reason' => 'Otro',
                'cancel_order' => true,
                'cancel_kot' => true,
            ],


        ];

        // Insert data for each restaurant
        foreach ($restaurants as $restaurant) {
            foreach ($cancelReasons as $reason) {
                // Check if this reason already exists for this restaurant
                $exists = DB::table('kot_cancel_reasons')
                    ->where('restaurant_id', $restaurant->id)
                    ->where('reason', $reason['reason'])
                    ->exists();

                if (!$exists) {
                    DB::table('kot_cancel_reasons')->insert([
                        'restaurant_id' => $restaurant->id,
                        'reason' => $reason['reason'],
                        'cancel_order' => $reason['cancel_order'],
                        'cancel_kot' => $reason['cancel_kot'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Define the sample reasons to remove
        $sampleReasons = [
            'El cliente cambió de opinión',
            'El cliente solicitó la cancelación',
            'Problemas de pago',
            'Dirección de entrega incorrecta',
            'Pedido realizado por error',
            'El cliente ya no quiere el pedido',
            'Ingrediente no disponible',
            'Artículo agotado',
            'Cocina sobrecargada',
            'Tiempo de preparación muy largo',
            'Problema de calidad con los ingredientes',
            'Fallo en el equipo',
            'Chef no disponible',
            'Error del sistema/Problema técnico',
            'Artículo equivocado pedido',
            'Personal no disponible',
            'Problemas de salud y seguridad',
            'Otro',
        ];

        // Remove sample data
        DB::table('kot_cancel_reasons')
            ->whereIn('reason', $sampleReasons)
            ->delete();
    }
};

