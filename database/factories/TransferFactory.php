<?php

namespace Database\Factories;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transfer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payer_id' => User::factory(),
            'payee_id' => User::factory(),
            'amount' => $this->faker->numberBetween(1,50)
        ];
    }
}
