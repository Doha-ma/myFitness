<?php

namespace Database\Seeders;

use App\Models\SubscriptionType;
use Illuminate\Database\Seeder;

class SubscriptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptionTypes = [
            [
                'name' => 'Mensuel',
                'slug' => 'mensuel',
                'description' => 'Accès illimité pour 30 jours',
                'base_price' => 49.99,
                'discount_type' => 'percentage',
                'discount_value' => 0,
                'duration_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Trimestriel',
                'slug' => 'trimestriel',
                'description' => 'Accès illimité pour 90 jours avec réduction',
                'base_price' => 149.99,
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'duration_days' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Annuel',
                'slug' => 'annuel',
                'description' => 'Accès illimité pour 365 jours avec meilleure réduction',
                'base_price' => 499.99,
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'duration_days' => 365,
                'is_active' => true,
            ],
            [
                'name' => 'Étudiant',
                'slug' => 'etudiant',
                'description' => 'Tarif spécial pour étudiants (validation requise)',
                'base_price' => 39.99,
                'discount_type' => 'fixed',
                'discount_value' => 10.00,
                'duration_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Essai Gratuit',
                'slug' => 'essai-gratuit',
                'description' => 'Période d\'essai de 7 jours',
                'base_price' => 0.00,
                'discount_type' => 'percentage',
                'discount_value' => 100,
                'duration_days' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($subscriptionTypes as $subscriptionType) {
            SubscriptionType::create($subscriptionType);
        }

        $this->command->info('Subscription types created successfully!');
    }
}
