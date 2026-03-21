<?php

namespace Database\Factories;

use App\Models\Etablissement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Etablissement>
 */
class EtablissementFactory extends Factory
{
    protected $model = Etablissement::class;

    public function definition(): array
    {
        $name = fake()->company . ' ' . fake()->city;

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'code' => strtoupper(Str::random(6)),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'timezone' => config('app.timezone', 'UTC'),
            'primary_color' => fake()->hexColor(),
            'accent_color' => fake()->hexColor(),
        ];
    }
}
