<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KotCancelReason;

class KotCancelReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($restaurant = null): void
    {
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
                'reason' => 'Tiempo de preparación muy largo',
                'cancel_order' => false,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Problema de calidad con los ingredientes',
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
                'reason' => 'El restaurante cierra temprano',
                'cancel_order' => true,
                'cancel_kot' => true,
            ],
            [
                'reason' => 'Otro',
                'cancel_order' => true,
                'cancel_kot' => true,
            ],
        ];

        foreach ($cancelReasons as $reason) {
            $reason['restaurant_id'] = $restaurant->id ?? null;
            KotCancelReason::create($reason);
        }
    }
}