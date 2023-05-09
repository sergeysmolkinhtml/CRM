<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'external_id' => $this->faker->uuid,
            'email' => $this->faker->email,
            'primary_number' => $this->faker->randomNumber(8),
            'secondary_number' => $this->faker->randomNumber(8),
            'client_id' => 1,
            'is_primary' => 1,
        ];
    }
}
