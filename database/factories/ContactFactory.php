<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        $sujets = array_keys(Contact::SUJETS);
        
        return [
            'nom' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'sujet' => $this->faker->randomElement($sujets),
            'message' => $this->faker->paragraphs(3, true),
            'lu' => $this->faker->boolean(30), // 30% de chances d'être lu
            'date_traitement' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', 'now'),
            'reponse' => $this->faker->optional(0.2)->paragraphs(2, true),
            'date_reponse' => function (array $attributes) {
                return $attributes['reponse'] ? $this->faker->dateTimeBetween('-1 month', 'now') : null;
            },
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }

    /**
     * Indiquer que le message est non lu.
     */
    public function nonLu(): static
    {
        return $this->state(fn (array $attributes) => [
            'lu' => false,
            'date_traitement' => null,
        ]);
    }

    /**
     * Indiquer que le message est lu.
     */
    public function lu(): static
    {
        return $this->state(fn (array $attributes) => [
            'lu' => true,
            'date_traitement' => now(),
        ]);
    }

    /**
     * Indiquer que le message a une réponse.
     */
    public function avecReponse(): static
    {
        return $this->state(fn (array $attributes) => [
            'reponse' => $this->faker->paragraphs(2, true),
            'date_reponse' => now(),
            'lu' => true,
            'date_traitement' => now(),
        ]);
    }
}